<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['product_id'], $_POST['quantity'], $_POST['total'], $_POST['address'])) {
    header("Location: collection.php?error=Missing order data");
    exit();
}

$productId = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];
$total = (float)$_POST['total'];
$address = trim($_POST['address']);
$userId = $_SESSION['user_id'];

include 'config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, address, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
$stmt->bind_param("iiids", $userId, $productId, $quantity, $total, $address);

if ($stmt->execute()) {
    $orderId = $stmt->insert_id;
    $message = "Order #$orderId placed successfully!";
} else {
    $message = "Order failed. Please try again.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
<div class="container mt-5 text-center">
    <div class="card p-5 bg-secondary">
        <h2><?php echo $message; ?></h2>
        <a href="collection.php" class="btn btn-warning mt-3">Continue Shopping</a>
    </div>
</div>
</body>
</html>
