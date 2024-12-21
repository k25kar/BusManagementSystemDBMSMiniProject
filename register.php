<?php
require 'db_connect.php'; // Include the database connection file

// Initialize variables to hold error messages
$name = $email = $password = $contact = "";
$error_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $contact = $conn->real_escape_string($_POST['contact']);

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif (!preg_match('/^[0-9]{10}$/', $contact)) {
        $error_message = "Contact number must be 10 digits.";
    } else {
        // Insert user details into the database
        $sql = "INSERT INTO User (name, email, password, contact) VALUES ('$name', '$email', '$password', '$contact')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to login page on successful registration
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <header>
            <h1>Bus Ticket Management System</h1>
            <h2>Register</h2>
        </header>

        <?php
        // Display error message if exists
        if (!empty($error_message)) {
            echo "<div style='color: red; text-align: center; margin-bottom: 15px;'>$error_message</div>";
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="contact">Contact:</label>
            <input type="text" name="contact" required>

            <button type="submit" name="register">Register</button>
        </form>
        <br>
        <nav>
            <a href="login.php">Already have an account? Login here</a>
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
