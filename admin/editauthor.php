<?php
require_once 'admin_auth.php';
// Database connection
$conn = mysqli_connect("localhost", "root", "", "book_management_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if author_id is present
if (!isset($_GET['author_id'])) {
    header("Location: allauthors.php");
    exit();
}

$author_id = $_GET['author_id'];

// Fetch author details
$sql = "SELECT * FROM authors WHERE author_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $author_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows < 1) {
    echo "Author not found!";
    exit();
}

$author = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['update_author'])) {
    $name = $_POST['name'];

    // SQL update query
    $update_sql = "UPDATE authors SET name = ? WHERE author_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $name, $author_id);

    if ($stmt->execute()) {
        echo "<script>alert('Author updated successfully!'); window.location.href = 'allauthors.php';</script>";
        exit();
    } else {
        echo "Error updating author: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Author</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 20px;
    }

    .form-container {
        width: 50%;
        margin: 40px auto;
        padding: 30px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    input[type="text"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #4a6fa5;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #3a5a80;
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
    <h2>Edit Author</h2>
    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" value="<?php echo $author['name']; ?>" required>
        

        <input type="submit" name="update_author" value="Update Author">
    </form>
</div>

</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>
