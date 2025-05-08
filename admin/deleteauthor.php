<?php
require_once 'admin_auth.php';

// Database connection
$conn = mysqli_connect("localhost", "root", "", "book_management_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if author_id is set
if (isset($_GET['author_id'])) {
    $author_id = $_GET['author_id'];

    // Prepare delete query
    $sql = "DELETE FROM authors WHERE author_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $author_id);

    // Execute delete query
    if ($stmt->execute()) {
        echo "<script>alert('Author deleted successfully!'); window.location.href = 'allauthors.php';</script>";
    } else {
        echo "Error deleting author: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request!";
}

mysqli_close($conn);
?>
