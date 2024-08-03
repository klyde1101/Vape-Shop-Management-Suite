<?php
include 'db.php';

// Fetch expenses data
$sql = "SELECT * FROM expenses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses</title>
    <link href="header.css" rel="stylesheet">
    <link href="sales_table.css" rel="stylesheet">
<link href="confirmation_dialog.css" rel="stylesheet">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&display=swap');

        body {
            display: relative;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
            font-family: Arial, sans-serif;
 
            background-color: #212121;
        }
        table {
            border-collapse: collapse;
            width: 60%;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 3%;
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

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Date</th>
                <th>Cost</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td class='peso-sign'>₱" . $row['cost'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No expenses found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
