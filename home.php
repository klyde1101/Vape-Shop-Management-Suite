<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> <!-- ROBOTO FONT -->
<link href="header.css" rel="stylesheet">
<link href="confirmation_dialog.css" rel="stylesheet">
<link href="home.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&display=swap'); /* RED HAT FONT */

  body {
    background-color: #212121; 
    color: #fff; /* Font Color */
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

</style>
</head>
<body>


<!-- HEADER -->
<header>
  <div class="header-left">

    <img src="logo_here.png" alt="Logo">


    <!-- HEADER NAVIGATION -->
    <div class="nav-buttons">

      <button class="nav-button" onclick="location.href='home.php'">Home</button>
      
      <!-- SALES DROPDOWN -->
      <div class="dropdown">

        <button class="nav-button">Sales</button>

        <div class="dropdown-content">
          <a href="sales.php">View Sales</a>
          <a href="add_sales.php">Add Sales</a>
        </div>
      </div>
      <!-- SALES DROPDOWN -->

      <!-- EXPENSES DROPDOWN -->
      <div class="dropdown">
      <button class="nav-button" onclick="location.href='expenses.php'">Expenses</button>
      <div class="dropdown-content">
          <a href="expenses.php">View Expenses</a>
          <a href="add_expensse.php">Add Expenses</a>
        </div>
      </div>
      <!-- EXPENSES DROPDOWN -->

      <button class="nav-button" onclick="location.href='#stock'">Stock</button>  <!-- STOCK NAVIGATION -->
      <button class="nav-button" onclick="location.href='#graph'">Graph</button>  <!-- GRAPH NAVIGATION -->

      <!-- PRICELIST DROPDOWN -->
      <div class="dropdown">
      <button class="nav-button" onclick="location.href='price_list.php'">Price List</button>
      <div class="dropdown-content">
          <a href="price_list.php">View Price List</a>
          <a href="add_item.php">Add Item</a>
        </div>
      </div>
      <!-- PRICELIST DROPDOWN -->
    


    </div>
    <!-- HEADER NAVIGATION -->
  </div>
  <!-- HEADER -->


  <!-- LOGOUT BUTTON-->
  <div class="button-borders">
    <button class="primary-button" onclick="showLogoutDialog()">LOGOUT</button>
  </div>
   <!-- LOGOUT BUTTON-->


</header>
<!-- HEADER -->


<!-- CONTAINER - Info Cards -->
<div class="container">

   

    <!-- SALES Info Card -->
    <div class="info-card" id="sales">
        <div class="align">
            <div class="red"></div>
            <div class="yellow"></div>
            <div class="green"></div>
        </div>
        <h1>Sales</h1>
        <div class="sales">Sales content goes here.</div> <!-- Total SALES of the CURRENT MONTH & YEAR should be displayed here -->
    </div>
    <!-- SALES Info Card -->

    <!-- EXPENSES Info Card -->
    <div class="info-card" id="expenses">
        <div class="align">
            <div class="red"></div>
            <div class="yellow"></div>
            <div class="green"></div>
        </div>
        <h1>Expenses</h1>
        <div class="expenses">Expenses content goes here.</div> <!-- Total EXPENSES of the CURRENT MONTH & YEAR should be displayed here -->
    </div>
    <!-- EXPENSES Info Card -->

    <!-- STOCK Info Card -->
    <div class="info-card" id="stock">
        <div class="align">
            <div class="red"></div>
            <div class="yellow"></div>
            <div class="green"></div>
        </div>
        <h1>Stock</h1>
        <div class="stock">Stock content goes here.</div> <!-- Total NUMBER & VALUE of STOCK of the CURRENT MONTH & YEAR should be displayed here -->
    </div>
    <!-- STOCK Info Card -->

    <!-- GRAPH Info Card -->
    <div class="info-card" id="graph">
        <div class="align">
            <div class="red"></div>
            <div class="yellow"></div>
            <div class="green"></div>
        </div>
        <h1>Graph</h1>
        <div class="graph">Graph content goes here.</div>
    </div>
    <!-- GRAPH Info Card -->
    
</div>
<!-- CONTAINER - Info Cards -->



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



<script>

  /* SHOW LOGOUT DIALOG */
  function showLogoutDialog() {
    document.getElementById('logoutDialog').classList.add('show');
  }

  /* CLOSE LOGOUT DIALOG */
  function closeLogoutDialog() {
    document.getElementById('logoutDialog').classList.remove('show');
  }

</script>

</body>
</html>