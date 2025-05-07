<?php
session_start();

function checkCredentials($email, $password, $userType) {
    $conn = new mysqli('localhost', 'root', '', 'book_management_system');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use the appropriate table based on user type
    $table = $userType === "admin" ? "admin" : "users";

    // Fetch user ID, email, and password
    $stmt = $conn->prepare("SELECT id, email, password FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $dbEmail, $dbPassword);
        $stmt->fetch();

        // Compare plain text passwords (not secure — use hashing in production)
        if ($password === $dbPassword) {
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $dbEmail;
            $_SESSION['user_id'] = $user_id; // ✅ Needed for checkout
            $_SESSION['user_type'] = $userType;

            $stmt->close();
            $conn->close();
            return true;
        } else {
            echo "Password did not match<br>";
        }
    } else {
        echo "No user found with the provided email.<br>";
    }

    $stmt->close();
    $conn->close();
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $userType = $_POST["user_type"];

    if (checkCredentials($email, $password, $userType)) {
        if ($userType === "admin") {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: homepage.php");
        }
        exit();
    } else {
        echo "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .login-container {
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

        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
            color: #333;
        }

        select {
            background-color: #fff;
            cursor: pointer;
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

        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }

        .forgot-password a {
            text-decoration: none;
            color: #4a6fa5;
            font-size: 14px;
            transition: color 0.3s;
        }

        .forgot-password a:hover {
            text-decoration: underline;
            color: #3a5a80;
        }

        @media (max-width: 500px) {
            .login-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Book Management System </h1>
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="user_type" required>
                <option value="" disabled selected>User Type</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <button type="submit">Login</button>
        </form>
        <div class="forgot-password">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </div>
</body>
</html>
