<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisan Dashboard - Authentic Goods</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f0e9e2;
            color: #5d4037;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .hero {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, #e6dfd5 0%, #d1c7ba 100%);
            border-radius: 15px;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(93, 64, 55, 0.1);
        }
        
        h1 {
            font-size: 42px;
            margin-bottom: 10px;
            color: #5d4037;
        }
        
        .subtitle {
            font-size: 18px;
            color: #5d4037;
            opacity: 0.8;
            max-width: 700px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }
        
        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-block;
            padding: 16px 32px;
            font-size: 18px;
            background-color: #7d5e4f;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-weight: 600;
            min-width: 220px;
            text-align: center;
        }
        
        .btn:hover {
            background-color: #5d4037;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(93, 64, 55, 0.2);
        }
        
        .btn-secondary {
            background-color: #a98774;
        }
        
        .btn-secondary:hover {
            background-color: #8d6e5d;
        }
        
        .benefits {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            margin-top: 60px;
            flex-wrap: wrap;
        }
        
        .benefit-card {
            flex: 1;
            min-width: 300px;
            background-color: #e6dfd5;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(93, 64, 55, 0.1);
            text-align: center;
        }
        
        .benefit-icon {
            font-size: 36px;
            color: #7d5e4f;
            margin-bottom: 15px;
        }
        
        .benefit-title {
            font-size: 20px;
            margin-bottom: 10px;
            color: #5d4037;
        }
        
        .benefit-text {
            color: #5d4037;
            opacity: 0.8;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero">
            <h1>Welcome, Talented Artisan</h1>
            <p class="subtitle">Share your authentic handcrafted creations with the world. Your unique skills and craftsmanship deserve to be discovered and celebrated.</p>
            
            <div class="buttons">
                <a href="get_ur_products.php" class="btn">See Your Collection</a>
                <a href="addproduct.php" class="btn btn-secondary">Add New Product</a>
            </div>
        </div>
        
        <div class="benefits">
            <div class="benefit-card">
                <div class="benefit-icon">📈</div>
                <h3 class="benefit-title">Reach More Customers</h3>
                <p class="benefit-text">Connect with passionate buyers who value authentic, handmade goods and appreciate your craftsmanship.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">💰</div>
                <h3 class="benefit-title">Fair Compensation</h3>
                <p class="benefit-text">Set your own prices and receive fair payment for your unique handcrafted items.</p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">🌍</div>
                <h3 class="benefit-title">Global Visibility</h3>
                <p class="benefit-text">Showcase your craftsmanship to customers worldwide and grow your artisan business.</p>
            </div>
        </div>
    </div>
</body>
</html>