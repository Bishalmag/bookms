<?php 
if (isset($_POST['add_category'])) {
    // Retrieve form data
    $category_id = $_POST['category_id'];
    $type = $_POST['type'];
    $description = $_POST['description'];

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

    // Prepare SQL statement
    $sql = "INSERT INTO categories (category_id, type, description) VALUES (?, ?, ?)";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $category_id, $type, $description); // i = int, s = string

    // Execute
    if ($stmt->execute()) {
        echo "<script>
                alert('Category added successfully');
                window.location.href = 'dashboard.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
</head>
<body>
    <h2>Add a New Category</h2>
    <form method="POST" action="addcategories.php">
        <label for="category_id">Category ID</label><br>
        <input type="number" name="category_id" required><br><br>

        <label for="type">Type</label><br>
        <input type="text" name="type" required><br><br>
        
        <label for="description">Description</label><br>
        <input type="text" name="description" required><br><br>

        <input type="submit" name="add_category" value="Add Category">
    </form>
</body>
</html>
