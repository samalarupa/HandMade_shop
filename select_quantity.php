<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['product_id'])) {
    header("Location: category.php");
    exit();
}

$product_id = $_GET['product_id'];

// Get product details
include 'config.php';
$conn = new mysqli("localhost", "root", "", "handmade_shop");
$stmt = $conn->prepare("SELECT title, price FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Quantity</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #0a0a0a;
            background-image: radial-gradient(circle at 80% 20%, rgba(0, 80, 40, 0.15) 0%, transparent 40%);
            color: #fff;
            padding: 20px;
        }
        
        .futuristic-container {
            max-width: 500px;
            width: 100%;
            padding: 40px 30px;
            border-radius: 12px;
            background: rgba(10, 10, 10, 0.8);
            border: 1px solid rgba(0, 200, 100, 0.3);
            box-shadow: 0 0 30px rgba(0, 200, 100, 0.15),
                        inset 0 0 15px rgba(0, 200, 100, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .futuristic-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                transparent 30%,
                rgba(0, 200, 100, 0.08) 40%,
                transparent 50%
            );
            transform: rotate(30deg);
            pointer-events: none;
        }
        
        h1 {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.8rem;
            font-weight: 300;
            letter-spacing: 1px;
        }
        
        .product-title {
            position: relative;
            padding-bottom: 15px;
        }
        
        .product-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #00c864, transparent);
        }
        
        .product-price {
            color: #00c864;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.6rem;
            font-weight: 500;
            text-shadow: 0 0 10px rgba(0, 200, 100, 0.3);
        }
        
        form {
            display: flex;
            flex-direction: column;
        }
        
        label {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 12px;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 35px;
            position: relative;
        }
        
        .quantity-control::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 200, 100, 0.3), transparent);
            z-index: -1;
        }
        
        .quantity-btn {
            width: 45px;
            height: 45px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 200, 100, 0.4);
            border-radius: 50%;
            color: #00c864;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .quantity-btn:hover {
            background: rgba(0, 200, 100, 0.15);
            box-shadow: 0 0 15px rgba(0, 200, 100, 0.3);
        }
        
        input[type="number"] {
            width: 100px;
            height: 45px;
            margin: 0 15px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 200, 100, 0.4);
            border-radius: 8px;
            text-align: center;
            color: #fff;
            font-size: 18px;
            font-weight: 400;
        }
        
        input[type="number"]:focus {
            outline: none;
            border-color: #00c864;
            box-shadow: 0 0 10px rgba(0, 200, 100, 0.3);
        }
        
        button[type="submit"] {
            background: rgba(0, 200, 100, 0.15);
            color: #fff;
            border: 1px solid rgba(0, 200, 100, 0.4);
            border-radius: 30px;
            padding: 14px 0;
            font-size: 16px;
            font-weight: 400;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        button[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(0, 200, 100, 0.2),
                transparent
            );
            transition: all 0.6s ease;
        }
        
        button[type="submit"]:hover {
            background: rgba(0, 200, 100, 0.25);
            box-shadow: 0 0 20px rgba(0, 200, 100, 0.2);
        }
        
        button[type="submit"]:hover::before {
            left: 100%;
        }
    </style>
</head>
<body>
    <div class="futuristic-container">
        <h1 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h1>
        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
        
        <form method="POST" action="confirm_order.php">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
            <label>Quantity</label>
            <div class="quantity-control">
                <button type="button" class="quantity-btn" onclick="decrementQuantity()">-</button>
                <input type="number" name="quantity" id="quantity" value="1" min="1" required>
                <button type="button" class="quantity-btn" onclick="incrementQuantity()">+</button>
            </div>

            <button type="submit">Next</button>
        </form>
    </div>

    <script>
        function incrementQuantity() {
            const input = document.getElementById('quantity');
            input.value = parseInt(input.value) + 1 || 1;
        }

        function decrementQuantity() {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value) || 1;
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
    </script>
</body>
</html>