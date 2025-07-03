<?php
session_start();
include('header.php');

// Database connection
$conn = mysqli_connect("localhost", "root", "", "book_management_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to check book availability
function checkBookAvailability($conn, $book_id, $requested_quantity) {
    $sql = "SELECT copies_available FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        return $book['copies_available'] >= $requested_quantity;
    }
    return false;
}

// Function to get book details including available stock
function getBookDetails($conn, $book_id) {
    $sql = "SELECT *, copies_available FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Add item to cart with stock validation
if (isset($_GET['add_to_cart'])) {
    $book_id = $_GET['add_to_cart'];
    
    // Get current cart quantity (0 if not in cart)
    $current_quantity = $_SESSION['cart'][$book_id]['quantity'] ?? 0;
    $requested_quantity = $current_quantity + 1;
    
    // Check availability
    if (!checkBookAvailability($conn, $book_id, $requested_quantity)) {
        $_SESSION['error'] = "Cannot add more than available stock";
        header("Location: homepage.php");
        exit();
    }
    
    // Get book details
    $book = getBookDetails($conn, $book_id);
    
    if ($book) {
        if (isset($_SESSION['cart'][$book_id])) {
            $_SESSION['cart'][$book_id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$book_id] = [
                'title' => $book['title'],
                'price' => $book['price'],
                'image' => $book['image'],
                'quantity' => 1,
                'max_quantity' => $book['copies_available'] // Store max available
            ];
        }
        $_SESSION['success'] = "Book added to cart!";
    }
    header("Location: cart.php");
    exit();
}

// Update cart quantities with stock validation
if (isset($_POST['update_cart'])) {
    $errors = [];
    
    foreach ($_POST['quantity'] as $book_id => $quantity) {
        $quantity = (int)$quantity;
        
        // Skip if book not in cart
        if (!isset($_SESSION['cart'][$book_id])) continue;
        
        // Remove if quantity is 0 or less
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$book_id]);
            continue;
        }
        
        // Check stock availability
        $book = getBookDetails($conn, $book_id);
        if (!$book || $quantity > $book['copies_available']) {
            $errors[] = "Only {$book['copies_available']} available for '{$_SESSION['cart'][$book_id]['title']}'";
            continue;
        }
        
        // Update quantity if valid
        $_SESSION['cart'][$book_id]['quantity'] = $quantity;
        $_SESSION['cart'][$book_id]['max_quantity'] = $book['copies_available'];
    }
    
    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
    }
    
    header("Location: cart.php");
    exit();
}

// Remove item from cart
if (isset($_GET['remove_from_cart'])) {
    $book_id = $_GET['remove_from_cart'];
    if (isset($_SESSION['cart'][$book_id])) {
        unset($_SESSION['cart'][$book_id]);
        $_SESSION['success'] = "Item removed from cart";
    }
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        .content {
            margin-left: 22%;
            padding: 30px;
            background-color: #f5f7fa;
            min-height: 100vh;
        }
        
        .cart-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .cart-table th {
            background-color: #4a6fa5;
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        .cart-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        
        .cart-table img {
            width: 80px;
            height: auto;
            border-radius: 4px;
        }
        
        .quantity-input {
            width: 60px;
            padding: 8px;
            text-align: center;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-block;
            border: none;
        }
        
        .btn-update {
            background-color: #4a6fa5;
            color: white;
        }
        
        .btn-update:hover {
            background-color: #3a5a80;
        }
        
        .btn-remove {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-remove:hover {
            background-color: #c0392b;
        }
        
        .cart-total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        
        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .btn-cart, .btn-checkout {
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 600;
        }
        
        .btn-cart {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-continue:hover {
            background-color: #5a6268;
        }
        
        .btn-checkout {
            background-color: #28a745;
            color: white;
        }
        
        .btn-checkout:hover {
            background-color: #218838;
        }
        
        @media (max-width: 1200px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .cart-table {
                display: block;
                overflow-x: auto;
            }
            
            .cart-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-continue, .btn-checkout {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="cart-container">
            <h2>Your Shopping Cart</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (!empty($_SESSION['cart'])): ?>
                <form method="POST" action="cart.php">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($_SESSION['cart'] as $book_id => $item): 
                                // Get current stock from database
                                $book = getBookDetails($conn, $book_id);
                                $max_quantity = $book ? $book['copies_available'] : $item['max_quantity'];
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 15px;">
                                            <?php 
                                            $image_path = "admin/uploads/" . $item['image']; 
                                            if (!empty($item['image']) && file_exists($image_path)): 
                                            ?>
                                                <img src="<?= $image_path ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                                            <?php else: ?>
                                                <img src="default.jpg" alt="No Image" style="width:80px;">
                                            <?php endif; ?>
                                            <span><?= htmlspecialchars($item['title']) ?></span>
                                        </div>
                                    </td>
                                    <td>Rs.<?= number_format($item['price'], 2) ?></td>
                                    <td>
                                        <input type="number" name="quantity[<?= $book_id ?>]" 
                                               value="<?= $item['quantity'] ?>" min="1" 
                                               max="<?= $max_quantity ?>"
                                               class="quantity-input"
                                               onchange="validateQuantity(this, <?= $max_quantity ?>)">
                                        <small style="display: block; color: #666;">Max: <?= $max_quantity ?></small>
                                    </td>
                                    <td>Rs.<?= number_format($subtotal, 2) ?></td>
                                    <td>
                                        <a href="cart.php?remove_from_cart=<?= $book_id ?>" 
                                           class="btn btn-remove">Remove</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <div class="cart-total">
                        Total: Rs.<?= number_format($total, 2) ?>
                    </div>
                    
                    <div class="cart-actions">
                        <a href="homepage.php" class="btn btn-cart">Continue Shopping</a>
                        <div>
                            <button type="submit" name="update_cart" class="btn btn-update">Update Cart</button>
                            <a href="checkout.php" class="btn btn-checkout">Proceed to Checkout</a>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <div style="text-align: center; padding: 40px;">
                    <p style="font-size: 18px; color: #6c757d;">Your cart is empty</p>
                    <a href="homepage.php" class="btn btn-continue" style="margin-top: 20px;">Browse Books</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function validateQuantity(input, max) {
            if (input.value > max) {
                alert(`Maximum available quantity is ${max}`);
                input.value = max;
            }
            if (input.value < 1) {
                input.value = 1;
            }
        }
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>
<?php include 'footer.php'; ?>