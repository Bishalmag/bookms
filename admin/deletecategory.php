<?php
require_once 'admin_auth.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "book_management_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if category_id is present
if (!isset($_GET['category_id'])) {
    header("Location: allcategories.php");
    exit();
}

$category_id = $_GET['category_id'];

// Prepare SQL query to delete the category
$sql = "DELETE FROM categories WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);

if ($stmt->execute()) {
    echo "<script>alert('Category deleted successfully!'); window.location.href = 'allcategories.php';</script>";
} else {
    echo "Error deleting category: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
