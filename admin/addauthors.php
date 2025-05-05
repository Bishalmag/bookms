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

<!DOCTYPE html>
<html>
<head>
    <title>Add Authors</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="number"] {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
            width: 100%;
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
            margin-top: 15px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #3a5a80;
        }

        .form-group {
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add a New Author</h2>
        <form method="POST" action="addauthors.php">
            <div class="form-group">
                <label for="author_id">Author ID</label>
                <input type="number" name="author_id" required>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" required>
            </div>

            <input type="submit" name="authors" value="Add Author">
        </form>
    </div>
</body>
</html>