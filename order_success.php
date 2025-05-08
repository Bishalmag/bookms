<?php
session_start();
include('header.php');

// Redirect if coming directly without a successful order
if (!isset($_SESSION['order_success'])) {
    header('Location: homepage.php');
    exit();
}

// Clear the success flag to prevent refresh issues
unset($_SESSION['order_success']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success | Book Management System</title>
    <style>
        .content {
            margin-left: 22%;
            padding: 30px;
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .success-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        p {
            color: #495057;
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: #4a6fa5;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a5a80;
        }
        
        .btn-outline {
            border: 2px solid #4a6fa5;
            color: #4a6fa5;
        }
        
        .btn-outline:hover {
            background-color: #f0f4f9;
        }
        
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }
            
            .btn-group {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="success-container">
            <div class="success-icon">✓</div>
            <h1>Order Placed Successfully!</h1>
            <p>Thank you for your purchase. Your order has been received and is being processed. You will receive a confirmation email shortly with your order details.</p>
            
            <div class="btn-group">
                <a href="order_history.php" class="btn btn-primary">View Orders</a>
                <a href="homepage.php" class="btn btn-outline">Continue Shopping</a>
                <a href="verify_khalti.php" class="btn btn-outline">Pay Now </a>
            </div>
            
        </div>
    </div>
   
    
</body>
</html>
<?php include('footer.php'); ?>