<?php

include('header.php');
include('sidebar.php');
include '../db.php'; // your PDO connection


$query = "
    SELECT 
        users.username,
        books.title AS book_name,
        order_items.quantity,
        orders.total_amount,
        orders.shipping_address,
        orders.date
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
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Orders</title>
    
    <style>
    
    :root {
        --primary-color: #4a6fa5;
        --secondary-color: #3a5a80;
        --success-color: #28a745;
        --danger-color: #e74c3c;
        --warning-color: #ffc107;
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
        padding: 30px;
        color: var(--text-color);
    }

    .orders-container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 30px;
    }

    .page-header {
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: center;     /* Center vertically */
    margin-bottom: 30px;
    text-align: center;      /* Ensure text inside is centered */
}


    h1 {
        color: var(--dark-gray);
        font-size: 28px;
        margin: 0;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0 auto;
        margin-left: 11%;
    }

    thead {
        background-color: var(--primary-color);
        color: white;
    }

    th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        position: sticky;
        top: 0;
    }

    td {
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e0;
        color: var(--text-color);
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

    .status-completed {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .status-pending {
        background-color: rgba(255, 193, 7, 0.1);
        color: var(--warning-color);
    }

    .status-cancelled {
        background-color: rgba(231, 76, 60, 0.1);
        color: var(--danger-color);
    }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .view-btn {
        background-color: rgba(74, 111, 165, 0.1);
        color: var(--primary-color);
    }

    .view-btn:hover {
        background-color: rgba(74, 111, 165, 0.2);
    }

    .edit-btn {
        background-color: rgba(23, 162, 184, 0.1);
        color: var(--info-color);
    }

    .edit-btn:hover {
        background-color: rgba(23, 162, 184, 0.2);
    }

    .cancel-btn {
        background-color: rgba(231, 76, 60, 0.1);
        color: var(--danger-color);
    }

    .cancel-btn:hover {
        background-color: rgba(231, 76, 60, 0.2);
    }

    .no-books {
        text-align: center;
        padding: 30px;
        color: #6c757d;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .orders-container {
            padding: 15px;
        }
        
        table {
            display: block;
            overflow-x: auto;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

</head>
<body>
    <div class="orders-container">
        <div class="page-header">
            <h1>All Order</h1>
            
        </div>
<table>
<thead>
<tr>
    <th>Username</th>
    <th>Book Name</th>
    <th>Quantity</th>
    <th>Total Amount</th>
    <th>Shipping Address</th>
    <th>Date</th>
</tr>
</thead>

<tbody>
<?php if (count($results) > 0): ?>
    <?php foreach ($results as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['book_name']) ?></td>
            <td><?= htmlspecialchars($row['quantity']) ?></td>
            <td><?= htmlspecialchars($row['total_amount']) ?></td>
            <td><?= htmlspecialchars($row['shipping_address']) ?></td>
            <td><?= htmlspecialchars($row['date']) ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="6" class="no-books">No orders found.</td></tr>
<?php endif; ?>
</tbody>

</table>
<?php include('footer.php'); ?>

