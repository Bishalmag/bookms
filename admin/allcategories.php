<?php
session_start();
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
        .content {
            margin-left: 22%;
            padding: 20px;
        }
        .center-title {
            text-align: center;
            margin: 30px 0;
        }
        .category-table {
            width: 100%;
            border-collapse: collapse;
        }
        .category-table th, .category-table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }
        .category-table th {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>

<div class="content">
    <div class="center-title">
        <h1>All Categories</h1>
    </div>

    <table class="category-table">
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
                echo "<td>" . $row['type'] . "</td>";
                echo "<td><a href='#'>Edit</a> | <a href='#'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No categories found</td></tr>";
        }

        mysqli_close($conn);
        ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
</body>
</html>
