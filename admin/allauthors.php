<?php
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
    <title>All Authors</title>
    <style>
    /* Global styles for content */
.content {
    margin-left: 22%;
    padding: 30px;
    background-color: #f5f7fa;
    min-height: 100vh;
}

/* Center title style */
.center-title {
    text-align: center;
    margin-bottom: 30px;
}

.center-title h1 {
    color: #2c3e50;
    font-size: 28px;
    margin-bottom: 10px;
}

/* Author Table Style */
.author-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background-color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px; /* Space below the table */
}

/* Table Header Style */
.author-table th {
    background-color: #4a6fa5;
    color: white;
    padding: 15px;
    text-align: center;
    font-weight: 600;
    position: sticky;
    top: 0;
    z-index: 1; /* Keeps header on top while scrolling */
}

/* Table Cell Style */
.author-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e0e0e0;
    color: #555;
    text-align: center;
}

/* Remove border from last row */
.author-table tr:last-child td {
    border-bottom: none;
}

/* Hover Effect */
.author-table tr:hover {
    background-color: #f8f9fa;
}

/* Action Button Styles */
.action-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn-edit, .btn-delete {
    padding: 8px 15px;
    border-radius: 4px;
    font-weight: 500;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-block;
}

/* Edit Button Style */
.btn-edit {
    background-color: #4a6fa5;
    color: white;
}

.btn-edit:hover {
    background-color: #3a5a80;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Delete Button Style */
.btn-delete {
    background-color: #e74c3c;
    color: white;
}

.btn-delete:hover {
    background-color: #c0392b;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Mobile responsiveness */
@media (max-width: 1200px) {
    .content {
        margin-left: 0;
        padding: 20px;
    }
}

@media (max-width: 768px) {
    /* Table responsiveness for smaller screens */
    .author-table {
        font-size: 12px; /* Smaller text size */
    }

    .content {
        padding: 15px;
    }

    .action-buttons {
        flex-direction: column; /* Stack buttons vertically on mobile */
    }

    .btn-edit, .btn-delete {
        width: 100%; /* Make buttons take full width */
        margin-bottom: 10px;
    }
}
</style>

</head>
<body>

<div class="content">
    <div class="center-title">
        <h1>All Authors</h1>
    </div>

    <table class="author-table">
        <thead>
            <tr>
                <th>Author ID</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM authors";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['author_id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td class='action-buttons'>
                    <a href='editauthor.php?author_id=" . $row['author_id'] . "' class='btn-edit'>Edit</a>
                    <a href='deleteauthor.php?author_id=" . $row['author_id'] . "' class='btn-delete' onclick=\"return confirm('Are you sure you want to delete this author?');\">Delete</a>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No authors found</td></tr>";
        }

        mysqli_close($conn);
        ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
</body>
</html>