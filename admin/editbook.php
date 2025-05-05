<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "book_management_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if book_id is present
if (!isset($_GET['book_id'])) {
    header("Location: allbook.php");
    exit();
}

$book_id = $_GET['book_id'];

// Fetch book details
$sql = "SELECT * FROM books WHERE book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows < 1) {
    echo "Book not found!";
    exit();
}

$book = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['update_book'])) {
    $title = $_POST['title'];
    $isbn = $_POST['isbn'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $publication_year = $_POST['publication_year'];
    $publisher = $_POST['publisher'];
    $copies_available = $_POST['copies_available'];
    $price = $_POST['price']; // Add price to the form data

    // Default to existing image
    $image = $book['image'];

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        $upload_dir = "uploads/";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        move_uploaded_file($tmp, $upload_dir . $image);
    }

    // SQL update (with image)
    $update_sql = "UPDATE books SET title = ?, isbn = ?, price = ?, author_id = ?, category_id = ?, publication_year = ?, publisher = ?, copies_available = ?, image = ? WHERE book_id = ?";
    $stmt = $conn->prepare($update_sql);
    // Bind parameters correctly (price is a float - d)
    $stmt->bind_param("ssdiisisss", $title, $isbn, $price, $author_id, $category_id, $publication_year, $publisher, $copies_available, $image, $book_id);

    if ($stmt->execute()) {
        echo "<script>alert('Book updated successfully!'); window.location.href = 'allbook.php';</script>";
        exit();
    } else {
        echo "Error updating book: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .form-container {
            width: 50%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
            color: #333;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        img {
            margin: 10px 0;
            max-width: 120px;
        }

        .form-container input[type="file"] {
            padding: 5px;
            background-color: #f8f8f8;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .form-container p {
            font-style: italic;
            color: #777;
        }

        .form-container select {
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Book</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Title</label>
        <input type="text" name="title" value="<?php echo $book['title']; ?>" required>

        <label for="isbn">ISBN</label>
        <input type="text" name="isbn" value="<?php echo $book['isbn']; ?>" required>

        <label for="price">Price</label>
        <input type="number" step="0.01" name="price" value="<?php echo $book['price']; ?>" required>

        <label for="author_id">Author</label>
        <select name="author_id" required>
            <?php
            $authors = mysqli_query($conn, "SELECT * FROM authors");
            while ($author = mysqli_fetch_assoc($authors)) {
                $selected = ($book['author_id'] == $author['author_id']) ? 'selected' : '';
                echo "<option value='{$author['author_id']}' $selected>{$author['name']}</option>";
            }
            ?>
        </select>

        <label for="category_id">Category</label>
        <select name="category_id" required>
            <?php
            $categories = mysqli_query($conn, "SELECT * FROM categories");
            while ($category = mysqli_fetch_assoc($categories)) {
                $selected = ($book['category_id'] == $category['category_id']) ? 'selected' : '';
                echo "<option value='{$category['category_id']}' $selected>{$category['type']}</option>";
            }
            ?>
        </select>

        <label for="publication_year">Publication Year</label>
        <input type="number" name="publication_year" value="<?php echo $book['publication_year']; ?>" required>

        <label for="publisher">Publisher</label>
        <input type="text" name="publisher" value="<?php echo $book['publisher']; ?>" required>

        <label for="copies_available">Copies Available</label>
        <input type="number" name="copies_available" value="<?php echo $book['copies_available']; ?>" required>

        <label for="current_image">Current Image</label><br>
        <?php if (!empty($book['image'])): ?>
            <img src="uploads/<?php echo $book['image']; ?>" alt="Book Image">
        <?php else: ?>
            <p>No image uploaded.</p>
        <?php endif; ?>

        <label for="image">Upload New Image (optional)</label>
        <input type="file" name="image" accept="image/*">

        <input type="submit" name="update_book" value="Update Book">
    </form>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
