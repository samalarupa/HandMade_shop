<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'handmade_shop');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$products = [];
$error_message = "";
$success = false;
$debug_info = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unique_key = trim($_POST['unique_key']);
    $username = $_SESSION['username'];
    
    // Debug info
    $debug_info[] = "Searching for products with key: " . $unique_key;
    $debug_info[] = "Username: " . $username;

    // Get artisan's user_id from username
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    if (!$stmt) {
        $error_message = "Prepare failed: " . $conn->error;
        $debug_info[] = $error_message;
    } else {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $artisan_id = $row['user_id'];
            $stmt->close();
            
            $debug_info[] = "Artisan ID found: " . $artisan_id;
            
            // First try: Direct match with unique_key
            $query = "SELECT * FROM products WHERE artisan_id = ? AND unique_key = ?";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                $error_message = "Prepare failed: " . $conn->error;
                $debug_info[] = $error_message;
            } else {
                $stmt->bind_param("is", $artisan_id, $unique_key);
                $stmt->execute();
                $result = $stmt->get_result();
                $debug_info[] = "Direct query executed: " . $query;
                $debug_info[] = "Number of products found (direct match): " . $result->num_rows;
                
                if ($result->num_rows > 0) {
                    $success = true;
                    while ($row = $result->fetch_assoc()) {
                        $products[] = $row;
                    }
                } else {
                    // Second try: LIKE query to handle potential whitespace or partial matches
                    $stmt->close();
                    $like_key = "%" . $unique_key . "%";
                    $query = "SELECT * FROM products WHERE artisan_id = ? AND unique_key LIKE ?";
                    $stmt = $conn->prepare($query);
                    if (!$stmt) {
                        $error_message = "Prepare failed: " . $conn->error;
                        $debug_info[] = $error_message;
                    } else {
                        $stmt->bind_param("is", $artisan_id, $like_key);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $debug_info[] = "LIKE query executed: " . $query;
                        $debug_info[] = "Number of products found (LIKE match): " . $result->num_rows;
                        
                        if ($result->num_rows > 0) {
                            $success = true;
                            while ($row = $result->fetch_assoc()) {
                                $products[] = $row;
                            }
                        }
                    }
                }
                
                // If still no products found, try one more alternative
                if (empty($products)) {
                    // Third try: Ignore user_id restriction (in case it's incorrectly stored)
                    $stmt->close();
                    $query = "SELECT * FROM products WHERE unique_key = ?";
                    $stmt = $conn->prepare($query);
                    if (!$stmt) {
                        $error_message = "Prepare failed: " . $conn->error;
                        $debug_info[] = $error_message;
                    } else {
                        $stmt->bind_param("s", $unique_key);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $debug_info[] = "Query without artisan_id executed: " . $query;
                        $debug_info[] = "Number of products found (without artisan_id): " . $result->num_rows;
                        
                        if ($result->num_rows > 0) {
                            $success = true;
                            while ($row = $result->fetch_assoc()) {
                                $products[] = $row;
                            }
                        } else {
                            // Fourth try: Check the entire products table
                            $stmt->close();
                            $query = "SELECT COUNT(*) as total FROM products";
                            $result = $conn->query($query);
                            $row = $result->fetch_assoc();
                            $debug_info[] = "Total products in database: " . $row['total'];
                            
                            // Show example of product data
                            $query = "SELECT * FROM products LIMIT 3";
                            $result = $conn->query($query);
                            $debug_info[] = "Sample products in database:";
                            while ($row = $result->fetch_assoc()) {
                                $debug_info[] = "ID: " . $row['id'] . ", Title: " . $row['title'] . 
                                               ", Unique key: '" . $row['unique_key'] . "', Artisan ID: " . $row['artisan_id'];
                            }
                            
                            $error_message = "No products found with key: " . $unique_key;
                        }
                    }
                }
                
                if (!empty($stmt)) {
                    $stmt->close();
                }
            }
        } else {
            $error_message = "Artisan account not found.";
            $debug_info[] = $error_message;
            
            // Additional debugging - check if the username exists but with a different user_type
            $stmt->close();
            $query = "SELECT username, user_type FROM users WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $debug_info[] = "User exists but with user_type: " . $row['user_type'];
            } else {
                $debug_info[] = "Username does not exist in the database.";
            }
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Products - Authentic Goods</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, #e6dfd5 0%, #d1c7ba 100%);
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(93, 64, 55, 0.1);
        }
        
        h1 {
            font-size: 36px;
            margin-bottom: 10px;
            color: #5d4037;
        }
        
        .search-form {
            max-width: 600px;
            margin: 0 auto 40px;
            background-color: #e6dfd5;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(93, 64, 55, 0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #5d4037;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #d1c7ba;
            border-radius: 6px;
            font-size: 16px;
            background-color: #fff;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus {
            border-color: #7d5e4f;
            outline: none;
        }
        
        .btn {
            display: inline-block;
            padding: 14px 28px;
            font-size: 16px;
            background-color: #7d5e4f;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
        }
        
        .btn:hover {
            background-color: #5d4037;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(93, 64, 55, 0.2);
        }
        
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #a98774;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background-color: #8d6e5d;
            transform: translateY(-2px);
        }
        
        .message {
            background-color: #fbe9e7;
            color: #c62828;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(93, 64, 55, 0.1);
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(93, 64, 55, 0.15);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-title {
            font-size: 18px;
            font-weight: 600;
            color: #5d4037;
            margin-bottom: 8px;
        }
        
        .product-description {
            color: #5d4037;
            opacity: 0.8;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .product-price {
            font-size: 20px;
            font-weight: 600;
            color: #7d5e4f;
        }
        
        .no-products {
            text-align: center;
            padding: 30px;
            background-color: #e6dfd5;
            border-radius: 10px;
            font-size: 18px;
        }
        
        .debug-section {
            margin-top: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(93, 64, 55, 0.1);
        }
        
        .debug-title {
            font-size: 20px;
            margin-bottom: 15px;
            color: #5d4037;
            font-weight: 600;
        }
        
        .debug-info {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 14px;
            white-space: pre-wrap;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>My Unique Products</h1>
            <p>Find all your handcrafted items by entering your unique key</p>
        </div>
        
        <div class="search-form">
            <form method="POST">
                <div class="form-group">
                    <label for="unique_key">Enter your unique key:</label>
                    <input type="text" id="unique_key" name="unique_key" value="<?php echo isset($_POST['unique_key']) ? htmlspecialchars($_POST['unique_key']) : ''; ?>" placeholder="Your unique product key" required>
                </div>
                <button type="submit" class="btn">Find My Products</button>
            </form>
            <div style="text-align: center; margin-top: 15px;">
                <a href="artisan_dashboard.php" class="back-btn">Back to Dashboard</a>
            </div>
        </div>
        
        <?php if (!empty($error_message)): ?>
            <div class="message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success && !empty($products)): ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <?php if(!empty($product['image']) && file_exists("images/" . $product['image'])): ?>
                            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="product-image">
                        <?php else: ?>
                            <div class="product-image" style="background-color: #d1c7ba; display: flex; align-items: center; justify-content: center;">
                                <span>No Image Available</span>
                            </div>
                        <?php endif; ?>
                        <div class="product-info">
                            <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="product-price">₹<?php echo number_format($product['price'], 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error_message)): ?>
            <div class="no-products">
                <p>No products found for this unique key.</p>
            </div>
        <?php endif; ?>
        
        <!-- Debug Section - Remove this in production -->
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <div class="debug-section">
            <h3 class="debug-title">Debug Information</h3>
            <div class="debug-info">
                <?php foreach ($debug_info as $info): ?>
                    <?php echo htmlspecialchars($info); ?><br>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>