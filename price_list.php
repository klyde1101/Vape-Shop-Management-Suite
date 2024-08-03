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

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM price_list WHERE id=$id");
}

// Handle update request
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $brand = $_POST['brand'];
    $type = $_POST['type'];
    $item = $_POST['item'];
    $price = $_POST['price'];
    $conn->query("UPDATE price_list SET brand='$brand', type='$type', item='$item', price='$price' WHERE id=$id");
}

// Handle add request
if (isset($_POST['add'])) {
    $brand = $_POST['brand'];
    $type = $_POST['type'];
    $item = $_POST['item'];
    $price = $_POST['price'];
    $conn->query("INSERT INTO price_list (brand, type, item, price) VALUES ('$brand', '$type', '$item', '$price')");
}

// Fetch price list
$result = $conn->query("SELECT * FROM price_list");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price List</title>
    <link href="header.css" rel="stylesheet">
    <link href="sales_table.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&display=swap');

        body {
            display: relative;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
            font-family: "Red Hat Display", sans-serif;
            background-color: #212121;
        }
        .table-container {
            text-align: center; /* Center table contents */
        }

        table {
            width: 100%;
            max-width: 1200px;
            border-collapse: collapse;
            margin-top: 3%; /* Center the table within its container */
        }

        table, th, td {
            border: 1px solid #333;
        }

        th, td {
            padding: 6px 12px;
            text-align: center; /* Center contents */
        }

        th {
            background-color: #333;
        }

        td {
            background-color: #424242;
        }

        tr:nth-child(even) {
            background-color: #333;
        }

        tr:hover {
            background-color: #555;
        }

        th:last-child, td:last-child {
        width: 20%; /* Adjust this value as needed */
    }

        .delete-button {
            background-color: #DC143C; 
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 4px;
            text-decoration: none;
        }

        .delete-button:hover {
            background-color: #E32636;
        }

        .edit-button {
            background-color: #4682B4; 
            color: white;
            border: none;
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 4px;
            margin-right: 10px;
        }

        .edit-button:hover {
            background-color: #7CB9E8;
        }
    </style>
    <script>
        function showModal(id, type, item, price) {
            const modal = document.getElementById('modal');
            const form = document.getElementById('edit-form');
            if (id) {
                form.style.display = 'block';
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-type').value = type;
                document.getElementById('edit-item').value = item;
                document.getElementById('edit-price').value = price;
            } else {
                form.style.display = 'none';
            }
            modal.style.display = 'flex';
        }

        function hideModal() {
            document.getElementById('modal').style.display = 'none';
        }
    </script>
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

<!-- Price List Table -->
<table>
    <tr>
        <th>ID</th>
        <th>Brand</th>
        <th>Type</th>
        <th>Item</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['brand']; ?></td>
            <td><?php echo $row['type']; ?></td>
            <td><?php echo $row['item']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td>
                <button class="edit-button" onclick="showModal(<?php echo $row['id']; ?>, '<?php echo $row['type']; ?>', '<?php echo $row['item']; ?>', '<?php echo $row['price']; ?>')">Edit</button>
                <a href="?delete=<?php echo $row['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>

<script>
    
</script>

</body>
</html>

<?php
$conn->close();
?>