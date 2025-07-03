<?php
require_once 'admin_auth.php';
include('header.php');
include('sidebar.php');

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['add_book'])) {
    $title = htmlspecialchars(trim($_POST['title']));
    $isbn = htmlspecialchars(trim($_POST['isbn']));
    $price = floatval($_POST['price']);
    $author_id = intval($_POST['author_id']);
    $category_id = intval($_POST['category_id']);
    $publication_year = intval($_POST['publication_year']);
    $publisher = htmlspecialchars(trim($_POST['publisher']));
    $copies_available = intval($_POST['copies_available']);
    $total_copies = intval($_POST['total_copies']);

    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if ($image && in_array($_FILES['image']['type'], $allowed_types)) {
        move_uploaded_file($tmp_name, $upload_dir . $image);
    } else {
        echo "<script>alert('Invalid image file type. Only JPG, PNG, and GIF are allowed.'); window.history.back();</script>";
        exit;
    }

    $sql = "INSERT INTO books 
            (title, isbn, price, author_id, category_id, publication_year, publisher, copies_available, total_copies, image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssdiissiis",
        $title,
        $isbn,
        $price,
        $author_id,
        $category_id,
        $publication_year,
        $publisher,
        $copies_available,
        $total_copies,
        $image
    );

    if ($stmt->execute()) {
        echo "<script>alert('Book added successfully!'); window.location.href = 'dashboard.php';</script>";
        exit;
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

// Fetch authors and categories for dropdowns
$authors = $conn->query("SELECT author_id, name FROM authors ORDER BY name");
$categories = $conn->query("SELECT category_id, genre FROM categories ORDER BY genre");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Book</title>
    <style>
    .add-book-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f7fa;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }
    .add-book-form {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
    }
    .add-book-form h2 {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 25px;
        font-size: 24px;
    }
    .add-book-form label {
        display: block;
        font-weight: 600;
        color: #34495e;
        margin-bottom: 8px;
        margin-top: 15px;
    }
    .add-book-form input[type="text"],
    .add-book-form input[type="number"],
    .add-book-form input[type="file"],
    .add-book-form select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: all 0.3s;
        box-sizing: border-box;
    }
    .add-book-form input:focus,
    .add-book-form select:focus {
        border-color: #4a6fa5;
        outline: none;
        box-shadow: 0 0 0 2px rgba(74, 111, 165, 0.2);
    }
    .add-book-form input[type="submit"] {
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
    .add-book-form input[type="submit"]:hover {
        background-color: #3a5a80;
    }
    @media (max-width: 768px) {
        .add-book-form {
            padding: 20px;
        }
    }
    </style>
</head>
<body>
    <div class="add-book-container">
        <div class="add-book-form">
            <form method="POST" action="addbook.php" enctype="multipart/form-data">
                <h2>Add a New Book</h2>

                <label for="title">Title:</label>
                <input type="text" name="title" required>

                <label for="isbn">ISBN:</label>
                <input type="text" name="isbn" required>

                <label for="price">Price:</label>
                <input type="number" step="0.01" name="price" required>

                <label for="author_id">Author:</label>
                <select name="author_id" required>
                    <option value="">Select Author</option>
                    <?php while ($row = $authors->fetch_assoc()): ?>
                        <option value="<?= $row['author_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="category_id">Category:</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php while ($row = $categories->fetch_assoc()): ?>
                        <option value="<?= $row['category_id'] ?>"><?= htmlspecialchars($row['genre']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="publication_year">Publication Year:</label>
                <input type="number" name="publication_year" required>

                <label for="publisher">Publisher:</label>
                <input type="text" name="publisher" required>

                <label for="copies_available">Copies Available:</label>
                <input type="number" name="copies_available" required>

                <label for="total_copies">Total Copies:</label>
                <input type="number" name="total_copies" required>

                <label for="image">Book Image:</label>
                <input type="file" name="image" accept="image/*" required>

                <input type="submit" name="add_book" value="Add Book">
            </form>
        </div>
    </div>
</body>
</html>

<?php include('footer.php'); ?>
