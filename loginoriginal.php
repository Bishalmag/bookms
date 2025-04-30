<?php
session_start();

// ✅ Move the function OUTSIDE the POST check
function checkCredentials($email, $password, $userType) {
    $conn = new mysqli('localhost', 'root', '', 'book_management_system');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $table = $userType == "admin" ? "admin" : "users";

    $stmt = $conn->prepare("SELECT email, password FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($dbEmail, $dbPassword);
        $stmt->fetch();

        if (password_verify($password, $dbPassword)) {
            echo "✅ Password matched<br>";
            $_SESSION['email'] = $dbEmail;
            $_SESSION['user_type'] = $userType;
            $stmt->close();
            $conn->close();
            return true;
        } else {
            echo "Password did not match<br>";
        }
    }

    $stmt->close();
    $conn->close();
    return false;
}

// ✅ Now PHP knows this function when POST is called
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $userType = $_POST["user_type"];

    if (checkCredentials($email, $password, $userType)) {
        if ($userType == "admin") {
            header("Location: dashboard.php");
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
    
</head>
<body>
    <div class="login-container">
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
    </div>
</body>
</html>
