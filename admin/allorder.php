<?php
// Start output buffering at the very top
ob_start();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'admin_auth.php';
include('../db.php');
include('header.php');
include('sidebar.php');

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    try {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->execute([$new_status, $order_id]);
        $_SESSION['success'] = "Order status updated successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating status: " . $e->getMessage();
    }
    
    // Clear buffer and redirect
    ob_end_clean();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Fetch all orders with status
$query = "
    SELECT 
        orders.order_id,
        users.username,
        books.title AS book_name,
        order_items.quantity,
        orders.total_amount,
        orders.shipping_address,
        orders.date,
        orders.status
    FROM 
        orders
    JOIN 
        order_items ON orders.order_id = order_items.order_id
    JOIN 
        users ON orders.user_id = users.id
    JOIN 
        books ON order_items.book_id = books.book_id
    ORDER BY 
        orders.date DESC
";

$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Flush the output buffer before HTML
ob_end_flush();
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Orders - Admin</title>
   <style>
    :root {
        --primary-color: #4a6fa5;
        --secondary-color: #3a5a80;
        --success-color: #28a745;
        --danger-color: #e74c3c;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --light-gray: #f8f9fa;
        --dark-gray: #343a40;
        --text-color: #495057;
        --border-radius: 8px;
        --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f7fa;
        margin: 0;
        padding: 0;
        margin-left: 150px;
        width: calc(100% - 150px);
        color: var(--text-color);
        box-sizing: border-box;
    }

    .orders-container {
        padding: 20px;
        margin-top: 70px;
        margin-bottom: 60px;
        margin-left: 60px;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .page-header {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 30px;
        text-align: center;
    }

    h1 {
        color: var(--dark-gray);
        font-size: 24px;
        margin: 0;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
    }

    thead {
        background-color: var(--primary-color);
        color: white;
        position: sticky;
        top: 70px;
        z-index: 100;
    }

    th {
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
    }

    td {
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e0;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover td {
        background-color: rgba(74, 111, 165, 0.05);
    }

    .amount-cell {
        font-weight: 600;
        color: var(--success-color);
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .status-pending {
        background-color: rgba(255, 193, 7, 0.1);
        color: var(--warning-color);
    }

    .status-processing {
        background-color: rgba(23, 162, 184, 0.1);
        color: var(--info-color);
    }

    .status-shipped {
        background-color: rgba(0, 123, 255, 0.1);
        color: #007bff;
    }

    .status-delivered {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .status-cancelled {
        background-color: rgba(231, 76, 60, 0.1);
        color: var(--danger-color);
    }

    .status-form {
        display: flex;
        gap: 8px;
    }

    .status-select {
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ddd;
        font-size: 13px;
    }

    .update-btn {
        padding: 5px 10px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        transition: background-color 0.3s;
    }

    .update-btn:hover {
        background-color: var(--secondary-color);
    }

    .no-orders {
        text-align: center;
        padding: 30px;
        color: #6c757d;
        font-style: italic;
    }

    .alert {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: var(--border-radius);
    }

    .alert-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
        border-left: 4px solid var(--success-color);
    }

    .alert-error {
        background-color: rgba(231, 76, 60, 0.1);
        color: var(--danger-color);
        border-left: 4px solid var(--danger-color);
    }

    @media (max-width: 768px) {
        body {
            margin-left: 0;
            width: 100%;
        }

        .orders-container {
            padding: 15px;
            margin-top: 60px;
        }

        thead {
            top: 60px;
        }

        table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
</head>
<body>
    <div class="orders-container">
        <div class="page-header">
            <h1>All Orders - Admin Panel</h1>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Book Name</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Shipping Address</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($results) > 0): ?>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['order_id']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['book_name']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td class="amount-cell">Rs. <?= number_format($row['total_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($row['shipping_address']) ?></td>
                            <td><?= date('M d, Y h:i A', strtotime($row['date'])) ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($row['status']) ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" class="status-form">
                                    <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                    <select name="status" class="status-select">
                                        <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="processing" <?= $row['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                        <option value="shipped" <?= $row['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                        <option value="delivered" <?= $row['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                        <option value="cancelled" <?= $row['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_status" class="update-btn">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="no-orders">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include('footer.php'); ?>
</body>
</html>