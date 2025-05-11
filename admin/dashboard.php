<?php
require_once 'admin_auth.php';
include('header.php');
include('sidebar.php');

// Database connection
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "book_management_system";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get all statistics
$total_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM books"))['total'];
$total_authors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM authors"))['total'];
$total_categories = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM categories"))['total'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
//$available_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(copies_available) as total FROM books"))['total'];
$out_of_stock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM books WHERE copies_available = 0"))['total'];

// Get recent orders
$recent_orders = mysqli_query($conn, "SELECT o.order_id, u.username, o.total_amount, o.date 
                                     FROM orders o JOIN users u ON o.user_id = u.id 
                                     ORDER BY o.date DESC LIMIT 5");

// Get popular books
$popular_books = mysqli_query($conn, "SELECT b.title, COUNT(oi.order_id) as orders 
                                     FROM order_items oi JOIN books b ON oi.book_id = b.book_id 
                                     GROUP BY b.title ORDER BY orders DESC LIMIT 5");

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #3a5a80;
            --danger-color: #e74c3c;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
            --text-color: #495057;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            color: var(--text-color);
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
            transition: margin-left 0.3s;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            color: var(--dark-gray);
            font-weight: 600;
            margin: 0;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 10px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .card-icon {
            font-size: 40px;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .books-available .card-icon { color: var(--success-color); }
        .books-out .card-icon { color: var(--danger-color); }
        .total-orders .card-icon { color: var(--warning-color); }
        .total-users .card-icon { color: var(--info-color); }

        .card-footer {
            font-size: 12px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }

        .card-footer i {
            margin-left: 5px;
        }

        .dashboard-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 1200px) {
            .dashboard-section {
                grid-template-columns: 1fr;
            }
        }

        .data-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-gray);
            margin: 0 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .data-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .data-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }

        .data-item:last-child {
            border-bottom: none;
        }

        .item-title {
            font-weight: 500;
        }

        .item-value {
            font-weight: 600;
        }

        .view-all {
            text-align: right;
            margin-top: 15px;
        }

        .view-all a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
        }

        .view-all a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .stats-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="main-content">
    <div class="dashboard-header">
        <h1 class="page-title">Dashboard</h1>
        <div class="breadcrumb">Admin</div>
    </div>

    <div class="stats-container">
        <div class="stat-card">
            <div class="card-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="card-title">Total Books</div>
            <div class="card-value"><?= $total_books ?></div>
            <div class="card-footer">
                <a href="allbook.php">View all books</a>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
<!-- 
        <div class="stat-card books-available">
            <div class="card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-title">Books Available</div>
            <div class="card-value"><?= $available_books ?></div>
            <div class="card-footer">
                <a href="books.php?filter=available">View available</a>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div> -->

        <div class="stat-card books-out">
            <div class="card-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="card-title">Out of Stock</div>
            <div class="card-value"><?= $out_of_stock ?></div>
            <div class="card-footer">
                <a href="out_of_stock.php?filter=outofstock">View out of stock</a>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="card-title">Authors</div>
            <div class="card-value"><?= $total_authors ?></div>
            <div class="card-footer">
                <a href="allauthors.php">View all authors</a>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>

        <div class="stat-card total-orders">
            <div class="card-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="card-title">Total Orders</div>
            <div class="card-value"><?= $total_orders ?></div>
            <div class="card-footer">
                <a href="allorder.php">View all orders</a>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>

        <div class="stat-card total-users">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-title">Registered Users</div>
            <div class="card-value"><?= $total_users ?></div>
            <div class="card-footer">
                <a href="allusers.php">View all users</a>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </div>

    <div class="dashboard-section">
        <div class="data-card">
            <h3 class="section-title">Recent Orders</h3>
            <ul class="data-list">
                <?php while($order = mysqli_fetch_assoc($recent_orders)): ?>
                <li class="data-item">
                    <span class="item-title">Order #<?= $order['order_id'] ?> by <?= $order['username'] ?></span>
                    <span class="item-value">Rs.<?= number_format($order['total_amount'], 2) ?></span>
                </li>
                <?php endwhile; ?>
            </ul>
            <div class="view-all">
                <a href="allorder.php">View all orders <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="data-card">
            <h3 class="section-title">Popular Books</h3>
            <ul class="data-list">
                <?php while($book = mysqli_fetch_assoc($popular_books)): ?>
                <li class="data-item">
                    <span class="item-title"><?= $book['title'] ?></span>
                    <span class="item-value"><?= $book['orders'] ?> orders</span>
                </li>
                <?php endwhile; ?>
            </ul>
            <!-- <div class="view-all">
                <a href="allbooks.php?sort=popular">View all books <i class="fas fa-arrow-right"></i></a>
            </div> -->
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>