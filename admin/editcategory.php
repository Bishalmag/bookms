<?php
require_once 'admin_auth.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "book_management_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect if no category_id is set
if (!isset($_GET['category_id'])) {
    header("Location: allcategories.php");
    exit();
}

$category_id = $_GET['category_id'];

// Fetch category details
$sql = "SELECT * FROM categories WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows < 1) {
    echo "Category not found!";
    exit();
}

$category = $result->fetch_assoc();

// Handle update form submission
if (isset($_POST['update_category'])) {
    $type = $_POST['type'];
    $description = $_POST['description'];

    $update_sql = "UPDATE categories SET type = ?, description = ? WHERE category_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $type, $description, $category_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Category updated successfully!'); window.location.href = 'allcategories.php';</script>";
        exit();
    } else {
        echo "Error updating category: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 20px;
    }

    .form-container {
        width: 50%;
        margin: 40px auto;
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 25px;
    }

    label {
        font-weight: 600;
        color: #333;
        display: block;
        margin-bottom: 6px;
    }

    input[type="text"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
        background-color: #fff;
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
   <h2>Edit Category</h2>
    <form method="POST">
        <label for="type">Type</label><br>
        <input type="text" name="type" value="<?php echo htmlspecialchars($category['type']); ?>" required><br><br>

        <label for="description">Description</label><br>
        <input type="text" name="description" value="<?php echo htmlspecialchars($category['description']); ?>" required><br><br>

        <input type="submit" name="update_category" value="Update Category">
    </form>
   </div>
</body>
</html>

<?php $conn->close(); ?>
