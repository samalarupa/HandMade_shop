<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'handmade_shop');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if product ID is provided via GET request
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch the product from the database by matching the 'product_id' (or correct column name)
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId); // Correct binding for 'product_id'
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found!";
        exit;
    }
} else {
    echo "Invalid product ID!";
    exit;
}

// Update the product if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize the POST data to prevent SQL injection
    $productName = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $stock = isset($_POST['stock']) ? $_POST['stock'] : '';

    // Update the product in the database
    $updateSql = "UPDATE products SET title = ?, description = ?, price = ?, stock = ? WHERE product_id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssdii", $productName, $description, $price, $stock, $productId); // Correct binding for all parameters

    if ($updateStmt->execute()) {
        $success = true;
    } else {
        $error = "Error updating product: " . $updateStmt->error;
    }
    $updateStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2e3bff;
            --secondary: #00ffe1;
            --dark: #111827;
            --light: #f9fafb;
            --success: #00d170;
            --danger: #ff3a5e;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--dark), #1f2937);
            min-height: 100vh;
            color: var(--light);
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .container {
            width: 100%;
            max-width: 800px;
            margin: 30px auto;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 800px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        h1 {
            font-weight: 300;
            font-size: 32px;
            background: linear-gradient(to right, var(--light), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 1px;
        }
        
        .header-actions {
            display: flex;
            gap: 15px;
        }
        
        .header-actions a {
            color: var(--light);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 0.8;
            transition: all 0.2s;
        }
        
        .header-actions a:hover {
            opacity: 1;
        }
        
        .panel {
            background: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            width: 100%;
            overflow: hidden;
        }
        
        .panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }
        
        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 5px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        input, textarea {
            width: 100%;
            padding: 12px 16px;
            border: none;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            color: var(--light);
            font-size: 16px;
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        textarea {
            resize: vertical;
            min-height: 150px;
            grid-column: 1 / -1;
        }
        
        input:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--secondary);
            background: rgba(255, 255, 255, 0.08);
        }
        
        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 15px;
        }
        
        input[type="submit"], .btn {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: var(--light);
            border: none;
            border-radius: 8px;
            padding: 14px 28px;
            cursor: pointer;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
        }
        
        input[type="submit"]:hover, .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        
        .message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
            animation: slideIn 0.3s ease-out, fadeOut 0.5s ease-out 2s forwards;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 100;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        
        .success-message {
            background: rgba(0, 209, 112, 0.2);
            border: 1px solid var(--success);
            color: var(--success);
        }
        
        .error-message {
            background: rgba(255, 58, 94, 0.2);
            border: 1px solid var(--danger);
            color: var(--danger);
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; visibility: hidden; }
        }
        
        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .panel {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Product</h1>
        <div class="header-actions">
            <a href="admindashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </header>
    
    <div class="container">
        <div class="panel">
            <form action="edit_product.php?id=<?php echo $productId; ?>" method="POST">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['title']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="price">Price ($)</label>
                    <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="form-actions">
                    <a href="admindashboard.php" class="btn btn-cancel"><i class="fas fa-times"></i> Cancel</a>
                    <input type="submit" value="Update Product">
                </div>
            </form>
        </div>
    </div>
    
    <?php if (isset($success) && $success): ?>
    <div class="message success-message">
        <i class="fas fa-check-circle"></i> Product updated successfully!
    </div>
    <script>
        setTimeout(function() {
            window.location.href = 'admindashboard.php';
        }, 2000);
    </script>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="message error-message">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
    </div>
    <?php endif; ?>
</body>
</html>