<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $username = htmlspecialchars(trim($_POST["username"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $plainPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Initialize error array
    $errors = [];
    
    // Field presence validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (empty($plainPassword)) {
        $errors[] = "Password is required.";
    }
    
    if (empty($confirmPassword)) {
        $errors[] = "Please confirm your password.";
    }
    
    // Password validation
    if ($plainPassword !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }
    
    if (strlen($plainPassword) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    
    if (!preg_match("/[A-Z]/", $plainPassword)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }
    
    if (!preg_match("/[a-z]/", $plainPassword)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    }
    
    if (!preg_match("/[0-9]/", $plainPassword)) {
        $errors[] = "Password must contain at least one number.";
    }
    
    if (!preg_match("/[\W_]/", $plainPassword)) {
        $errors[] = "Password must contain at least one special character.";
    }

    // Proceed if no errors
    if (empty($errors)) {
        $conn = new mysqli('localhost', 'root', '', 'book_management_system');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if email already exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errors[] = 'This email is already registered. Please use a different one or login.';
        } else {
            // Hash the password
            $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
            
            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
        }

        $checkStmt->close();
        
        $conn->close();
    }
    
    // Display errors if any
    if (!empty($errors)) {
        echo '<script>alert("' . implode("\\n", $errors) . '");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .register-container {
            width: 400px;
            max-width: 100%;
            margin: 50px auto;
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
            margin: 8px 0 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
            box-sizing: border-box;
            transition: border 0.3s;
        }

        input:focus {
            border-color: #4a6fa5;
            outline: none;
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
            margin-top: 10px;
        }

        button:hover {
            background-color: #3a5a80;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
        }

        .login-link a {
            color: #4a6fa5;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }

        .password-rules {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: -10px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .rule-valid {
            color: #27ae60;
        }

        .rule-invalid {
            color: #e74c3c;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 20px 15px;
                margin: 30px auto;
            }
            
            h1 {
                font-size: 24px;
            }
            
            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Book Management System</h1>
        <h2>Registration Form</h2>
        <form id="registrationForm" action="registration.php" method="POST">
            <div>
                <input type="text" name="username" id="username" placeholder="Username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div>
                <input type="email" name="email" id="email" placeholder="Email" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <div id="emailError" class="error-message"></div>
            </div>
            
            <div>
                <input type="password" id="password" name="password" placeholder="Password" required
                       oninput="checkPasswordStrength()">
                <div class="password-rules" id="passwordRules">
                    Password must contain:
                    <span id="length" class="rule-invalid">8+ characters</span>, 
                    <span id="uppercase" class="rule-invalid">uppercase</span>, 
                    <span id="lowercase" class="rule-invalid">lowercase</span>, 
                    <span id="number" class="rule-invalid">number</span>, 
                    <span id="special" class="rule-invalid">special character</span>
                </div>
            </div>
            
            <div>
                <input type="password" id="confirm_password" name="confirm_password" 
                       placeholder="Confirm Password" required>
                <div id="confirmPasswordError" class="error-message"></div>
            </div>
            
            <button type="submit" id="submitBtn">Register</button>
            
            <p class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const emailInput = document.getElementById('email');
            
            // Real-time validation
            emailInput.addEventListener('input', validateEmail);
            passwordInput.addEventListener('input', checkPasswordStrength);
            confirmPasswordInput.addEventListener('input', validatePasswordConfirmation);
            
            // Form submission
            form.addEventListener('submit', function(event) {
                if (!validateForm()) {
                    event.preventDefault();
                }
            });
        });
        
        function validateForm() {
            let isValid = true;
            const errors = [];
            
            // Validate email
            if (!validateEmail()) {
                isValid = false;
            }
            
            // Validate password strength
            const password = document.getElementById('password').value;
            if (password.length < 8) {
                errors.push("Password must be at least 8 characters");
                isValid = false;
            }
            if (!/[A-Z]/.test(password)) {
                errors.push("Password needs an uppercase letter");
                isValid = false;
            }
            if (!/[a-z]/.test(password)) {
                errors.push("Password needs a lowercase letter");
                isValid = false;
            }
            if (!/[0-9]/.test(password)) {
                errors.push("Password needs a number");
                isValid = false;
            }
            if (!/[\W_]/.test(password)) {
                errors.push("Password needs a special character");
                isValid = false;
            }
            
            // Validate password confirmation
            if (!validatePasswordConfirmation()) {
                isValid = false;
            }
            
            // Show errors if any
            if (errors.length > 0) {
                alert("Please fix the following errors:\n\n" + errors.join('\n'));
            }
            
            return isValid;
        }
        
        function validateEmail() {
            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            
            if (!email) {
                emailError.textContent = '';
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                emailError.textContent = 'Please enter a valid email address';
                return false;
            } else {
                emailError.textContent = '';
                return true;
            }
        }
        
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            
            // Update visual indicators
            document.getElementById('length').className = password.length >= 8 ? 'rule-valid' : 'rule-invalid';
            document.getElementById('uppercase').className = /[A-Z]/.test(password) ? 'rule-valid' : 'rule-invalid';
            document.getElementById('lowercase').className = /[a-z]/.test(password) ? 'rule-valid' : 'rule-invalid';
            document.getElementById('number').className = /[0-9]/.test(password) ? 'rule-valid' : 'rule-invalid';
            document.getElementById('special').className = /[\W_]/.test(password) ? 'rule-valid' : 'rule-invalid';
            
            // Validate confirmation in real-time
            validatePasswordConfirmation();
        }
        
        function validatePasswordConfirmation() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const confirmError = document.getElementById('confirmPasswordError');
            
            if (!confirmPassword) {
                confirmError.textContent = '';
                return false;
            }
            
            if (password && confirmPassword && password !== confirmPassword) {
                confirmError.textContent = 'Passwords do not match!';
                return false;
            } else {
                confirmError.textContent = '';
                return true;
            }
        }
    </script>
</body>
</html>