<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "business";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['username'])) {
        $inputUsername = $_SESSION['username'];
        $newPassword = $_POST['new_password'];

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("MySQL error: " . $conn->error);
        }
        $stmt->bind_param("ss", $hashedPassword, $inputUsername);
        if ($stmt->execute()) {
            echo "Password updated successfully. <a href='index.php'>Login again</a>";
        } else {
            echo "Failed to update password.";
        }
        $stmt->close();
        session_unset();
        session_destroy();
    } else {
        echo "No active session found.";
    }
}
$conn->close();
?>
