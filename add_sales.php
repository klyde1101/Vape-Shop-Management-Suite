<?php
session_start();

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

// Database connection
$host = 'localhost';
$db = 'business';
$user = 'root'; // Your database username
$pass = ''; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
  // Handle form submission
  $brand = $_POST['brand'];
  $type = $_POST['type'];
  $item = $_POST['item'];
  $flavor = $_POST['flavor'] ?? null;
  $mop = $_POST['mop'];
  $proof = file_get_contents($_FILES['proof']['tmp_name']);
  $date = date('Y-m-d'); // Automatically use the current date

  // Fetch price from price_list table
  $priceQuery = "SELECT price FROM price_list WHERE item = ?";
  $priceStmt = $pdo->prepare($priceQuery);
  $priceStmt->execute([$item]);
  $price = $priceStmt->fetchColumn();

  if ($price === false) {
      echo "Price not found for the specified item.";
      exit;
  }

  $query = "INSERT INTO sales (brand, type, item, flavor, mop, proof, date, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$brand, $type, $item, $flavor, $mop, $proof, $date, $price]);

  echo "Sales record added successfully!";
}
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
<link href="form.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&display=swap');

body {
    background-color: #212121;
    color: #fff;
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
  }
  .submit-btn .dialog button {
    background-color: #DC143C;
    border: none;
    color: white;
    padding: 10px 20px;
    margin-top: 10px;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    width: 40%;
    transition: background 0.3s ease;
    margin: 10px;
}

