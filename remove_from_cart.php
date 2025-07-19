<?php
session_start();

if (!isset($_GET['product_id'])) {
    header("Location: checkout.php");
    exit();
}

$product_id = $_GET['product_id'];

// Remove the product from the cart
if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}

header("Location: checkout.php");
exit();
?>
