<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    // Get the plain password from the form
    $plainPassword = $_POST['password'];

    $conn = new mysqli('localhost', 'root', '', 'book_management_system');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the user into the database with the plain password
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $plainPassword);

    // Execute the query and handle success/failure
    if ($stmt->execute()) {
        // Registration successful — redirect to login
        header("Location: login.php");
        exit();
    } else {
        // Error occurred while inserting the data
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    return false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .register-container {
            width: 400px;
            margin: 100px auto;
            background-color: #ffffff;
            padding: 30px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-left: 8px solid #4a6fa5;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 28px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
            color: #333;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4a6fa5;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #3a5a80;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 500px) {
            .register-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Book Management System</h1>
        <h2>Registration Form</h2>
        <form action="registration.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
