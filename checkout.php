<?php
session_start();
include 'config.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: category.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total = 0;

// Calculate the total price of the cart
foreach ($_SESSION['cart'] as $product) {
    $total += $product['price'] * $product['quantity'];
}

// Handle form submission (confirm order)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = trim($_POST['address']); // Get the address from the form

    if (!empty($address)) {
        // Insert the order into the database

        $order_status = 'Pending'; // Set initial order status
        $created_at = date("Y-m-d H:i:s"); // Current timestamp

        // Insert each item in the cart into the orders table
        foreach ($_SESSION['cart'] as $product_id => $product) {
            $title = $product['title'];
            $price = $product['price'];
            $quantity = $product['quantity'];
            $total_price = $price * $quantity;

            // Insert into orders table
            $stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, product_id, quantity, total_price, order_status, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iiidss", $user_id, $product_id, $quantity, $total_price, $order_status, $created_at);
            mysqli_stmt_execute($stmt);
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);

        // Clear the cart after the order
        unset($_SESSION['cart']);

        // Redirect to the thank you page
        header("Location: order_display.php");
        exit();
    } else {
        // Handle the case where the address is empty
        $error = "Please provide a valid shipping address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #0f172a;
            --secondary: #00c864;
            --light: #f8fafc;
            --dark: #0f172a;
            --accent: #00c864;
            --gray: #94a3b8;
        }

        body {
            background-color: #f1f5f9;
            color: var(--dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .checkout-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .checkout-header {
            position: relative;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .checkout-header h2 {
            color: var(--primary);
            font-weight: 600;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .checkout-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--secondary);
            border-radius: 3px;
        }

        .order-summary {
            margin-bottom: 2rem;
        }

        .order-summary h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .product-table {
            border-radius: 12px;
            overflow: hidden;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .product-table thead {
            background-color: var(--primary);
            color: white;
        }

        .product-table th {
            font-weight: 500;
            padding: 0.8rem 1rem;
            border: none;
        }

        .product-table td {
            padding: 1rem;
            border-color: #e2e8f0;
            vertical-align: middle;
        }

        .product-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .product-price, .product-total {
            font-weight: 500;
            color: var(--primary);
        }

        .product-quantity {
            background-color: #f1f5f9;
            padding: 0.2rem 0.7rem;
            border-radius: 50px;
            display: inline-block;
            font-weight: 500;
        }

        .total-row {
            background-color: #f8fafc !important;
        }

        .total-row td {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .total-amount {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--secondary);
        }

        .shipping-section {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .shipping-section h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.7rem 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(0, 200, 100, 0.1);
        }

        .btn-checkout {
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-checkout:hover {
            background-color: #00b058;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 200, 100, 0.2);
        }

        .btn-checkout .btn-icon {
            margin-right: 0.5rem;
        }

        .checkout-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }

        .continue-shopping {
            color: var(--gray);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .continue-shopping:hover {
            color: var(--primary);
        }

        .continue-shopping i {
            margin-right: 0.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .checkout-container {
                padding: 1.5rem;
            }

            .product-table th, .product-table td {
                padding: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="checkout-container">
            <div class="checkout-header">
                <h2>Checkout</h2>
                <p class="text-secondary mb-0">Complete your purchase</p>
            </div>

            <form method="POST" action="checkout.php">
                <div class="order-summary">
                    <h3><i class="fas fa-shopping-cart me-2"></i> Order Summary</h3>
                    <div class="table-responsive">
                        <table class="table product-table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $productId => $product): ?>
                                    <tr>
                                        <td class="product-name"><?php echo htmlspecialchars($product['title']); ?></td>
                                        <td class="product-price">₹<?php echo number_format($product['price'], 2); ?></td>
                                        <td><span class="product-quantity"><?php echo intval($product['quantity']); ?></span></td>
                                        <td class="product-total">₹<?php echo number_format($product['price'] * $product['quantity'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="total-row">
                                    <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                    <td class="total-amount">₹<?php echo number_format($total, 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="shipping-section">
                    <h3><i class="fas fa-truck me-2"></i> Shipping Information</h3>
                    <div class="form-group mb-3">
                        <label for="address" class="form-label">Shipping Address:</label>
                        <textarea name="address" id="address" rows="3" class="form-control" placeholder="Enter your complete shipping address" required></textarea>
                    </div>
                </div>

                <div class="checkout-footer">
                    <a href="category.php" class="continue-shopping">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                    <button type="submit" class="btn btn-checkout">
                        <i class="fas fa-lock btn-icon"></i> Confirm Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
