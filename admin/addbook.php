<?php
require_once 'admin_auth.php';

// Database connection parameters
$servername   = "localhost";
$username     = "root";
$password     = "";
$dbname       = "book_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['books'])) {
    // Retrieve form data
    $title             = $_POST['title'];
    $isbn              = $_POST['isbn'];
    $price             = $_POST['price'];
    $author_id         = $_POST['author_id'];
    $category_id       = $_POST['category_id'];
    $publication_year  = $_POST['publication_year'];
    $publisher         = $_POST['publisher'];
    $copies_available  = $_POST['copies_available'];
    $total_copies      = $_POST['total_copies'];

    // Image upload handling
    $image     = $_FILES['image']['name'];
    $tmp_name  = $_FILES['image']['tmp_name'];
    $upload_dir = "uploads/";

    // Create uploads directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Move the uploaded file
    if ($image) {
        move_uploaded_file($tmp_name, $upload_dir . $image);
    }

    // SQL insert (omit book_id, it's AUTO_INCREMENT)
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
        echo "<script>
                alert('Book added successfully!');
                window.location.href = 'dashboard.php';
              </script>";
        exit;
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

// Fetch authors and categories for dropdowns
$authors    = $conn->query("SELECT author_id, name FROM authors ORDER BY name");
$categories = $conn->query("SELECT category_id, genre FROM categories ORDER BY genre");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Book</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
        }
        form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        label {
            display: block;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
            margin-top: 15px;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
            box-sizing: border-box;
        }
        input:focus, select:focus {
            border-color: #4a6fa5;
            outline: none;
            box-shadow: 0 0 0 2px rgba(74, 111, 165, 0.2);
        }
        input[type="submit"] {
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
        input[type="submit"]:hover {
            background-color: #3a5a80;
        }
        @media (max-width: 768px) {
            form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
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
                <option value="<?= $row['author_id'] ?>">
                    <?= htmlspecialchars($row['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="category_id">Category:</label>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php while ($row = $categories->fetch_assoc()): ?>
                <option value="<?= $row['category_id'] ?>">
                    <?= htmlspecialchars($row['genre']) ?>
                </option>
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

        <input type="submit" name="books" value="Add Book">
    </form>
</body>
</html>
