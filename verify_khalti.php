<?php
session_start();
include('header.php');
include('db.php');
// Check if cart exists and has items
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Your cart is empty.";
    header('Location: cart.php');
    exit();
}

// Handle payment success
if (isset($_GET['payment_success'])) {
    $_SESSION['message'] = "Payment successful! Your order has been placed.";
    unset($_SESSION['cart']);
    header('Location: order_history.php');
    exit();
    unset($_SESSION['cart']);
}

// Fetch cart items from database
$book_ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($book_ids), '?'));
$stmt = $conn->prepare("SELECT book_id, title, price, copies_available FROM books WHERE book_id IN ($placeholders)");
$stmt->execute($book_ids);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verify book availability
foreach ($books as $book) {
    $cart_item = $_SESSION['cart'][$book['book_id']];
    if ($cart_item['quantity'] > $book['copies_available']) {
        $_SESSION['error'] = "Only {$book['copies_available']} copies available for '{$book['title']}'";
        header('Location: cart.php');
        exit();
    }
}

// Process order
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['khalti_token'])) {
    $shipping_address = $_POST['shipping_address'];
    $payment_method = $_POST['payment_method'];
    
    try {
        $conn->beginTransaction();
        
        // Create order header
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            array_reduce($_SESSION['cart'], fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0),
            $shipping_address,
            $payment_method
        ]);
        $order_id = $conn->lastInsertId();
        
        // Create order items and update inventory
        foreach ($_SESSION['cart'] as $book_id => $item) {
            // Add order item
            $conn->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) 
                           VALUES (?, ?, ?, ?)")
                 ->execute([$order_id, $book_id, $item['quantity'], $item['price']]);
            
            // Update inventory
            $conn->prepare("UPDATE books SET copies_available = copies_available - ? 
                           WHERE book_id = ?")
                 ->execute([$item['quantity'], $book_id]);
        }
        
        $conn->commit();
        unset($_SESSION['cart']);
        $_SESSION['message'] = "Order placed successfully!";
        header('Location: order_history.php');
        exit();
    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Error processing order: " . $e->getMessage();
        header("Location: checkout.php");
        exit();
    }
}

// Calculate total
$total = array_reduce($_SESSION['cart'], fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Book Management System</title>
    <style>
        /* Main content area */
        .content {
            margin-left: 22%;
            padding: 30px;
            background-color: #f5f7fa;
            min-height: 100vh;
        }
        
        /* Checkout container */
        .checkout-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Page title */
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        
        /* Two-column layout */
        .checkout-columns {
            display: flex;
            gap: 30px;
        }
        
        .checkout-form {
            flex: 2;
        }
        
        .order-summary {
            flex: 1;
        }
        
        /* Form styling */
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #495057;
            font-weight: 600;
            font-size: 14px;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.15s ease-in-out;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: #4a6fa5;
            outline: 0;
            box-shadow: 0 0 0 2px rgba(74, 111, 165, 0.25);
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        /* Order summary */
        .order-item {
            display: flex;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-item-image {
            width: 80px;
            height: 100px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 4px;
        }
        
        .order-item-details {
            flex: 1;
        }
        
        .order-item-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .order-item-price {
            color: #4a6fa5;
            margin-bottom: 10px;
        }
        
        .order-item-quantity {
            color: #6c757d;
        }
        
        /* Total section */
        .order-total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: right;
            font-weight: 600;
            font-size: 18px;
        }
        
        /* Payment buttons */
        .payment-btn {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            margin-top: 10px;
        }
        
        .btn-primary {
            background-color: #4a6fa5;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a5a80;
        }
        
        .btn-khalti {
            background-color: #5C2D91;
            color: white;
        }
        
        .btn-khalti:hover {
            background-color: #4a2475;
        }
        
        /* Responsive design */
        @media (max-width: 1200px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }
            
            .checkout-columns {
                flex-direction: column;
            }
        }
        
        @media (max-width: 768px) {
            .checkout-container {
                padding: 20px;
            }
            
            .order-item {
                flex-direction: column;
            }
            
            .order-item-image {
                margin-right: 0;
                margin-bottom: 10px;
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="checkout-container">
            <h1>Payment</h1>
            
            <div class="checkout-columns">
                <div class="checkout-form">
                    <h2>Payment Method</h2>
                    <form method="POST" id="checkoutForm">
                      
                        
                        <div class="form-group">
                        <select id="payment_method" name="payment_method" required>
    <option value="khalti" selected>Khalti</option>
</select>

                        </div>
                        
                        <button type="submit" class="payment-btn btn-primary">Place Order</button>
                        <button type="button" id="khaltiBtn" class="payment-btn btn-khalti" style="display:none;">Pay with Khalti</button>
                    </form>
                </div>
                
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    
                    <?php foreach ($_SESSION['cart'] as $book_id => $item): ?>
                        <div class="order-item">
                            <?php if (!empty($item['image'])): ?>
                                <img src="admin/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="order-item-image">
                            <?php endif; ?>
                            <div class="order-item-details">
                                <div class="order-item-title"><?= htmlspecialchars($item['title']) ?></div>
                                <div class="order-item-price">Rs.<?= number_format($item['price'], 2) ?></div>
                                <div class="order-item-quantity">Quantity: <?= $item['quantity'] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="order-total">
                        Total: Rs. <?= number_format($total, 2) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    <script>
        // Khalti payment configuration
        var config = {
            "publicKey": "test_public_key_1234567890abcdef1234567890abcdef",
            "productIdentity": "book_order_<?= $_SESSION['user_id'] ?>",
            "productName": "Book Purchase",
            "productUrl": window.location.href,
            "eventHandler": {
                onSuccess(payload) {
                    // Verify payment with server
                    verifyKhaltiPayment(payload);
                },
                onError(error) {
                    console.error(error);
                    alert('Payment failed: ' + error.message);
                },
                onClose() {
                    console.log('Payment widget closed');
                }
            }
        };
        
        var checkout = new KhaltiCheckout(config);
        
        // Toggle Khalti button based on payment method
        document.getElementById('payment_method').addEventListener('change', function() {
            const khaltiBtn = document.getElementById('khaltiBtn');
            khaltiBtn.style.display = this.value === 'khalti' ? 'block' : 'none';
        });
        
        // Handle form submission
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            if (document.getElementById('payment_method').value === 'khalti') {
                e.preventDefault();
                checkout.show({amount: <?= $total * 100 ?>}); // Amount in paisa
            }
        });
        
        // Handle Khalti button click
        document.getElementById('khaltiBtn').addEventListener('click', function() {
            checkout.show({amount: <?= $total * 100 ?>});
        });
        
        // Verify Khalti payment with server
        function verifyKhaltiPayment(payload) {
            const formData = new FormData();
            formData.append('khalti_token', payload.token);
            formData.append('khalti_amount', payload.amount);
            formData.append('shipping_address', document.getElementById('shipping_address').value);
            
            fetch('verify_khalti.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'checkout.php?payment_success=1';
                } else {
                    alert('Payment verification failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while verifying payment');
            });
        }
    </script>
</body>
</html>
<?php include('footer.php'); ?>