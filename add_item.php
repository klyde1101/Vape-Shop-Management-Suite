<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "business";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle add request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand = $_POST['brand'];
    $type = $_POST['type'];
    $item = $_POST['item'];
    $price = $_POST['price'];

    $sql = "INSERT INTO price_list (brand, type, item, price) VALUES ('$brand', '$type', '$item', '$price')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New item added successfully!'); window.location.href='price_list.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link href="header.css" rel="stylesheet">
    <link href="confirmation_dialog.css" rel="stylesheet">
    <style>
       body {
    background-color: #212121;
    color: #fff;
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
}


/* CONFIRMATION DIALOG */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    display: none;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
    opacity: 0;
    z-index: 1000;
}

.overlay.show {
    display: flex;
    opacity: 1;
    z-index: 1001;
}

.dialog {
    background: #212121;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 300px;
    color: #A6A6A6;
}

.dialog h2 {
    margin: 0;
    color: #FFFFFF;
    font-size: 24px;
}

.dialog p {
    margin: 20px 0;
    color: #FFFFFF;
}

.dialog button {
    width: 30%; /* Adjusted width */
    padding: 10px;
    font-size: 18px;
    border-radius: 3px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.3s ease;
    margin: 10px 5%;
    display: inline-block;
    border: none;
    text-transform: uppercase;
}

.dialog button[type="submit"] {
    background: #FF4655;
    color: white;
}

.dialog button[type="submit"]:hover {
    background: #FF7885;
    transform: translateY(-2px);
    max-width: 80%;
}

.close-btn {
    background: #A6A6A6;
    color: white;
}

.close-btn:hover {
    background: #C0C0C0;
    transform: translateY(-2px);
}

/* CONFIRMATION DIALOG */


.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
    padding: 20px;
    box-sizing: border-box;
}

.form-block {
    background-color: #333;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    width: 100%;
    max-width: 400px;
}

.form-block h2 {
    margin-bottom: 20px;
    text-align: center;
}

.form-block label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

.form-block input[type="text"], .form-block input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: none;
    border-radius: 4px;
    box-sizing: border-box;
    
    
}

.form-block input[type="submit"] {
    background-color: #4CAF50;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-weight: bold;
    font-size: 14pt;
}

.form-block input[type="submit"]:hover {
    background-color: #45a049;
}



    </style>
</head>
<body>
<header>
    <div class="header-left">
        <img src="logo_here.png" alt="Logo">
        <div class="nav-buttons">
            <button class="nav-button" onclick="location.href='home.php'">Home</button>
            <div class="dropdown">
                <button class="nav-button">Sales</button>
                <div class="dropdown-content">
                    <a href="sales.php">View Sales</a>
                    <a href="add_sales.php">Add Sales</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="nav-button" onclick="location.href='expenses.php'">Expenses</button>
                <div class="dropdown-content">
                    <a href="expenses.php">View Expenses</a>
                    <a href="add_expenses.php">Add Expenses</a>
                </div>
            </div>
            <button class="nav-button" onclick="location.href='#stock'">Stock</button>
            <button class="nav-button" onclick="location.href='#graph'">Graph</button>
            <div class="dropdown">
                <button class="nav-button" onclick="location.href='price_list.php'">Price List</button>
                <div class="dropdown-content">
                    <a href="price_list.php">View Price List</a>
                    <a href="add_item.php">Add Item</a>
                </div>
            </div>
        </div>
    </div>
    <div class="button-borders">
        <button class="primary-button" onclick="showLogoutDialog()">LOGOUT</button>
    </div>
</header>

<!-- Logout Confirmation Dialog -->
<div class="overlay" id="logoutDialog">
  <div class="dialog">
    <h2>Logout Confirmation</h2>
    <p>Are you sure you want to logout?</p>
    <form action="home.php" method="post">
      <button type="submit" name="logout">Yes</button>
      <button type="button" class="close-btn" onclick="closeLogoutDialog()">No</button>
    </form>
  </div>
</div>
<!-- Logout Confirmation Dialog -->

<div class="container">
    <div class="form-block">
        <h2>Add New Item</h2>
        <form method="POST" action="add_item.php">
            <label for="brand">Brand:</label>
            <input type="text" id="brand" name="brand" placeholder="Black" required>
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" placeholder="Pod" required>
            <label for="item">Item:</label>
            <input type="text" id="item" name="item" placeholder="Elite 8000, Elite 12000" required>
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" placeholder="350" required>
            <input type="submit" value="ADD ITEM">
        </form>
    </div>
</div>


<script>
function showLogoutDialog() {
    document.getElementById('logoutDialog').classList.add('show');
}

function closeLogoutDialog() {
    document.getElementById('logoutDialog').classList.remove('show');
}
</script>

</body>




</html>
