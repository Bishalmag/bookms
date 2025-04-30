<?php
session_start();


// Database connection
$conn = mysqli_connect("localhost", "root", "", "book_management_system");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if book_id is set in the URL
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Prepare the delete query
    $sql = "DELETE FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);

    if ($stmt->execute()) {
        // Redirect to dashboard after successful deletion
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "No book selected to delete.";
}

// Close the database connection
mysqli_close($conn);
?>
