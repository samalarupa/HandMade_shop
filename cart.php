<?php
session_start();
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty!";
} else {
    echo "<h1>Your Cart</h1>";
    echo "<table border='1'>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>";
    
    $total = 0;
    foreach ($_SESSION['cart'] as $productId => $product) {
        $subtotal = $product['price'] * $product['quantity'];
        $total += $subtotal;

        echo "<tr>
                <td>" . htmlspecialchars($product['product_name']) . "</td>
                <td>$" . number_format($product['price'], 2) . "</td>
                <td>" . $product['quantity'] . "</td>
                <td>$" . number_format($subtotal, 2) . "</td>
            </tr>";
    }

    echo "</table>";
    echo "<br><b>Total: $" . number_format($total, 2) . "</b>";
}
?>


<!-- <a href="cart.php">View Cart</a> -->
