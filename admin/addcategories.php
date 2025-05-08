<?php 
require_once 'admin_auth.php';
if (isset($_POST['add_category'])) {
    // Retrieve form data (category_id is auto-incremented)
    $genre = $_POST['genre'];  // Change 'type' to 'genre'
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

    // Prepare SQL statement (no need to insert category_id since it's auto incremented)
    $sql = "INSERT INTO categories (genre, description) VALUES (?, ?)";  // Change 'type' to 'genre'

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $genre, $description);  // Bind genre and description (both are strings)

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
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
        }

        form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: #4a6fa5;
            outline: none;
            box-shadow: 0 0 0 2px rgba(74, 111, 165, 0.2);
        }

        input[type="submit"] {
            background-color: #4a6fa5;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 20px;
            width: 100%;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #3a5a80;
        }

        @media (max-width: 768px) {
            form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <form method="POST" action="addcategories.php">
        <h2>Add a New Category</h2>

        <!-- Remove category_id input field, it's auto-incremented by the database -->
        
        <label for="genre">Genre</label> <!-- Changed label from "Type" to "Genre" -->
        <input type="text" name="genre" required> <!-- Changed input name from "type" to "genre" -->

        <label for="description">Description</label>
        <input type="text" name="description" required>

        <input type="submit" name="add_category" value="Add Category">
    </form>

</body>
</html>
