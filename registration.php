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
</head>
<body>
  <h2>Registration Form</h2>
  <form action="registration.php" method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Register</button>
  </form>
</body>
</html>
