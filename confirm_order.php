<?php
session_start();

if (!isset($_POST['product_id'], $_POST['quantity'])) {
    header("Location: category.php");
    exit();
}

$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);
$address = ""; // Address will be handled in checkout.php

include 'config.php';
$conn = new mysqli("localhost", "root", "", "handmade_shop");

// Get product details
$stmt = $conn->prepare("SELECT title, price FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: category.php?error=Product not found");
    exit();
}

// Initialize cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Update cart
$_SESSION['cart'][$product_id] = [
    'title' => $product['title'],
    'price' => $product['price'],
    'quantity' => $quantity,
    'address' => $address // Empty address for now
];

// Redirect to checkout
header("Location: checkout.php");
exit();
?>
