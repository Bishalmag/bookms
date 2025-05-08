<?php
session_start();
include('header.php');

// Database configuration
$host = 'localhost';
$dbname = 'book_management_system';
$username = 'root';  // Default XAMPP username
$password = '';      // Default XAMPP password

// Database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    header('Location: order_history.php');
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    $_SESSION['error'] = "Order not found";
    header('Location: order_history.php');
    exit();
}

// Fetch order items
$stmt = $conn->prepare("SELECT oi.*, b.title, b.image 
                        FROM order_items oi
                        JOIN books b ON oi.book_id = b.book_id
                        WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details | Book Management System</title>
    <style>
        .content {
            margin-left: 22%;
            padding: 30px;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        .order-details-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .order-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .meta-group {
            flex: 1;
            min-width: 200px;
        }

        .meta-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }

        .meta-value {
            color: #212529;
            font-size: 1.1rem;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background-color: #4a6fa5;
            color: white;
            padding: 12px 15px;
            text-align: left;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .item-image {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }

        .item-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .order-total {
            text-align: right;
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 20px;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #4a6fa5;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }

            .items-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="order-details-container">
            <h1>Order Details #<?= $order['order_id'] ?></h1>
                <div class="meta-group">
                    <div class="meta-label">Shipping Address</div>
                    <div class="meta-value"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></div>
                </div>
            </div>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <?php if ($item['image']): ?>
                                        <img src="admin/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="item-image">
                                    <?php endif; ?>
                                    <span class="item-title"><?= htmlspecialchars($item['title']) ?></span>
                                </div>
                            </td>
                            <td>Rs <?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>Rs <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="order-total">
                Total: Rs <?= number_format($order['total_amount'], 2) ?>
            </div>
            
            <a href="order_history.php" class="back-link">← Back to Order History</a>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
