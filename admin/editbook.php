<?php
require_once 'admin_auth.php';
include('header.php');
include('sidebar.php');

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
    $title = trim($_POST['title']);
    $isbn = trim($_POST['isbn']);
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $publication_year = $_POST['publication_year'];
    $publisher = trim($_POST['publisher']);
    $copies_available = $_POST['copies_available'];
    $price = $_POST['price'];

    // Validate publisher field
    if (empty($publisher) || $publisher === '0') {
        echo "<script>alert('Publisher cannot be empty or zero!');</script>";
    } else {
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

        // SQL update
        $update_sql = "UPDATE books SET title = ?, isbn = ?, price = ?, author_id = ?, category_id = ?, publication_year = ?, publisher = ?, copies_available = ?, image = ? WHERE book_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssdiisssss", $title, $isbn, $price, $author_id, $category_id, $publication_year, $publisher, $copies_available, $image, $book_id);

        if ($stmt->execute()) {
            echo "<script>alert('Book updated successfully!'); window.location.href = 'allbook.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error updating book: " . addslashes($conn->error) . "');</script>";
        }
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
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            border-color: #4a6fa5;
            outline: none;
        }

        .btn-submit {
            background-color: #4a6fa5;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #3a5a80;
        }

        .book-image {
            max-width: 150px;
            max-height: 200px;
            border-radius: 4px;
            margin: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .file-input {
            padding: 8px;
            background-color: #f8f9fa;
        }

        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Book</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="isbn">ISBN</label>
            <input type="text" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>" required>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" step="0.01" min="0" name="price" value="<?php echo htmlspecialchars($book['price']); ?>" required>
        </div>

        <div class="form-group">
            <label for="author_id">Author</label>
            <select name="author_id" required>
                <?php
                $authors = mysqli_query($conn, "SELECT * FROM authors");
                while ($author = mysqli_fetch_assoc($authors)) {
                    $selected = ($book['author_id'] == $author['author_id']) ? 'selected' : '';
                    echo "<option value='".htmlspecialchars($author['author_id'])."' $selected>".htmlspecialchars($author['name'])."</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="category_id">Genre</label>
            <select name="category_id" required>
                <?php
                $categories = mysqli_query($conn, "SELECT * FROM categories");
                while ($category = mysqli_fetch_assoc($categories)) {
                    $selected = ($category['category_id'] == $book['category_id']) ? 'selected' : '';
                    echo "<option value='".htmlspecialchars($category['category_id'])."' $selected>".htmlspecialchars($category['genre'])."</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="publication_year">Publication Year</label>
            <input type="number" min="1000" max="<?php echo date('Y'); ?>" name="publication_year" value="<?php echo htmlspecialchars($book['publication_year']); ?>" required>
        </div>

        <div class="form-group">
            <label for="publisher">Publisher</label>
            <input type="text"  name="publisher" value="<?php echo htmlspecialchars($book['publisher']); ?>" required>
        </div>

        <div class="form-group">
            <label for="copies_available">Copies Available</label>
            <input type="number" min="0" name="copies_available" value="<?php echo htmlspecialchars($book['copies_available']); ?>" required>
        </div>

        <div class="form-group">
            <label for="current_image">Current Image</label><br>
            <?php if (!empty($book['image']) && file_exists("uploads/".$book['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($book['image']); ?>" class="book-image" alt="Book Cover">
            <?php else: ?>
                <p>No image available</p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="image">Upload New Image (optional)</label>
            <input type="file" name="image" class="file-input" accept="image/*">
        </div>

        <button type="submit" name="update_book" class="btn-submit">Update Book</button>
    </form>
</div>

<?php mysqli_close($conn); ?>
<?php include('footer.php'); ?>