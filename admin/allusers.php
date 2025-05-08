<?php
require_once 'admin_auth.php';
include('header.php');
include('sidebar.php');
// Step 1: Connect to the database
$conn = new mysqli("localhost", "root", "", "book_management_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Fetch users
$sql = "SELECT username, email FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Users</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .content {
    margin-left: 20%; /* Adjust this according to your sidebar width */
    width: 80%; /* Ensure the content takes the remaining width */
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
            margin-left: 20%;
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

        .no-users {
            text-align: center;
            color: #777;
            font-style: italic;
            padding: 20px;
        }

        @media (max-width: 768px) {
    .content {
        margin-left: 0; /* Reset margin for small screens */
        width: 100%; /* Full width on mobile */
    }
        }
    </style>
</head>
<body>
    <h2>All Users</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
        </tr>

        <?php
        // Step 3: Display users
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["username"]) . "</td>
                        <td>" . htmlspecialchars($row["email"]) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No users found</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</body>
</html>
<?php include('footer.php'); ?>
