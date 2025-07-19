<?php
session_start();
include 'config.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Query to fetch orders for the logged-in user (removed order_status)
$sql = "SELECT o.order_id, o.product_id, o.quantity, o.total_price, o.created_at, p.title 
        FROM orders o
        JOIN products p ON o.product_id = p.product_id
        WHERE o.user_id = ? ORDER BY o.created_at DESC"; 

try {
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        $no_orders_message = "You haven't placed any orders yet.";
    }
} catch (Exception $e) {
    $error_message = "An error occurred: " . htmlspecialchars($e->getMessage());
} finally {
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}

// Function to calculate delivery date (e.g., 5 days after order date)
function getDeliveryDate($orderDate) {
    $date = new DateTime($orderDate);
    $date->modify('+5 days');
    return $date->format('d M Y');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F0E9E0; /* Beige background */
            font-family: 'Arial', sans-serif;
            transition: background-color 0.3s ease;
        }

        .order-container {
            background-color: white;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(107, 74, 47, 0.2);
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .order-header h2 {
            font-size: 2.2rem;
            font-weight: bold;
            color: #6B4A2F;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #6B4A2F;
            padding-bottom: 0.5rem;
        }

        .breadcrumbs {
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .breadcrumbs a {
            color: #6B4A2F;
            text-decoration: none;
            margin-right: 0.5rem;
        }

        .breadcrumbs a:hover {
            text-decoration: underline;
            color: #4A2F1F;
        }

        .breadcrumbs span {
            color: #6B4A2F;
        }

        .order-table th {
            background-color: #6B4A2F;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 1rem;
            transition: background-color 0.3s ease;
        }

        .order-table th:hover {
            background-color: #4A2F1F;
        }

        .order-table td {
            padding: 1rem;
            border-bottom: 1px solid #F0E9E0;
            transition: background-color 0.3s ease;
        }

        .order-table tr:hover td {
            background-color: #F9F1E9;
        }

        .order-footer {
            margin-top: 2rem;
            text-align: center;
        }

        .continue-shopping {
            display: inline-block;
            padding: 0.8rem 2rem;
            background-color: #6B4A2F;
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .continue-shopping:hover {
            background-color: #4A2F1F;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(107, 74, 47, 0.3);
        }

        .payment-info {
            margin-top: 1.5rem;
            padding: 1rem;
            background-color: #F9F1E9;
            border-radius: 8px;
            color: #6B4A2F;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="order-container">
        <div class="order-header">
            <h2>Your Orders</h2>
            <div class="breadcrumbs">
                <a href="home.html">Home</a> <span>></span> <a href="category.php">Categories</a> <span>></span> Your Orders
            </div>
        </div>

        <?php if (isset($error_message)): ?>
            <p class="text-danger"><?php echo $error_message; ?></p>
        <?php elseif (isset($no_orders_message)): ?>
            <p><?php echo $no_orders_message; ?></p>
        <?php else: ?>
            <table class="table order-table">
                <thead>
                    <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Delivery Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td>₹<?php echo number_format($row['total_price'], 2); ?></td>
                            <td><?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
                            <td><?php echo getDeliveryDate($row['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php mysqli_free_result($result); ?>
        <?php endif; ?>

        <div class="payment-info">
            <p><strong>Payment Information:</strong> All payments are securely processed at checkout using credit/debit cards, UPI, or popular wallets like Paytm and PhonePe. Please ensure your payment is completed before the order is confirmed. For any issues, contact our support team.</p>
        </div>

        <div class="order-footer">
            <a href="category.php" class="continue-shopping">Continue Shopping</a>
        </div>
    </div>
</div>

</body>
</html>