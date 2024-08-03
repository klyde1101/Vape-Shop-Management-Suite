<?php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $brand = $_POST['brand'];
    $type = $_POST['type'];
    $item = $_POST['item'];
    $flavor = $_POST['flavor'];
    $mop = $_POST['mop'];
    $proof = $_POST['proof'];
    $date = $_POST['date'];
    $price = $_POST['price'];

    $query = "UPDATE sales SET brand = :brand, type = :type, item = :item, flavor = :flavor, mop = :mop, proof = :proof, date = :date, price = :price WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':brand', $brand);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':item', $item);
    $stmt->bindParam(':flavor', $flavor);
    $stmt->bindParam(':mop', $mop);
    $stmt->bindParam(':proof', $proof);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