.close-btn .dialog button {
    background-color: yellow;
    border: none;
    color: white;
    padding: 10px 20px;
    margin-top: 10px;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    width: 40%;
    transition: background 0.3s ease;
    margin: 10px;
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
          <a href="add_expensse.php">Add Expenses</a>
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


<!-- Add Sales Form -->
<main>
  <form id="addSalesForm" action="add_sales.php" method="post" enctype="multipart/form-data">
    <label for="brand">Brand</label>
    <select id="brand" name="brand" required>
      <option value="">Select Brand</option>
      <option value="Black">Black</option>
      <option value="Cigbay">Cigbay</option>
    </select>

    <label for="type">Type</label>
    <select id="type" name="type" required>
      <option value="">Select Type</option>
      <!-- Options will be populated based on brand selection -->
    </select>

    <label for="item">Item</label>
    <select id="item" name="item" required>
      <option value="">Select Item</option>
      <!-- Options will be populated based on type selection -->
    </select>

    <label for="flavor">Flavor</label>
    <select id="flavor" name="flavor">
      <option value="">Select Flavor</option>
      <!-- Options will be populated based on item selection -->
    </select>

    <label for="mop">MOP</label>
    <select id="mop" name="mop" required>
      <option value="Cash">Cash</option>
      <option value="Gcash">Gcash</option>
      <option value="Maya">Maya</option>
    </select>

    <label for="proof">Proof</label>
    <input type="file" id="proof" name="proof" accept="image/*" required>

    <button type="button" class="primary-button" onclick="showConfirmationDialog()">Submit</button>

  </form>
</main>

<!-- Confirmation Dialog -->
<div class="overlay" id="confirmationDialog">
  <div class="dialog">
    <h2>Confirm Your Details</h2>
    <p id="confirmationDetails"></p>
    <form id="confirmForm" action="add_sales.php" method="post" enctype="multipart/form-data">
      <input type="hidden" id="confirmBrand" name="brand">
      <input type="hidden" id="confirmType" name="type">
      <input type="hidden" id="confirmItem" name="item">
      <input type="hidden" id="confirmFlavor" name="flavor">
      <input type="hidden" id="confirmMop" name="mop">
      <input type="hidden" id="confirmDate" name="date">
      <input type="file" id="confirmProof" name="proof" style="display:none;">
      <button type="submit" class="confirm-btn" name="confirm">CONFIRM</button>
      <button type="button" class="close-btn" onclick="closeConfirmationDialog()">Cancel</button>
    </form>
  </div>
</div>

<!-- Logout Confirmation Dialog -->
<div class="overlay" id="logoutDialog">
  <div class="dialog">
    <h2>Logout Confirmation</h2>
    <p>Are you sure you want to logout?</p>
    <form action="home.php" method="post">
    <button type="submit" class="submit-btn" name="logout">Yes</button>
    <button type="button" class="close-btn" onclick="closeLogoutDialog()">No</button>
    </form>
  </div>
</div>

<script>
const brandSelect = document.getElementById('brand');
const typeSelect = document.getElementById('type');
const itemSelect = document.getElementById('item');
const flavorSelect = document.getElementById('flavor');

brandSelect.addEventListener('change', function() {
    updateTypeOptions();
    updateItemOptions();
    updateFlavorOptions();
});

typeSelect.addEventListener('change', function() {
    updateItemOptions();
    updateFlavorOptions();
});

itemSelect.addEventListener('change', function() {
    updateFlavorOptions();
});

function updateTypeOptions() {
    typeSelect.innerHTML = '<option value="">Select Type</option>';
    switch (brandSelect.value) {
        case 'Black':
            typeSelect.innerHTML += '<option value="Black Pod">Pod</option>';
            typeSelect.innerHTML += '<option value="Formula">Formula</option>';
            typeSelect.innerHTML += '<option value="Black Battery">Black Battery</option>';
            break;
        case 'Cigbay':
            typeSelect.innerHTML += '<option value="Cigbay Pod">Cigbay Pod</option>';
            typeSelect.innerHTML += '<option value="Battery">Battery</option>';
            break;
    }
}

function updateItemOptions() {
    itemSelect.innerHTML = '<option value="">Select Item</option>';
    switch (true) {
        case brandSelect.value === 'Black' && typeSelect.value === 'Black Pod':
            itemSelect.innerHTML += '<option value="Elite 8000">Elite 8000</option>';
            itemSelect.innerHTML += '<option value="Elite 12000">Elite 12000</option>';
            break;
        case brandSelect.value === 'Black' && typeSelect.value === 'Formula':
            itemSelect.innerHTML += '<option value="30ML Formula">30ML Formula</option>';
            break;
        case brandSelect.value === 'Black' && typeSelect.value === 'Black Battery':
            itemSelect.innerHTML += '<option value="v1">v1</option>';
            itemSelect.innerHTML += '<option value="v2">v2</option>';
            break;
        case brandSelect.value === 'Cigbay' && typeSelect.value === 'Cigbay Pod':
            itemSelect.innerHTML += '<option value="Cigbay 8000">Cigbay 8000</option>';
            break;
        case brandSelect.value === 'Cigbay' && typeSelect.value === 'Battery':
            itemSelect.innerHTML += '<option value="Cigbay Battery 550Mah">Cigbay Battery 550Mah</option>';
            break;
    }
}

function updateFlavorOptions() {
    flavorSelect.innerHTML = '<option value="">Select Flavor</option>';
    switch (true) {
      case (brandSelect.value === 'Black' && typeSelect.value === 'Black Pod') && (itemSelect.value === 'Elite 8000' || itemSelect.value === 'Elite 12000'):
            flavorSelect.innerHTML += '<option value="Green Tokyo (Matcha)">Green Tokyo (Matcha)</option>';
            flavorSelect.innerHTML += '<option value="Very Baguio (Strawberry)">Very Baguio (Strawberry)</option>';
            flavorSelect.innerHTML += '<option value="Sticky Worms (Gummy Worms)">Sticky Worms (Gummy Worms)</option>';
            flavorSelect.innerHTML += '<option value="Red Pulp (Watermelon)">Red Pulp (Watermelon)</option>';
            flavorSelect.innerHTML += '<option value="Bacteria Monster (Yakult)">Bacteria Monster (Yakult)</option>';
            flavorSelect.innerHTML += '<option value="Yellow Summer (Mango)">Yellow Summer (Mango)</option>';
            flavorSelect.innerHTML += '<option value="Trouble Purple (Grapes)">Trouble Purple (Grapes)</option>';
            flavorSelect.innerHTML += '<option value="Very More (Mixed Berries)">Very More (Mixed Berries)</option>';
            flavorSelect.innerHTML += '<option value="Rainbow Punch (Kool-Aid)">Rainbow Punch (Kool-Aid)</option>';
            flavorSelect.innerHTML += '<option value="Sweet Forest (Green Apple)">Sweet Forest (Green Apple)</option>';
            flavorSelect.innerHTML += '<option value="Yellow Green (Lemon Lime)">Yellow Green (Lemon Lime)</option>';
            flavorSelect.innerHTML += '<option value="Black Wave (Black Currant)">Black Wave (Black Currant)</option>';
            break;

      case (brandSelect.value === 'Black' && typeSelect.value === 'Formula' && itemSelect.value === '30ML Formula'):
            flavorSelect.innerHTML += '<option value="Green Tokyo (Matcha)">Green Tokyo (Matcha)</option>';
            flavorSelect.innerHTML += '<option value="Red Cannon (Bubblegum)">Red Cannon (Bubblegum)</option>';
            flavorSelect.innerHTML += '<option value="Very Monkey (Strawberry Banana)">Very Monkey (Strawberry Banana)</option>';
            flavorSelect.innerHTML += '<option value="Yellow Green (Lemon Lime)">Yellow Green (Lemon Lime)</option>';
            flavorSelect.innerHTML += '<option value="Very Baguio (Strawberry)">Very Baguio (Strawberry)</option>';
            flavorSelect.innerHTML += '<option value="Beer Sparkle (Rootbeer)">Beer Sparkle (Rootbeer)</option>';
            flavorSelect.innerHTML += '<option value="Trouble Purple (Grape)">Trouble Purple (Grape)</option>';
            flavorSelect.innerHTML += '<option value="Red Pulp (Watermelon)">Red Pulp (Watermelon)</option>';
            flavorSelect.innerHTML += '<option value="Sparkle Squeeze (Lemon Cola)">Sparkle Squeeze (Lemon Cola)</option>';
            flavorSelect.innerHTML += '<option value="Blue Freeze (Blueberry)">Blue Freeze (Blueberry)</option>';
            flavorSelect.innerHTML += '<option value="Bacteria Monster (Yakult)">Bacteria Monster (Yakult)</option>';
            flavorSelect.innerHTML += '<option value="Very More (Mixed Berries)">Very More (Mixed Berries)</option>';
            break;
      
      case (brandSelect.value === 'Black' && typeSelect.value === 'Black Battery') && (itemSelect.value === 'v1' || itemSelect.value === 'v2'):
            flavorSelect.innerHTML += '<option value="">Select a flavor</option>';
            break;

            
        case brandSelect.value === 'Cigbay' && typeSelect.value === 'Cigbay Pod' && itemSelect.value === 'Cigbay 8000':
            flavorSelect.innerHTML += '<option value="Papa Bryan B (Strawberry)">Papa Bryan B (Strawberry)</option>';
            flavorSelect.innerHTML += '<option value="Papa Raymond (Bubblegum)">Papa Raymond (Bubblegum)</option>';
            break;
    }
}


function showConfirmationDialog() {
    const confirmationDetails = `
        <p><strong>Brand:</strong> ${brandSelect.value}</p>
        <p><strong>Type:</strong> ${typeSelect.value}</p>
        <p><strong>Item:</strong> ${itemSelect.value}</p>
        <p><strong>Flavor:</strong> ${flavorSelect.value}</p>
        <p><strong>MOP:</strong> ${document.getElementById('mop').value}</p>
        <p><strong>Date:</strong> ${new Date().toISOString().split('T')[0]}</p>
    `;
    document.getElementById('confirmationDetails').innerHTML = confirmationDetails;

    document.getElementById('confirmBrand').value = brandSelect.value;
    document.getElementById('confirmType').value = typeSelect.value;
    document.getElementById('confirmItem').value = itemSelect.value;
    document.getElementById('confirmFlavor').value = flavorSelect.value;
    document.getElementById('confirmMop').value = document.getElementById('mop').value;
    document.getElementById('confirmDate').value = new Date().toISOString().split('T')[0];

    // Copy the file input (cannot directly copy FileList, needs separate handling)
    const proofFile = document.getElementById('proof').files[0];
    const confirmProof = document.getElementById('confirmProof');
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(proofFile);
    confirmProof.files = dataTransfer.files;

    document.getElementById('confirmationDialog').classList.add('show');
}

function closeConfirmationDialog() {
    document.getElementById('confirmationDialog').classList.remove('show');
}

function showLogoutDialog() {
    document.getElementById('logoutDialog').classList.add('show');
  }

  function closeLogoutDialog() {
    document.getElementById('logoutDialog').classList.remove('show');
  }
</script>
</body>
</html>