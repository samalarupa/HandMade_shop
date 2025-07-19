<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['category'])) {
    header("Location: category.php");
    exit();
}

$category = $_GET['category'];
include 'config.php';
$conn = new mysqli("localhost", "root", "", "handmade_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize filter variables with default values
$maxPrice = 10000;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price-asc';
$subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : 'all';
$priceRange = isset($_GET['price']) ? (int)$_GET['price'] : $maxPrice;

// Prepare the base SQL query
$sql = "SELECT product_id, title, description, price, image FROM products WHERE category = ?";
$params = [$category];
$types = "s";

// Add price filter to query
$sql .= " AND price <= ?";
$params[] = $priceRange;
$types .= "d";

// Add subcategory filter if not 'all'
if ($subcategory !== 'all') {
    $sql .= " AND title LIKE ?";
    $params[] = "%$subcategory%";
    $types .= "s";
}

// Add sorting
switch ($sort) {
    case 'price-asc':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price-desc':
        $sql .= " ORDER BY price DESC";
        break;
    case 'name-asc':
        $sql .= " ORDER BY title ASC";
        break;
    case 'name-desc':
        $sql .= " ORDER BY title DESC";
        break;
    default:
        $sql .= " ORDER BY price ASC";
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Get max price in database for range slider
$maxPriceQuery = $conn->prepare("SELECT MAX(price) as max_price FROM products WHERE category = ?");
$maxPriceQuery->bind_param("s", $category);
$maxPriceQuery->execute();
$maxPriceResult = $maxPriceQuery->get_result();
if ($row = $maxPriceResult->fetch_assoc()) {
    $maxPrice = ceil($row['max_price']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category); ?> Collection - Handmade Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #F0E9E0; /* Cream background */
            --primary: #6B4A2F; /* Warm brown */
            --primary-light: #8B6F47; /* Lighter brown */
            --primary-dark: #4A3723; /* Darker brown */
            --text-primary: #4A3723; /* Dark brown text */
            --text-secondary: #6B4A2F; /* Medium brown text */
            --text-muted: #8B6F47; /* Light brown text */
            --card-bg: #FFFFFF; /* White for cards */
            --border-color: #D9D1C7; /* Light cream border */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lora', serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Navigation Bar */
        .navbar {
            background-color: var(--card-bg);
            padding: 15px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .navbar-logo i {
            color: var(--primary);
            font-size: 24px;
            margin-right: 10px;
        }

        .navbar-logo span {
            color: var(--text-primary);
            font-size: 20px;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
        }

        .navbar-menu {
            display: flex;
            list-style: none;
        }

        .navbar-item {
            margin-left: 25px;
        }

        .navbar-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .navbar-link:hover {
            color: var(--primary);
        }

        .navbar-link.active {
            color: var(--primary);
            font-weight: bold;
        }

        .nav-icon {
            margin-right: 5px;
        }

        .navbar-icons {
            display: flex;
            align-items: center;
        }

        .navbar-icon {
            color: var(--text-secondary);
            font-size: 18px;
            margin-left: 20px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .navbar-icon:hover {
            color: var(--primary);
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--primary);
            color: var(--card-bg);
            font-size: 12px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hamburger {
            display: none;
            cursor: pointer;
            font-size: 24px;
            color: var(--text-primary);
        }

        /* Breadcrumbs */
        .breadcrumbs {
            font-size: 0.9em;
            color: var(--text-muted);
            margin: 15px 0;
            padding: 0 15px;
        }

        .breadcrumbs ul {
            list-style: none;
            display: flex;
            align-items: center;
            padding: 0;
            gap: 8px;
        }

        .breadcrumbs li {
            display: inline-flex;
            align-items: center;
        }

        .breadcrumbs a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumbs a:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .breadcrumbs li:not(:last-child)::after {
            content: "›";
            margin-left: 8px;
            color: var(--text-muted);
        }

        .breadcrumbs li:last-child {
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Main Layout */
        .main-container {
            display: flex;
            min-height: 100vh;
            padding-top: 80px;
        }

        .sidebar {
            width: 300px;
            background: var(--card-bg);
            padding: 80px 20px 40px;
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            height: 100%;
            overflow-y: auto;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            z-index: 2;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .sidebar h2 {
            font-size: 1.5em;
            color: var(--primary);
            margin-bottom: 20px;
            font-family: 'Playfair Display', serif;
            text-align: left;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding-left: 15px;
            position: relative;
        }

        .sidebar h2::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: var(--primary-light);
            border-radius: 2px;
        }

        .filter-card {
            background: var(--card-bg);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.3s ease;
        }

        .filter-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .filter-section label, .sort-section label, .category-section label {
            font-size: 1em;
            color: var(--text-secondary);
            font-family: 'Lora', serif;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .price-display {
            font-size: 1em;
            color: var(--text-primary);
            text-align: center;
            margin: 10px 0;
            font-weight: 500;
        }

        .filter-section input[type="range"] {
            width: 100%;
            -webkit-appearance: none;
            height: 6px;
            border-radius: 3px;
            background: var(--border-color);
            margin: 15px 0;
            position: relative;
        }

        .filter-section input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            background: var(--primary);
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
            border: 2px solid var(--card-bg);
            transition: transform 0.2s ease;
        }

        .filter-section input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.1);
        }

        .filter-section input[type="range"]::-moz-range-thumb {
            width: 18px;
            height: 18px;
            background: var(--primary);
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
            border: 2px solid var(--card-bg);
        }

        .sort-section select, .category-section select {
            width: 100%;
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            padding: 10px 15px;
            border-radius: 5px;
            color: var(--text-primary);
            font-family: 'Lora', serif;
            font-size: 0.95em;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236B4A2F%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right 15px top 50%;
            background-size: 10px auto;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .sort-section select:focus, .category-section select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(107, 74, 47, 0.2);
            outline: none;
        }

        .filter-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: var(--card-bg);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Lora', serif;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .filter-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .filter-reset {
            font-size: 0.85em;
            color: var(--primary);
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .filter-reset:hover {
            color: var(--primary-dark);
        }

        .container {
            flex: 1;
            max-width: 1100px;
            margin-left: 320px;
            padding: 40px;
            position: relative;
            z-index: 1;
        }

        h1 {
            text-align: center;
            font-size: 2.8em;
            color: var(--primary);
            font-family: 'Playfair Display', serif;
            text-transform: capitalize;
            letter-spacing: 2px;
            margin-bottom: 40px;
            font-weight: normal;
            position: relative;
            padding-bottom: 15px;
        }

        h1:after {
            content: "";
            position: absolute;
            width: 80px;
            height: 3px;
            background-color: var(--primary-light);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 35px;
            padding: 20px 0;
        }

        .product-card {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .product-image img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .product-image:hover img {
            transform: scale(1.05);
        }

        .product-title {
            font-size: 1.3em;
            color: var(--primary);
            font-family: 'Playfair Display', serif;
            margin-bottom: 10px;
            font-weight: normal;
            letter-spacing: 0.5px;
        }

        .product-description {
            color: var(--text-muted);
            font-size: 0.95em;
            line-height: 1.6;
            margin-bottom: 15px;
            height: 60px;
            overflow: hidden;
        }

        .product-price {
            font-size: 1.2em;
            color: var(--primary);
            font-weight: bold;
            margin-bottom: 20px;
            display: block;
        }

        .btn {
            display: inline-block;
            background: var(--primary);
            color: var(--card-bg);
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.9em;
            font-family: 'Lora', serif;
        }

        .btn:hover {
            background: var(--primary-dark);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .no-products {
            text-align: center;
            padding: 50px;
            font-size: 1.2em;
            color: var(--text-secondary);
            border: 1px dashed var(--border-color);
            border-radius: 8px;
            background: var(--bg-color);
        }

        .toggle-sidebar {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            background: var(--primary);
            border: none;
            padding: 10px 12px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 3;
            color: var(--card-bg);
            font-size: 1.2em;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .toggle-sidebar:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }

        /* Footer */
        .footer {
            background-color: var(--card-bg);
            padding: 60px 20px 20px;
            border-top: 1px solid var(--border-color);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .footer-logo i {
            color: var(--primary);
            font-size: 24px;
            margin-right: 10px;
        }

        .footer-logo span {
            color: var(--text-primary);
            font-size: 20px;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
        }

        .footer-about {
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .footer-social {
            display: flex;
        }

        .footer-social-link {
            width: 35px;
            height: 35px;
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .footer-social-link:hover {
            background: var(--primary);
            color: var(--card-bg);
        }

        .footer-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 20px;
            font-family: 'Playfair Display', serif;
        }

        .footer-links {
            list-style: none;
        }

        .footer-link {
            margin-bottom: 10px;
        }

        .footer-link a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link a:hover {
            color: var(--primary);
        }

        .footer-contact {
            margin-bottom: 15px;
            color: var(--text-muted);
        }

        .footer-contact i {
            margin-right: 10px;
            color: var(--primary);
        }

        .footer-newsletter p {
            color: var(--text-muted);
            margin-bottom: 15px;
        }

        .newsletter-form {
            display: flex;
        }

        .newsletter-input {
            flex-grow: 1;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            background: var(--bg-color);
            color: var(--text-primary);
            border-radius: 5px 0 0 5px;
        }

        .newsletter-button {
            background: var(--primary);
            color: var(--card-bg);
            border: none;
            padding: 0 15px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .newsletter-button:hover {
            background: var(--primary-dark);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .navbar-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--card-bg);
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            }

            .navbar-menu.show {
                display: flex;
            }

            .navbar-item {
                margin: 10px 0;
            }

            .hamburger {
                display: block;
            }
        }

        @media (max-width: 800px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                padding: 70px 15px 30px;
            }

            .sidebar.active {
                transform: translateX(0);
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2);
            }

            .container {
                margin-left: 0;
                padding: 20px;
            }

            .breadcrumbs {
                font-size: 0.85em;
            }

            .toggle-sidebar {
                display: block;
            }

            .products {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }

            h1 {
                font-size: 2.2em;
            }

            .filter-card {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-logo">
                <i class="fas fa-leaf"></i>
                <span>Handmade Shop</span>
            </a>
            <ul class="navbar-menu">
                <li class="navbar-item">
                    <a href="home.html" class="navbar-link"><i class="fas fa-home nav-icon"></i>Home</a>
                </li>
                <li class="navbar-item">
                    <a href="category.php" class="navbar-link active"><i class="fas fa-th nav-icon"></i>Categories</a>
                </li>
                <li class="navbar-item">
                    <a href="productlisting.php" class="navbar-link"><i class="fas fa-tag nav-icon"></i>Products</a>
                </li>
                <li class="navbar-item">
                    <a href="signup.php" class="navbar-link"><i class="fas fa-users nav-icon"></i>Artisans</a>
                </li>
                <li class="navbar-item">
                    <a href="about.html" class="navbar-link"><i class="fas fa-info-circle nav-icon"></i>About</a>
                </li>
                <li class="navbar-item">
                    <a href="#" class="navbar-link"><i class="fas fa-envelope nav-icon"></i>Contact</a>
                </li>
            </ul>
            <div class="navbar-icons">
                <div class="navbar-icon"><i class="fas fa-search"></i></div>
                <div class="navbar-icon"><a href="login.php"><i class="fas fa-user"></i></a></div>
                <div class="navbar-icon"><i class="fas fa-heart"></i></div>
                <div class="navbar-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">3</span>
                </div>
                <div class="hamburger"><i class="fas fa-bars"></i></div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <button class="toggle-sidebar" onclick="toggleSidebar()"><i class="fas fa-filter"></i></button>
        <div class="sidebar" id="sidebar">
            <h2>Refine Selection</h2>
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="category.html">Categories</a></li>
                    <li><?php echo htmlspecialchars($category); ?></li>
                </ul>
            </nav>
            <form id="filter-form" method="GET" action="">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <div class="filter-card">
                    <div class="filter-header">
                        <label for="price-range">Price Range</label>
                        <button type="button" class="filter-reset" onclick="resetPrice()">Reset</button>
                    </div>
                    <input type="range" id="price-range" name="price" min="0" max="<?php echo $maxPrice; ?>" value="<?php echo $priceRange; ?>" oninput="updatePrice(this.value)">
                    <div class="price-display" id="price-value">₹<?php echo $priceRange; ?></div>
                </div>
                <div class="filter-card">
                    <div class="filter-header">
                        <label for="sort">Sort By</label>
                    </div>
                    <select id="sort" name="sort">
                        <option value="price-asc" <?php if($sort == 'price-asc') echo 'selected'; ?>>Price: Low to High</option>
                        <option value="price-desc" <?php if($sort == 'price-desc') echo 'selected'; ?>>Price: High to Low</option>
                        <option value="name-asc" <?php if($sort == 'name-asc') echo 'selected'; ?>>Name: A to Z</option>
                        <option value="name-desc" <?php if($sort == 'name-desc') echo 'selected'; ?>>Name: Z to A</option>
                    </select>
                </div>
                <div class="filter-card">
                    <div class="filter-header">
                        <label for="subcategory">Craft Type</label>
                        <button type="button" class="filter-reset" onclick="resetCategory()">Reset</button>
                    </div>
                    <select id="subcategory" name="subcategory">
                        <option value="all" <?php if($subcategory == 'all') echo 'selected'; ?>>All Crafts</option>
                        <option value="pottery" <?php if($subcategory == 'pottery') echo 'selected'; ?>>Pottery</option>
                        <option value="textiles" <?php if($subcategory == 'textiles') echo 'selected'; ?>>Textiles</option>
                        <option value="jewelry" <?php if($subcategory == 'jewelry') echo 'selected'; ?>>Jewelry</option>
                        <option value="woodwork" <?php if($subcategory == 'woodwork') echo 'selected'; ?>>Woodwork</option>
                    </select>
                </div>
                <button type="submit" class="filter-btn">Apply Filters</button>
            </form>
        </div>
        <div class="container">
            <h1><?php echo htmlspecialchars($category); ?> Collection</h1>
            <div class="products">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                            </div>
                            <h3 class="product-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                            <span class="product-price">₹<?php echo number_format($row['price'], 2); ?></span>
                            <a href="select_quantity.php?product_id=<?php echo $row['product_id']; ?>" class="btn">Add to Cart</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-products">
                        <p>No artisan pieces match your selection criteria. Please try different filters.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3 class="footer-title">Quick Links</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="#">Home</a></li>
                        <li class="footer-link"><a href="#">Shop</a></li>
                        <li class="footer-link"><a href="#">About Us</a></li>
                        <li class="footer-link"><a href="#">Artisans</a></li>
                        <li class="footer-link"><a href="#">Blog</a></li>
                        <li class="footer-link"><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3 class="footer-title">Customer Service</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="#">My Account</a></li>
                        <li class="footer-link"><a href="#">Order Tracking</a></li>
                        <li class="footer-link"><a href="#">Wishlist</a></li>
                        <li class="footer-link"><a href="#">Shipping Policy</a></li>
                        <li class="footer-link"><a href="#">Returns & Exchanges</a></li>
                        <li class="footer-link"><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3 class="footer-title">Contact Us</h3>
                    <div class="footer-contact">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>123 Artisan Lane, Craft City, AC 12345</p>
                    </div>
                    <div class="footer-contact">
                        <i class="fas fa-phone-alt"></i>
                        <p>+1 (555) 987-6543</p>
                    </div>
                    <div class="footer-contact">
                        <i class="fas fa-envelope"></i>
                        <p>support@handmadeshop.com</p>
                    </div>
                </div>
                <div class="footer-col">
                    <h3 class="footer-title">Newsletter</h3>
                    <p class="footer-newsletter">Stay updated with new products and exclusive offers.</p>
                    <form class="newsletter-form">
                        <input type="email" class="newsletter-input" placeholder="Your email address">
                        <button type="submit" class="newsletter-button"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 Handmade Shop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        function updatePrice(value) {
            document.getElementById('price-value').textContent = '₹' + value;
        }

        function resetPrice() {
            const priceRange = document.getElementById('price-range');
            priceRange.value = priceRange.max;
            updatePrice(priceRange.value);
        }

        function resetCategory() {
            document.getElementById('subcategory').value = 'all';
        }

        // Mobile Menu Toggle
        document.querySelector('.hamburger').addEventListener('click', function() {
            document.querySelector('.navbar-menu').classList.toggle('show');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideMenu = document.querySelector('.navbar-menu').contains(event.target);
            const isClickOnHamburger = document.querySelector('.hamburger').contains(event.target);
            if (!isClickInsideMenu && !isClickOnHamburger && document.querySelector('.navbar-menu').classList.contains('show')) {
                document.querySelector('.navbar-menu').classList.remove('show');
            }
        });
    </script>
</body>
</html>