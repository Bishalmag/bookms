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
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Categories</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .content {
            margin-left: 22%;
            margin-top: 2%;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 28px;
        }

        table {
            width: 95%;
            margin: 0 auto;
            border-collapse: separate;
            border-spacing: 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
        }

        thead {
            background-color: #4a6fa5;
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
            color: #555;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        a.edit-btn, a.delete-btn {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s ease;
        }

        a.edit-btn {
            background-color: #4a6fa5;
            color: white;
            border: 1px solid #3a5a80;
        }

        a.edit-btn:hover {
            background-color: #3a5a80;
        }

        a.delete-btn {
            background-color: #e74c3c;
            color: white;
            border: 1px solid #c0392b;
        }

        a.delete-btn:hover {
            background-color: #c0392b;
        }

        .no-books {
            text-align: center;
            color: #777;
            font-style: italic;
            padding: 20px;
        }

        @media (max-width: 1200px) {
            table {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="content">
    <h1>All Categories</h1>

    <table>
        <thead>
            <tr>
                <th>Category ID</th>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM categories";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['category_id'] . "</td>";
                echo "<td>" . $row['genre'] . "</td>";
                echo "<td class='action-btns'>
                        <a class='edit-btn' href='editcategory.php?category_id=" . $row['category_id'] . "'>Edit</a>
                        <a class='delete-btn' href='deletecategory.php?category_id=" . $row['category_id'] . "' onclick='return confirm(\"Are you sure you want to delete this category?\");'>Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3' class='no-books'>No categories found</td></tr>";
        }

        mysqli_close($conn);
        ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
</body>
</html>
