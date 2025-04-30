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
    $update_sql = "UPDATE books SET title = ?, isbn = ?, author_id = ?, category_id = ?, publication_year = ?, publisher = ?, copies_available = ?, image = ? WHERE book_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssiiisiss", $title, $isbn, $author_id, $category_id, $publication_year, $publisher, $copies_available, $image, $book_id);

    if ($stmt->execute()) {
        echo "<script>alert('Book updated successfully!'); window.location.href = 'allbook.php';</script>";
        exit();
    } else {
        echo "Error updating book: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <style>
        .form-container {
            width: 50%;
            margin: 20px auto;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
        }
        img {
            margin: 10px 0;
            max-width: 120px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Book</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Title</label>
        <input type="text" name="title" value="<?php echo $book['title']; ?>" required>

        <label>ISBN</label>
        <input type="text" name="isbn" value="<?php echo $book['isbn']; ?>" required>

        <label>Author</label>
        <select name="author_id" required>
            <?php
            $authors = mysqli_query($conn, "SELECT * FROM authors");
            while ($author = mysqli_fetch_assoc($authors)) {
                $selected = ($book['author_id'] == $author['author_id']) ? 'selected' : '';
                echo "<option value='{$author['author_id']}' $selected>{$author['name']}</option>";
            }
            ?>
        </select>

        <label>Category</label>
        <select name="category_id" required>
            <?php
            $categories = mysqli_query($conn, "SELECT * FROM categories");
            while ($category = mysqli_fetch_assoc($categories)) {
                $selected = ($book['category_id'] == $category['category_id']) ? 'selected' : '';
                echo "<option value='{$category['category_id']}' $selected>{$category['type']}</option>";
            }
            ?>
        </select>

        <label>Publication Year</label>
        <input type="number" name="publication_year" value="<?php echo $book['publication_year']; ?>" required>

        <label>Publisher</label>
        <input type="text" name="publisher" value="<?php echo $book['publisher']; ?>" required>

        <label>Copies Available</label>
        <input type="number" name="copies_available" value="<?php echo $book['copies_available']; ?>" required>

        <label>Current Image</label><br>
        <?php if (!empty($book['image'])): ?>
            <img src="uploads/<?php echo $book['image']; ?>" alt="Book Image">
        <?php else: ?>
            <p>No image uploaded.</p>
        <?php endif; ?>

        <label>Upload New Image (optional)</label>
        <input type="file" name="image" accept="image/*">

        <input type="submit" name="update_book" value="Update Book">
    </form>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
