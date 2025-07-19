<?php
session_start();

// Check if user is logged in and is a buyer
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: login.php");
    exit;
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
</head>
<body>
    <h2>Your Cart</h2>

    <?php if (isset($_GET['message'])): ?>
        <div><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php elseif (isset($_GET['error'])): ?>
        <div><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $productId => $product):
                    $productId = intval($productId);
                    $totalPrice = floatval($product['price']) * intval($product['quantity']);
                    $total += $totalPrice;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['title']); ?></td>
                        <td>₹<?php echo number_format(floatval($product['price']), 2); ?></td>
                        <td><?php echo intval($product['quantity']); ?></td>
                        <td>₹<?php echo number_format($totalPrice, 2); ?></td>
                        <td>
                            <a href="remove_from_cart.php?product_id=<?php echo $productId; ?>">Remove</a> | 
                            <a href="select_quantity.php?product_id=<?php echo $productId; ?>">Update Quantity</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3">Total Amount:</td>
                    <td>₹<?php echo number_format($total, 2); ?></td>
                </tr>
            </tbody>
        </table>

        <a href="checkout.php">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty!</p>
    <?php endif; ?>
</body>
</html>
