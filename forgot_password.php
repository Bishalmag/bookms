<?php
session_start();
include 'db.php'; // your PDO connection

$message = ''; // to display messages like error or success

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the email entered by the user
    $email = $_POST['email'] ?? '';

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Email exists, proceed to reset password page
        $_SESSION['reset_user_id'] = $user['id'];
        header("Location: reset_password.php");
        exit();
    } else {
        // Email doesn't exist in the database
        $message = "No account found with that email address.";
    }
}
?>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .forgot-container {
        width: 400px;
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

    input[type="email"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0 20px 0;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 16px;
        color: #333;
        box-sizing: border-box;
    }

    input[type="email"]:focus {
        outline: none;
        border-color: #4a6fa5;
        box-shadow: 0 0 0 2px rgba(74, 111, 165, 0.2);
    }

    button[type="submit"] {
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

    button[type="submit"]:hover {
        background-color: #3a5a80;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .message {
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 5px;
        text-align: center;
        font-size: 14px;
        background-color: #fdecea;
        color: #e74c3c;
        border-left: 4px solid #e74c3c;
    }

    .login-link {
        text-align: center;
        margin-top: 20px;
    }

    .login-link a {
        text-decoration: none;
        color: #4a6fa5;
        font-size: 14px;
        transition: color 0.3s;
    }

    .login-link a:hover {
        text-decoration: underline;
        color: #3a5a80;
    }

    @media (max-width: 500px) {
        .forgot-container {
            width: 90%;
            padding: 25px 20px;
        }
        
        h2 {
            font-size: 24px;
        }
    }
</style>

<div class="forgot-container">
    <form method="POST">
        <h2>Forgot Password</h2>
        <?php if ($message) echo "<div class='message'>$message</div>"; ?>
        <input type="email" name="email" required placeholder="Enter your email">
        <button type="submit">Submit</button>
        <div class="login-link">
            <a href="login.php">Back to Login</a>
        </div>
    </form>
</div>