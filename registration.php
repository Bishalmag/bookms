<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $plainPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($plainPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $conn = new mysqli('localhost', 'root', '', 'book_management_system');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the email already exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "<script>alert('This email is already registered. Please use a different one or login.');</script>";
        } else {
            // Insert user with plain password (for learning only)
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $plainPassword);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

        $checkStmt->close();
        $conn->close();
    }
}}
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

        h1, h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
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

        p.login-link {
            text-align: center;
            margin-top: 15px;
        }

        p.login-link a {
            color: #4a6fa5;
            text-decoration: none;
        }

        p.login-link a:hover {
            text-decoration: underline;
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
        <form action="registration.php" method="POST" onsubmit="return validateForm()">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
            <p class="login-link">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </form>
    </div>
</body>
</html>
<script>
    function validateForm() {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;
        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }
        return true;
    }
    </script>