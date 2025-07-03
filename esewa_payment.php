<?php
session_start();
include('header.php');

// Check if order data exists
if (!isset($_SESSION['order_data'])) {
    header("Location: checkout.php");
    exit();
}

// Process payment on form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $host = 'localhost';
        $dbname = 'book_management_system';
        $username = 'root';
        $password = '';
        
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Update order status
        $stmt = $conn->prepare("UPDATE orders SET 
                               payment_status = 'completed', 
                               payment_method = 'eSewa'
                               WHERE order_id = :order_id");
        $stmt->execute([':order_id' => $_SESSION['order_data']['order_id']]);
        
        // Record transaction
        $reference_id = 'MOCK-' . uniqid();
        $stmt = $conn->prepare("INSERT INTO transactions 
                               (order_id, payment_method, amount, transaction_status, reference_id) 
                               VALUES (:order_id, 'eSewa', :amount, 'completed', :reference_id)");
        $stmt->execute([
            ':order_id' => $_SESSION['order_data']['order_id'],
            ':amount' => $_SESSION['order_data']['total_amount'],
            ':reference_id' => $reference_id
        ]);
        
        // Clear cart and order data
        unset($_SESSION['cart']);
        $order_id = $_SESSION['order_data']['order_id'];
        unset($_SESSION['order_data']);
        
        // Redirect to success page
        header("Location: payment_success.php?order_id=$order_id&reference_id=$reference_id");
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Payment processing error: " . $e->getMessage();
        header("Location: checkout.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mock Payment Gateway</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .payment-container {
            width: 100%;
            max-width: 500px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .payment-header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .payment-body {
            padding: 30px;
        }
        
        .payment-details {
            margin-bottom: 30px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .detail-label {
            color: #666;
            font-weight: 500;
        }
        
        .detail-value {
            font-weight: bold;
        }
        
        .payment-form {
            margin-top: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .btn-pay {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-pay:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h2>Mock Payment Gateway</h2>
            <p>This is a simulation for testing purposes</p>
        </div>
        
        <div class="payment-body">
            <div class="payment-details">
                <h3>Order Summary</h3>
                <div class="detail-row">
                    <span class="detail-label">Order ID:</span>
                    <span class="detail-value"><?= htmlspecialchars($_SESSION['order_data']['order_id']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value">Rs. <?= number_format($_SESSION['order_data']['total_amount'], 2) ?></span>
                </div>
            </div>
            
            <form method="POST" class="payment-form">
                <div class="form-group">
                    <label for="card_number">Test Card Number</label>
                    <input type="text" id="card_number" name="card_number" value="4242 4242 4242 4242" readonly>
                </div>
                
                <div class="form-group">
                    <label for="expiry">Test Expiry</label>
                    <input type="text" id="expiry" name="expiry" value="12/34" readonly>
                </div>
                
                <div class="form-group">
                    <label for="cvc">Test CVC</label>
                    <input type="text" id="cvc" name="cvc" value="123" readonly>
                </div>
                
                <button type="submit" class="btn-pay">Complete Mock Payment</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>