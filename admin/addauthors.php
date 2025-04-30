<?php 
if (isset($_POST['authors'])) {
    // Retrieve form data
    $author_id = $_POST['author_id'];
    $name = $_POST['name'];

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
    $sql = "INSERT INTO authors (author_id, name) VALUES (?, ?)";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $author_id, $name); // i = int, s = string

    // Execute
    if ($stmt->execute()) {
        echo "<script>
                alert('Author added successfully');
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

<!DOCname html>
<html>
<head>
    <title>Add Authors</title>
</head>
<body>
    <h2>Add a New Authors</h2>
    <form method="POST" action="addauthors.php">
        <label for="author_id">Author ID</label><br>
        <input type="number" name="author_id" required><br><br>

        <label for="name">Name</label><br>
        <input type="text" name="name" required><br><br>

        <input type="submit" name="authors" value="Add Author">

    </form>
</body>
</html>
