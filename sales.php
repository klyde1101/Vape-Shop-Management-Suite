<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'business';
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $updateQuery = "UPDATE sales SET deleted = 1 WHERE id = :id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute(['id' => $deleteId]);
    header("Location: sales.php"); // Redirect to refresh the page
    exit();
}

// Fetch data from the sales table with the 'deleted' column [0 = SHOW | 1 = HIDDEN]
$query = "SELECT id, brand, type, item, flavor, mop, proof, date, price FROM sales WHERE deleted = 0";
$stmt = $pdo->prepare($query);
$stmt->execute();
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sales</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link href="header.css" rel="stylesheet">
<link href="confirmation_dialog.css" rel="stylesheet">
<link href="sales_table.css" rel="stylesheet">

<style>
@import url('https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&display=swap');

body {
    background-color: #212121;
    color: #fff;
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
}

/* IMAGE POPUP (VIEW IMAGE PROOF) */
.image-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: none;
    align-items: center;
    justify-content: center;
    flex-direction: column; /* Arrange children in a column */
    z-index: 1000; /* Ensure it's above other content */
}

.image-popup img {
    max-width: 80%;
    max-height: 80%;
    border: 2px solid #fff;
    margin-bottom: 20px; /* Space between image and button */
}

.image-popup.show {
    display: flex;
}

.image-popup .close-btn {
    background: #FF4655;
    border: none;
    color: white;
    padding: 10px 20px;
    width: 200px;
    cursor: pointer;
    font-size: 16px;
    border-radius: 4px;
    z-index: 2; /* Ensure button is above the image */
    position: absolute; /* Position absolute to place it relative to the container */
    margin-top: 53%;
}

.image-popup .close-btn:hover {
    background: #E03E47;
}

/* IMAGE POPUP (VIEW IMAGE PROOF) */

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



<!-- IMAGE POPUP (VIEW IMAGE PROOF) -->
<div class="image-popup" id="imagePopup">
  <img id="popupImage" src="" alt="Image">
  <button class="close-btn" onclick="closeImagePopup()">Close</button>
</div>
<!-- IMAGE POPUP (VIEW IMAGE PROOF) -->


<!-- Sales Table -->
<main>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Brand</th>
        <th>Type</th>
        <th>Item</th>
        <th>Flavor</th>
        <th>MOP</th>
        <th>Proof</th>
        <th>Date</th>
        <th>Price</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
  <?php foreach ($sales as $sale): ?>
  <tr>
    <td><?php echo htmlspecialchars($sale['id']); ?></td>
    <td><?php echo htmlspecialchars($sale['brand']); ?></td>
    <td><?php echo htmlspecialchars($sale['type']); ?></td>
    <td><?php echo htmlspecialchars($sale['item']); ?></td>
    <td><?php echo htmlspecialchars($sale['flavor']); ?></td>
    <td><?php echo htmlspecialchars($sale['mop']); ?></td>
    <td>
      <?php if ($sale['proof']): ?>
        <a href="#" class="android-green-link" onclick="showImagePopup('<?php echo base64_encode($sale['proof']); ?>')">View Image</a> <!-- MEDIUM BLOB IN DATABASE -->
      <?php else: ?>
        No Image 
      <?php endif; ?>
    </td>
    <td><?php echo htmlspecialchars($sale['date']); ?></td>
    <td>₱<?php echo number_format($sale['price'], 2); ?></td>
    <td>

      <button class="delete-button" onclick="confirmDelete(<?php echo $sale['id']; ?>)">Delete</button> <!-- DELETE SALE BUTTON -->
    </td>
  </tr>
  <?php endforeach; ?>
</tbody>
  </table>

</main>
<!-- Sales Table -->



  <!-- Delete Confirmation Dialog -->
  <div class="overlay" id="deleteDialog">
  <div class="dialog">
    <h2>Delete Confirmation</h2>
    <p>Are you sure you want to delete this sale?</p>
    <form id="deleteForm" method="post">
      <input type="hidden" name="delete_id" id="deleteId">
      <button type="submit">Yes</button>
      <button type="button" class="close-btn" onclick="closeDeleteDialog()">No</button>
    </form>
  </div>
</div>
<!-- Delete Confirmation Dialog -->



<script>

/* LOGOUT DIALOG SCRIPT */
function showLogoutDialog() {
    document.getElementById('logoutDialog').classList.add('show');
}

function closeLogoutDialog() {
    document.getElementById('logoutDialog').classList.remove('show');
}
/* LOGOUT DIALOG SCRIPT */



/* IMAGE POPUP (VIEW PROOF) SCRIPT */
function showImagePopup(imageData) {
    var popup = document.getElementById('imagePopup');
    var img = document.getElementById('popupImage');
    img.src = 'data:image/jpeg;base64,' + imageData;
    popup.classList.add('show');
}

function closeImagePopup() {
    document.getElementById('imagePopup').classList.remove('show');
}
/* IMAGE POPUP (VIEW PROOF) SCRIPT */



/* DELETE DIALOG SCRIPT */
function confirmDelete(id) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteDialog').classList.add('show');
}

function closeDeleteDialog() {
    document.getElementById('deleteDialog').classList.remove('show');
}
/* DELETE DIALOG SCRIPT */

</script>
</body>
</html>
