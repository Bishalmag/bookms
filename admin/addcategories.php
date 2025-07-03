<?php 
require_once 'admin_auth.php';
include('header.php');
include('sidebar.php');

if (isset($_POST['add_category'])) {
    $genre = htmlspecialchars(trim($_POST['genre']));
    $description = htmlspecialchars(trim($_POST['description']));

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "book_management_system";

    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection Error: " . $conn->connect_error);
    }

    $sql = "INSERT INTO categories (genre, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $genre, $description);

    if ($stmt->execute()) {
        echo "<script>alert('Category added successfully'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <style>
    .add-category-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f7fa;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }
    .add-category-form {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
    }
    .add-category-form h2 {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 25px;
        font-size: 24px;
    }
    .add-category-form label {
        display: block;
        font-weight: 600;
        color: #34495e;
        margin-bottom: 8px;
        margin-top: 15px;
    }
    .add-category-form input[type="text"] {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: all 0.3s;
        box-sizing: border-box;
    }
    .add-category-form input[type="text"]:focus {
        border-color: #4a6fa5;
        outline: none;
        box-shadow: 0 0 0 2px rgba(74, 111, 165, 0.2);
    }
    .add-category-form input[type="submit"] {
        background-color: #4a6fa5;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        margin-top: 20px;
        width: 100%;
        transition: background-color 0.3s;
    }
    .add-category-form input[type="submit"]:hover {
        background-color: #3a5a80;
    }
    @media (max-width: 768px) {
        .add-category-form {
            padding: 20px;
        }
    }
    </style>
</head>
<body>
    <div class="add-category-container">
        <div class="add-category-form">
            <form method="POST" action="addcategories.php">
                <h2>Add a New Category</h2>

                <label for="genre">Genre</label>
                <input type="text" name="genre" required>

                <label for="description">Description</label>
                <input type="text" name="description" required>

                <input type="submit" name="add_category" value="Add Category">
            </form>
        </div>
    </div>
</body>
</html>
<?php include('footer.php'); ?>
