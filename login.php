<?php
require 'db_connect.php'; // Include the database connection file

// Initialize variables to hold error messages
$email = $password = "";
$error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Retrieve the user details from the database
        $sql = "SELECT * FROM User WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start session and store user info
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['name'];

                // Redirect to dashboard.php
                header("Location: dashboard.php");
                exit();
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "No account found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <header>
            <h1>Login</h1>
        </header>
        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?= $error_message; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <br>
        <nav>
            <a href="register.php">Don't have an account? Register here</a>
        </nav>
        <br>
        <a href="index.php" style="
            padding: 10px 20px; 
            background-color: #214d79; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            font-size: 16px; 
            display: inline-block;">
            Go Back
        </a>
    </div>
</body>
</html>
