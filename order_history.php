<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'book_management_system';
$username = 'root';  // Default XAMPP username
$password = '';      // Default XAMPP password

// Create the database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user's orders
$stmt = $conn->prepare("SELECT o.order_id, o.total_amount, 
                        COUNT(oi.item_id) as item_count 
                        FROM orders o
                        JOIN order_items oi ON o.order_id = oi.order_id
                        WHERE o.user_id = ?
                        GROUP BY o.order_id");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History | Book Management System</title>
    <style>
        .content {
            margin-left: 22%;
            padding: 30px;
            background-color: #f5f7fa;
            min-height: 100vh;
        }
        
        .order-history-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .order-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .order-card:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .order-id {
            font-weight: 600;
            color: #4a6fa5;
        }
        
        .order-date {
            color: #6c757d;
        }
        
        .order-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .order-items {
            color: #495057;
        }
        
        .order-total {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .view-details-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #4a6fa5;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .view-details-btn:hover {
            background-color: #3a5a80;
        }
        
        .empty-orders {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        .empty-orders a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4a6fa5;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        .home-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #4a6fa5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            text-align: center;
        }

        .home-button:hover {
            background-color: #3b5f8c;
        }
        
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }
            
            .order-header, .order-details {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="order-history-container">
            <h1>Your Order History</h1>
            
            <?php if (count($orders) > 0): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-id">Order #<?= $order['order_id'] ?></span>
                        </div>
                        <div class="order-details">
                            <div class="order-items">
                                <?= $order['item_count'] ?> item<?= $order['item_count'] > 1 ? 's' : '' ?>
                            </div>
                            <div class="order-total">Rs <?= number_format($order['total_amount'], 2) ?></div>
                        </div>
                        <div style="margin-top: 15px; text-align: right;">
                            <a href="order_details.php?order_id=<?= $order['order_id'] ?>" class="view-details-btn">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-orders">
                    <p>You haven't placed any orders yet.</p>
                    <a href="homepage.php">Browse Books</a>
                </div>
            <?php endif; ?>
            
            <!-- Added button to go to the homepage -->
            <a href="homepage.php" class="home-button">Go to Homepage</a>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
