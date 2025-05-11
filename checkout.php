<?php
session_start();
include('header.php');

// Database configuration
$host = 'localhost';
$dbname = 'book_management_system';
$username = 'root';  // Default XAMPP username
$password = '';      // Default XAMPP password

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to place an order.";
    header("Location: login.php");
    exit();
}

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Your cart is empty.";
    header("Location: cart.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = trim($_POST['address']);
    if (empty($shipping_address)) {
        $_SESSION['error'] = "Please enter a shipping address.";
        header("Location: checkout.php");
        exit();
    }

    $total_amount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    try {
        // Connect to DB
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, shipping_address) 
                                VALUES (:user_id, :total_amount, :shipping_address)");
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':total_amount' => $total_amount,
            ':shipping_address' => $shipping_address
        ]);
        $order_id = $conn->lastInsertId();

        // Insert order items and update inventory
        foreach ($_SESSION['cart'] as $book_id => $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, book_id, quantity, price)
                                    VALUES (:order_id, :book_id, :quantity, :price)");
            $stmt->execute([
                ':order_id' => $order_id,
                ':book_id' => $book_id,
                ':quantity' => $item['quantity'],
                ':price' => $item['price']
            ]);

            $stmt = $conn->prepare("UPDATE books SET copies_available = copies_available - :qty 
                                    WHERE book_id = :book_id");
            $stmt->execute([
                ':qty' => $item['quantity'],
                ':book_id' => $book_id
            ]);
        }

        // Clear cart and redirect
         
         $_SESSION['order_success'] = true;
        header("Location: order_success.php?order_id=$order_id");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Error processing your order: " . $e->getMessage();
        header("Location: checkout.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 20px;
}

.checkout-container {
    width: 50%;
    margin: 40px auto;
    background-color: #fff;
    padding: 30px 25px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-left: 8px solid #4a6fa5;
}

h2, h3 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 25px;
    font-size: 26px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
}

textarea {
    width: 100%;
    padding: 12px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 16px;
    color: #333;
    resize: vertical;
    min-height: 100px;
}

.cart-summary {
    margin-top: 30px;
    border-top: 1px solid #ddd;
    padding-top: 20px;
}

.cart-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    color: #444;
}

.total {
    text-align: right;
    font-size: 18px;
    font-weight: bold;
    margin-top: 20px;
    padding-top: 10px;
    border-top: 2px solid #ddd;
    color: #222;
}

.btn-submit {
    background-color: #4a6fa5;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
    margin-top: 20px;
    transition: background-color 0.3s, transform 0.2s;
}

.btn-submit:hover {
    background-color: #3a5a80;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.error-message {
    color: #721c24;
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}

    </style>
</head>
<body>
<div class="checkout-container">
    <h2>Checkout</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="checkout.php">
        <div class="form-group">
            <label for="address">Shipping Address:</label>
            <textarea name="address" id="address" rows="4" required></textarea>
        </div>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $item): 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <div class="cart-item">
                    <span><?= htmlspecialchars($item['title']) ?> (x<?= $item['quantity'] ?>)</span>
                    <span>Rs.<?= number_format($subtotal, 2) ?></span>
                </div>
            <?php endforeach; ?>
            <div class="total">Total: Rs.<?= number_format($total, 2) ?></div>
        </div>

        <button type="submit" class="btn-submit">Place Order</button>
    </form>
</div>
</body>
</html>
<?php include 'footer.php'; ?> 
