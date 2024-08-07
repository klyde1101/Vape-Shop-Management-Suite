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

// Initialize error variables
$errorUsername = "";
$errorPassword = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $inputUsername = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
    $inputPassword = trim($_POST['password']);

    // Validate input
    if (empty($inputUsername)) {
        $errorUsername = "Username is required.";
    }
    if (empty($inputPassword)) {
        $errorPassword = "Password is required.";
    }

    if (empty($errorUsername) && empty($errorPassword)) {
        $sql = "SELECT username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("MySQL error: " . $conn->error);
        }
        $stmt->bind_param("s", $inputUsername);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($dbUsername, $dbPassword);
            $stmt->fetch();

            if (strpos($dbPassword, '$2y$') === 0) {
                // Password is hashed
                if (password_verify($inputPassword, $dbPassword)) {
                    $_SESSION['username'] = $dbUsername;
                    header("Location: home.php"); // Redirect to welcome page
                    exit();
                } else {
                    $errorPassword = "Invalid password.";
                }
            } else {
                // Password is not hashed
                if ($inputPassword === $dbPassword) {
                    // Password matches, hash it and update in database
                    $newHashedPassword = password_hash($inputPassword, PASSWORD_DEFAULT);
                    $updateSql = "UPDATE users SET password = ? WHERE username = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    if ($updateStmt === false) {
                        die("MySQL error: " . $conn->error);
                    }
                    $updateStmt->bind_param("ss", $newHashedPassword, $inputUsername);
                    if ($updateStmt->execute()) {
                        $_SESSION['username'] = $dbUsername;
                        header("Location: home.php"); // Redirect to home page
                        exit(); 
                    } else {
                        $errorPassword = "Failed to update password.";
                    }
                    $updateStmt->close();
                } else {
                    $errorPassword = "Invalid password.";
                }
            }
        } else {
            $errorUsername = "Username not found.";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link href="login.css" rel="stylesheet">
<style>
  body {
    background-color: #212121;
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

</style>
</head>
<body>
<div class="login-box">
  <form action="index.php" method="post">
    <div class="user-box">
      <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>" class="<?php echo $errorUsername ? 'error' : ''; ?>" required>
      <label>Username</label>
      <?php if ($errorUsername): ?>
        <div class="error-message"><?php echo $errorUsername; ?></div>
      <?php endif; ?>
    </div>
    <div class="user-box">
      <input type="password" name="password" class="<?php echo $errorPassword ? 'error' : ''; ?>" required>
      <label>Password</label>
      <?php if ($errorPassword): ?>
        <div class="error-message"><?php echo $errorPassword; ?></div>
      <?php endif; ?>
    </div>
    <button type="submit">LOGIN<span></span></button>
  </form>
</div>
</body>
</html>
