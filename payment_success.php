<?php
session_start();
include('header.php');

if (!isset($_GET['order_id']) || !isset($_GET['reference_id'])) {
    header("Location: homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .success-container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }
        
        .success-icon {
            color: #4CAF50;
            font-size: 72px;
            margin-bottom: 20px;
        }
        
        .btn-continue {
            display: inline-block;
            background-color: #4a6fa5;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: bold;
        }
        
        .order-details {
            margin-top: 30px;
            text-align: left;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h2>Payment Successful!</h2>
        <p>Thank you for your order. Your payment has been processed successfully.</p>
        
        <div class="order-details">
            <h3>Order Details</h3>
            <p><strong>Order ID:</strong> <?= htmlspecialchars($_GET['order_id']) ?></p>
            <p><strong>Reference ID:</strong> <?= htmlspecialchars($_GET['reference_id']) ?></p>
        </div>
        
        <a href="order_history.php" class="btn-continue">View Order History</a>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>