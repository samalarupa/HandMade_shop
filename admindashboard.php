<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2e3bff;
            --secondary: #00ffe1;
            --dark: #111827;
            --light: #f9fafb;
            --accent: #7e22ce;
            --danger: #ff3a5e;
            --success: #00d170;
            --warning: #ffbb00;
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
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        h2 {
            font-weight: 400;
            font-size: 22px;
            margin: 25px 0 15px;
            color: var(--secondary);
            position: relative;
            padding-left: 15px;
        }
        
        h2::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 20px;
            background: var(--secondary);
            border-radius: 5px;
        }
        
        .panel {
            background: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 25px;
            margin-bottom: 30px;
            position: relative;
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
            min-height: 100px;
        }
        
        input:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--secondary);
            background: rgba(255, 255, 255, 0.08);
        }
        
        input[type="submit"] {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: var(--light);
            cursor: pointer;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            grid-column: 1 / -1;
            max-width: 200px;
            justify-self: end;
            padding: 14px;
        }
        
        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        th {
            background: rgba(0, 0, 0, 0.3);
            padding: 14px 10px;
            text-align: left;
            font-weight: 500;
            color: var(--secondary);
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.2s;
        }
        
        tr:hover td {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            color: var(--light);
            background: rgba(255, 255, 255, 0.1);
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .actions a.edit {
            background: rgba(46, 59, 255, 0.15);
        }
        
        .actions a.delete {
            background: rgba(255, 58, 94, 0.15);
        }
        
        .actions a.edit:hover {
            background: var(--primary);
            transform: translateY(-2px);
        }
        
        .actions a.delete:hover {
            background: var(--danger);
            transform: translateY(-2px);
        }
        
        .empty-table {
            text-align: center;
            padding: 30px;
            color: rgba(255, 255, 255, 0.6);
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            form {
                grid-template-columns: 1fr;
            }
            
            input[type="submit"] {
                max-width: 100%;
            }
            
            .actions {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Admin Dashboard</h1>
            <a href="logout.php" style="color: var(--light); text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </header>

        <div class="panel">
            <h2>Add New Product</h2>
            <form action="add_product.php" method="POST">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" id="product_name" name="product_name" required>
                </div>

                <div class="form-group">
                    <label for="price">Price ($)</label>
                    <input type="text" id="price" name="price" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" required>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <input type="submit" value="Add Product">
            </form>
        </div>

        <div class="panel">
            <h2>Product List</h2>

            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>

                <?php
                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'handmade_shop');
                
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch products from the database
                $sql = "SELECT * FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Loop through each product and display them
                    while($row = $result->fetch_assoc()) {
                        // Check if the required keys exist in the row
                        if (isset($row['title']) && isset($row['description']) && isset($row['price']) && isset($row['stock']) && isset($row['product_id'])) {
                            // Safely output data using htmlspecialchars to prevent XSS
                            $productName = htmlspecialchars($row['title']);
                            $description = htmlspecialchars($row['description']);
                            // Limit description length
                            if (strlen($description) > 100) {
                                $description = substr($description, 0, 97) . '...';
                            }
                            $price = "$" . number_format($row['price'], 2);
                            $stock = $row['stock'];
                            $productId = $row['product_id'];

                            echo "<tr>";
                            echo "<td>" . $productName . "</td>";
                            echo "<td>" . $description . "</td>";
                            echo "<td>" . $price . "</td>";
                            echo "<td>" . $stock . "</td>";
                            echo "<td class='actions'>
                                    <a href='edit_product.php?id=$productId' class='edit' title='Edit'><i class='fas fa-edit'></i></a>
                                    <a href='delete_product.php?id=$productId' class='delete' title='Delete' onclick='return confirm(\"Are you sure you want to delete this product?\")'><i class='fas fa-trash-alt'></i></a>
                                  </td>";
                            echo "</tr>";
                        } else {
                            echo "<tr><td colspan='5' class='empty-table'>Missing data for product.</td></tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='5' class='empty-table'>No products found</td></tr>";
                }

                $conn->close();
                ?>
            </table>
        </div>
    </div>
</body>
</html>