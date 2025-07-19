<?php
session_start();
if (!isset($_GET['product_id']) || !isset($_SESSION['cart'][$_GET['product_id']])) {
    header("Location: view_cart.php");
    exit();
}

$productId = $_GET['product_id'];
$product = $_SESSION['cart'][$productId];

// In a real application, this would come from your database
$productImages = [
    '1' => 'images/products/ceramic-mug.jpg',
    '2' => 'images/products/wool-hat.jpg',
    '3' => 'images/products/silver-ring.jpg'
];

// Fallback to placeholder if image doesn't exist
$productImage = isset($productImages[$productId]) && file_exists($productImages[$productId]) 
    ? $productImages[$productId] 
    : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&h=300&q=80';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #5D4037; /* Earthy brown */
            --primary-light: #8D6E63;
            --primary-dark: #3E2723;
            --secondary: #D7CCC8;
            --accent: #C8A97E; /* Warm gold */
            --text-dark: #212121;
            --text-light: #757575;
            --bg-light: #FAF9F5;
        }
        
        body {
            background-color: var(--bg-light);
            font-family: 'Lato', sans-serif;
            color: var(--text-dark);
        }
        
        .product-container {
            max-width: 1000px;
            margin: 3rem auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #EEE;
        }
        
        .product-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1.5rem;
            text-align: center;
            border-bottom: 3px solid var(--accent);
        }
        
        .product-header h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            margin: 0;
            font-size: 1.8rem;
        }
        
        .product-body {
            padding: 2.5rem;
        }
        
        .product-image-container {
            position: relative;
            overflow: hidden;
            border-radius: 6px;
            box-shadow: 0 3px 20px rgba(0,0,0,0.08);
            height: 380px;
            background: #F9F5F0;
            transition: all 0.3s ease;
        }
        
        .product-image-container:hover {
            box-shadow: 0 5px 25px rgba(0,0,0,0.12);
        }
        
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .product-image-container:hover .product-image {
            transform: scale(1.02);
        }
        
        .product-info {
            padding-left: 2rem;
        }
        
        .product-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-dark);
            line-height: 1.2;
        }
        
        .product-price {
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }
        
        .product-price::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 2px;
            background-color: var(--accent);
        }
        
        .product-meta {
            margin-bottom: 2rem;
        }
        
        .product-meta p {
            margin-bottom: 0.8rem;
            font-size: 1rem;
            color: var(--text-light);
        }
        
        .product-meta strong {
            color: var(--text-dark);
            font-weight: 600;
        }
        
        .product-meta i {
            width: 20px;
            color: var(--accent);
            margin-right: 8px;
        }
        
        .rating {
            color: var(--accent);
            margin-right: 8px;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #DDD, transparent);
            margin: 2rem 0;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .quantity-input {
            width: 80px;
            text-align: center;
            margin: 0 1rem;
            padding: 0.6rem;
            border: 1px solid #DDD;
            border-radius: 4px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .quantity-input:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 2px rgba(200, 169, 126, 0.2);
        }
        
        .btn-classic {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-classic i {
            margin-right: 8px;
        }
        
        .btn-classic:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .back-link {
            color: var(--primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin-top: 1.5rem;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .back-link i {
            margin-right: 6px;
            transition: transform 0.3s;
        }
        
        .back-link:hover {
            color: var(--primary-dark);
            text-decoration: none;
        }
        
        .back-link:hover i {
            transform: translateX(-4px);
        }
        
        @media (max-width: 768px) {
            .product-body {
                padding: 1.5rem;
            }
            
            .product-image-container {
                height: 280px;
                margin-bottom: 1.5rem;
            }
            
            .product-info {
                padding-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="product-container">
        <div class="product-header">
            <h1><i class="fas fa-info-circle"></i> Product Details</h1>
        </div>
        
        <div class="product-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-image-container">
                        <img src="<?php echo $productImage . '.png'; ?>" 
                             alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                             class="product-image">
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="product-info">
                        <h2 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h2>
                        <div class="product-price">₹<?php echo number_format($product['price'], 2); ?></div>
                        
                        <div class="product-meta">
                            <p><strong><i class="fas fa-box-open"></i> Current in Cart:</strong> <?php echo $product['quantity']; ?></p>
                            <p><strong><i class="fas fa-tag"></i> Product ID:</strong> <?php echo $productId; ?></p>
                            <p><strong><i class="fas fa-star"></i> Rating:</strong> 
                                <span class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </span> 4.5/5
                            </p>
                        </div>
                        
                        <div class="divider"></div>
                        
                        <div class="quantity-control">
                            <button class="btn btn-light" type="button" onclick="updateQuantity(<?php echo $productId; ?>, 'decrease')">-</button>
                            <input type="number" class="quantity-input" id="quantity-<?php echo $productId; ?>" 
                                   value="<?php echo $product['quantity']; ?>" min="1">
                            <button class="btn btn-light" type="button" onclick="updateQuantity(<?php echo $productId; ?>, 'increase')">+</button>
                        </div>
                        
                        <button class="btn-classic" onclick="window.location.href='view_cart.php'">
                            <i class="fas fa-shopping-cart"></i> Proceed to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="view_cart.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Cart
    </a>

    <script>
        function updateQuantity(productId, action) {
            const quantityInput = document.getElementById('quantity-' + productId);
            let quantity = parseInt(quantityInput.value);
            if (action === 'increase') {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            }
            quantityInput.value = quantity;

            // Update session/cart data
            fetch('update_cart.php', {
                method: 'POST',
                body: JSON.stringify({ product_id: productId, quantity: quantity })
            });
        }
    </script>
</body>
</html>
