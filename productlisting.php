<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Added Successfully - Handmade Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f0e6da;
            background-image: linear-gradient(to bottom right, #e8dcc7, #d4c3ad);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            color: #614b3a;
        }
        
        .container {
            background: rgba(245, 240, 231, 0.8);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(97, 75, 58, 0.15);
            border: 1px solid rgba(97, 75, 58, 0.1);
            width: 100%;
            max-width: 700px;
            overflow: hidden;
            position: relative;
            text-align: center;
        }
        
        .success-header {
            background: #755c48;
            border-bottom: 1px solid rgba(245, 240, 231, 0.2);
            color: #f5f0e7;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .success-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: 600;
            color: #f5f0e7;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .success-header p {
            color: rgba(245, 240, 231, 0.9);
            font-size: 16px;
        }
        
        .success-content {
            padding: 40px 30px;
            position: relative;
            z-index: 1;
        }
        
        .success-icon {
            font-size: 72px;
            color: #614b3a;
            margin-bottom: 30px;
            text-shadow: 0 0 20px rgba(97, 75, 58, 0.2);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .product-info {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
            border: 1px solid rgba(97, 75, 58, 0.1);
        }
        
        .product-info h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #614b3a;
        }
        
        .product-info p {
            color: #755c48;
            margin-bottom: 5px;
            font-size: 16px;
        }
        
        .stats-container {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        
        .stat-box {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 12px;
            padding: 20px;
            margin: 10px;
            min-width: 150px;
            text-align: center;
            border: 1px solid rgba(97, 75, 58, 0.1);
            transition: all 0.3s ease;
        }
        
        .stat-box:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 20px rgba(97, 75, 58, 0.1);
        }
        
        .stat-box i {
            font-size: 28px;
            margin-bottom: 10px;
            color: #755c48;
        }
        
        .stat-box h4 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #614b3a;
        }
        
        .stat-box p {
            font-size: 24px;
            font-weight: bold;
            color: #614b3a;
        }
        
        .btn {
            background: #755c48;
            color: #f5f0e7;
            border: none;
            border-radius: 8px;
            padding: 15px 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(97, 75, 58, 0.2);
            margin: 10px;
            position: relative;
            overflow: hidden;
            display: inline-block;
            min-width: 200px;
        }
        
        .btn.primary {
            background: #614b3a;
            color: #f5f0e7;
        }
        
        .btn.secondary {
            background: rgba(245, 240, 231, 0.9);
            color: #614b3a;
            border: 1px solid rgba(97, 75, 58, 0.3);
        }
        
        .btn::before {
            content: '';
            position: absolute;
            left: -100%;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(97, 75, 58, 0.2);
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .highlight-box {
            position: absolute;
            top: -80px;
            right: -80px;
            width: 160px;
            height: 160px;
            background: rgba(245, 240, 231, 0.3);
            border-radius: 50%;
            z-index: 0;
        }
        
        .highlight-circle {
            position: absolute;
            bottom: -40px;
            left: -40px;
            width: 80px;
            height: 80px;
            background: rgba(245, 240, 231, 0.3);
            border-radius: 50%;
        }
        
        .accent-line {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, transparent, rgba(97, 75, 58, 0.3), transparent);
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #d4c3ad;
            border-radius: 0;
            animation: fall 5s ease-out infinite;
        }
        
        @keyframes fall {
            0% {
                transform: translateY(-100px) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(1000px) rotate(360deg);
                opacity: 0;
            }
        }
        
        .message {
            font-size: 18px;
            line-height: 1.6;
            color: #614b3a;
            margin-bottom: 30px;
        }
        
        .impact-message {
            font-style: italic;
            color: #755c48;
            margin-top: 30px;
            font-size: 16px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <?php
    // Get product info from the database if needed
    // This is a simplified example - in your actual implementation you might want to retrieve the product details
    
    // Mock data for demonstration purposes
    $product_title = $_GET['title'] ?? 'Your Beautiful Creation';
    $category = $_GET['category'] ?? 'Handcrafted Item';
    $unique_key = $_GET['unique_key'] ?? '';
    
    // Calculate some stats (these would typically come from your database)
    $total_products = 0;
    $potential_views = 0;
    $artisan_level = "Starter";
    
    // Connect to database to get actual stats
    $conn = new mysqli('localhost', 'root', '', 'handmade_shop');
    if (!$conn->connect_error) {
        $user_id = $_SESSION['user_id'] ?? 1; // Fallback to 1 if not set
        
        // Count products by this artisan
        $result = $conn->query("SELECT COUNT(*) as count FROM products WHERE artisan_id = $user_id");
        if ($result && $row = $result->fetch_assoc()) {
            $total_products = $row['count'];
        }
        
        // Set artisan level based on number of products
        if ($total_products > 20) {
            $artisan_level = "Master";
            $potential_views = 1500;
        } elseif ($total_products > 10) {
            $artisan_level = "Expert";
            $potential_views = 750;
        } elseif ($total_products > 5) {
            $artisan_level = "Skilled";
            $potential_views = 400;
        } else {
            $potential_views = 200;
        }
        
        $conn->close();
    }
    ?>
    
    <!-- Create confetti elements -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colors = ['#e8dcc7', '#d4c3ad', '#c0a887', '#9e7e57', '#755c48', '#614b3a'];
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.width = Math.random() * 10 + 5 + 'px';
                confetti.style.height = Math.random() * 10 + 5 + 'px';
                confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
                confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
                confetti.style.animationDelay = Math.random() * 5 + 's';
                
                document.body.appendChild(confetti);
            }
        });
    </script>
    
    <div class="container">
        <div class="highlight-box"></div>
        <div class="highlight-circle"></div>
        
        <div class="success-header">
            <h1>Congratulations!</h1>
            <p>Your product has been successfully added to the marketplace</p>
            <div class="accent-line"></div>
        </div>
        
        <div class="success-content">
            <i class="fas fa-check-circle success-icon"></i>
            
            <div class="message">
                <p>Amazing work! Your craftsmanship is now ready to be discovered by people around the world. Your unique creations help make our marketplace special, and we're thrilled to have your talent on display.</p>
            </div>
            
            <div class="product-info">
                <h3><?php echo htmlspecialchars($product_title); ?></h3>
                <p>Category: <?php echo htmlspecialchars($category); ?></p>
                <?php if(!empty($unique_key)): ?>
                <p>Unique Key: <?php echo htmlspecialchars($unique_key); ?></p>
                <?php endif; ?>
                <p>Status: <span style="color: #614b3a; font-weight: bold;">Active</span></p>
            </div>
            
            <div class="stats-container">
                <div class="stat-box">
                    <i class="fas fa-box"></i>
                    <h4>Total Products</h4>
                    <p><?php echo $total_products; ?></p>
                </div>
                
                <div class="stat-box">
                    <i class="fas fa-eye"></i>
                    <h4>Potential Views</h4>
                    <p><?php echo $potential_views; ?>+</p>
                </div>
                
                <div class="stat-box">
                    <i class="fas fa-star"></i>
                    <h4>Artisan Level</h4>
                    <p><?php echo $artisan_level; ?></p>
                </div>
            </div>
            
            <div class="impact-message">
                "Every handmade product tells a story and carries the passion of its creator. Yours is now part of our growing community of unique creations."
            </div>
            
            <div style="margin-top: 40px;">
                <a href="get_ur_products.php" class="btn primary">
                    <i class="fas fa-boxes" style="margin-right: 8px;"></i>
                    View My Products
                </a>
                
                <a href="addproduct.php" class="btn secondary">
                    <i class="fas fa-plus-circle" style="margin-right: 8px;"></i>
                    Add Another Product
                </a>
            </div>
        </div>
    </div>
</body>
</html>