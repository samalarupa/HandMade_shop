<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product - Handmade Shop</title>
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
        }
        
        .form-header {
            background: #755c48;
            border-bottom: 1px solid rgba(245, 240, 231, 0.2);
            color: #f5f0e7;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .form-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
            color: #f5f0e7;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .form-header p {
            color: rgba(245, 240, 231, 0.9);
            font-size: 16px;
        }
        
        .form-content {
            padding: 30px;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        
        .form-column {
            flex: 1;
            padding: 0 15px;
            min-width: 250px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: #614b3a;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #755c48;
            transition: all 0.3s ease;
        }
        
        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(97, 75, 58, 0.2);
            border-radius: 8px;
            font-size: 16px;
            color: #614b3a;
            transition: all 0.3s ease;
        }
        
        textarea.form-control {
            height: 120px;
            resize: none;
            font-family: inherit;
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.9);
            border-color: #755c48;
            box-shadow: 0 0 15px rgba(97, 75, 58, 0.1);
            outline: none;
        }
        
        .form-control:focus + i {
            color: #614b3a;
        }
        
        .form-control::placeholder {
            color: rgba(97, 75, 58, 0.5);
        }
        
        .file-upload {
            position: relative;
            display: block;
            background: rgba(255, 255, 255, 0.7);
            border: 2px dashed rgba(97, 75, 58, 0.3);
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .file-upload:hover {
            background: rgba(255, 255, 255, 0.9);
            border-color: #755c48;
        }
        
        .file-upload input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
        
        .file-upload i {
            font-size: 48px;
            color: #755c48;
            margin-bottom: 15px;
            display: block;
        }
        
        .file-upload span {
            color: #614b3a;
            font-size: 16px;
            font-weight: 500;
        }
        
        .file-upload p {
            color: rgba(97, 75, 58, 0.7);
            font-size: 14px;
            margin-top: 10px;
        }
        
        #preview-image {
            max-width: 100%;
            max-height: 200px;
            margin-top: 15px;
            border-radius: 6px;
            display: none;
        }
        
        .btn {
            background: #614b3a;
            color: #f5f0e7;
            border: none;
            border-radius: 8px;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(97, 75, 58, 0.2);
            position: relative;
            overflow: hidden;
            margin-top: 20px;
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
            background: #755c48;
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
        
        .card-form {
            position: relative;
            z-index: 1;
        }
        
        .success-message {
            padding: 15px;
            background-color: rgba(97, 75, 58, 0.1);
            border: 1px solid rgba(97, 75, 58, 0.3);
            color: #614b3a;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            display: none;
        }
    </style>
</head>
<body>
    <?php
    // session_start();
    
    $success_message = '';
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get product details
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category = $_POST['category'];
        $image = $_FILES['image']['name'];
        $target = "images/" . basename($image);
        $unique_key = $_POST['unique_key'];  // Path to store images

        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        // Connect to database
        $conn = new mysqli('localhost', 'root', '', 'handmade_shop');
        $user_id = $_SESSION['user_id'] ?? 1; // Fallback to 1 if not set

        $stmt = $conn->prepare("INSERT INTO products (artisan_id, title, description, price, image, stock, category, unique_key) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdisss", $artisan_id, $title, $description, $price, $image, $stock, $category, $unique_key);

        if ($stmt->execute()) {
            // Redirect to success page with product info
            header("Location: success.php?title=" . urlencode($title) . "&category=" . urlencode($category) . "&unique_key=" . urlencode($unique_key));
            exit();
        } else {
            echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
    
    <div class="container">
        <div class="highlight-box"></div>
        <div class="highlight-circle"></div>
        
        <div class="form-header">
            <h1>Add New Product</h1>
            <p>Share your handmade creation with the world</p>
            <div class="accent-line"></div>
        </div>
        
        <div class="form-content">
            <div id="success-message" class="success-message">
                <?php echo $success_message; ?>
            </div>
            <form method="POST" action="" enctype="multipart/form-data" class="card-form">
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="title">Product Title</label>
                            <div class="input-group">
                                <input type="text" id="title" name="title" class="form-control" required placeholder="Enter product name">
                                <i class="fas fa-tag"></i>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Product Description</label>
                            <div class="input-group">
                                <textarea id="description" name="description" class="form-control" required placeholder="Describe your product"></textarea>
                                <i class="fas fa-align-left" style="top: 25px;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-column">
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <div class="file-upload">
                                <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(this)">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Upload Image</span>
                                <p>Drag & drop or click to browse</p>
                                <img id="preview-image" src="#" alt="Preview">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="price">Price ($)</label>
                            <div class="input-group">
                                <input type="number" id="price" name="price" step="0.01" class="form-control" required placeholder="0.00">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-column">
                        <div class="form-group">
                            <label for="stock">Stock Available</label>
                            <div class="input-group">
                                <input type="number" id="stock" name="stock" min="1" class="form-control" required placeholder="1">
                                <i class="fas fa-cubes"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-column">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <div class="input-group">
                                <input type="text" id="category" name="category" class="form-control" required placeholder="e.g. Jewelry, Pottery">
                                <i class="fas fa-list"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unique Key input -->
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="unique_key">Unique Key</label>
                            <div class="input-group">
                                <input type="text" id="unique_key" name="unique_key" class="form-control" required placeholder="e.g. summer2025, bohoBatch1">
                                <i class="fas fa-key"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-plus-circle" style="margin-right: 8px;"></i>
                    Add Product
                </button>
            </form>
        </div>
    </div>
    
    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview-image');
            const fileUpload = input.parentElement;
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    fileUpload.querySelector('i').style.display = 'none';
                    fileUpload.querySelector('span').textContent = input.files[0].name;
                    fileUpload.querySelector('p').style.display = 'none';
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>