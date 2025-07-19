<?php
session_start();

// Check if product_id and quantity are set
if (!isset($_POST['product_id'], $_POST['quantity'])) {
    header("Location: category.php");
    exit();
}

$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Connect to the database
include 'config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Fetch product details
$stmt = $conn->prepare("SELECT title, price FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
    // If the cart already contains this product, update the quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity; // Update quantity
    } else {
        // Add new product to cart
        $_SESSION['cart'][$product_id] = [
            'title' => $product['title'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }

    // Redirect back to the category page
    header("Location: category.php?message=Product added to cart");
} else {
    // Product not found
    header("Location: category.php?error=Product not found");
}

exit();
?>
