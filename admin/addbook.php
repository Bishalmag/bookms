<?php 
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['books'])) {
    // Retrieve form data
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $isbn = $_POST['isbn'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id']; 
    $publication_year = $_POST['publication_year'];
    $publisher = $_POST['publisher'];
    $copies_available = $_POST['copies_available'];
    $total_copies = $_POST['total_copies'];

    // Image upload handling
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $upload_dir = "uploads/";

    // Create uploads directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Move the uploaded file to the uploads folder
    move_uploaded_file($tmp_name, $upload_dir . $image);

    // SQL insert
    $sql = "INSERT INTO books (book_id, title, isbn, author_id, category_id, publication_year, publisher, copies_available, total_copies, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiiisiss", $book_id, $title, $isbn, $author_id, $category_id, $publication_year, $publisher, $copies_available, $total_copies, $image);

    if ($stmt->execute()) {
        echo "<script>
                alert('Book added successfully!');
                window.location.href = 'dashboard.php';
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

// Fetch authors and categories for dropdowns
$authors = $conn->query("SELECT author_id, name FROM authors");
$categories = $conn->query("SELECT category_id, type FROM categories");

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
</head>
<body>
    <h2>Add a New Book</h2>
    <form method="POST" action="addbook.php" enctype="multipart/form-data">
        <label for="book_id">Book ID:</label><br>
        <input type="text" name="book_id" required><br><br>

        <label for="title">Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label for="isbn">ISBN:</label><br>
        <input type="text" name="isbn" required><br><br>

        <label for="author_id">Author:</label><br>
        <select name="author_id" required>
            <option value="">Select Author</option>
            <?php while ($row = $authors->fetch_assoc()): ?>
                <option value="<?= $row['author_id'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="category_id">Category:</label><br>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php while ($row = $categories->fetch_assoc()): ?>
                <option value="<?= $row['category_id'] ?>"><?= $row['type'] ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="publication_year">Publication Year:</label><br>
        <input type="number" name="publication_year" required><br><br>

        <label for="publisher">Publisher:</label><br>
        <input type="text" name="publisher" required><br><br>

        <label for="copies_available">Copies Available:</label><br>
        <input type="number" name="copies_available" required><br><br>

        <label for="total_copies">Total Copies:</label><br>
        <input type="number" name="total_copies" required><br><br>

        <label for="image">Book Image:</label><br>
        <input type="file" name="image" id="image" accept="image/*" required><br><br>

        <input type="submit" name="books" value="Add Book">
    </form>
</body>
</html>
