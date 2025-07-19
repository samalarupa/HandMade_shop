<?php
session_start();

// Ensure user is logged in and is a buyer
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'buyer') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handmade Shop - Categories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #F0E9E0; /* Cream */
            --primary: #6B4A2F; /* Warm brown */
            --primary-light: #8B6F47; /* Lighter brown */
            --primary-dark: #4A3723; /* Darker brown */
            --card-bg: #FFFFFF; /* White */
            --border-color: #D9D1C7; /* Light cream */
            --text-primary: #2D2D2D; /* Dark charcoal */
            --text-muted: #5C5C5C; /* Medium gray */
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
            --border-radius: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lora', serif;
            background: var(--bg-color);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .header {
            background: var(--card-bg);
            padding: 16px 0;
            box-shadow: var(--shadow-sm);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 8px;
            font-size: 24px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .nav-link {
            font-family: 'Lora', serif;
            font-size: 15px;
            font-weight: 500;
            color: var(--text-primary);
            text-decoration: none;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: var(--primary);
            bottom: -4px;
            left: 0;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-icon {
            font-size: 18px;
            color: var(--text-primary);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .nav-icon:hover {
            color: var(--primary);
        }

        .nav-icon-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--primary);
            color: var(--card-bg);
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .search-container {
            max-width: 800px;
            margin: 100px auto 20px;
            padding: 0 20px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: var(--card-bg);
            border-radius: 50px;
            padding: 10px 16px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .search-bar:focus-within {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(107, 74, 47, 0.1);
        }

        .search-input {
            border: none;
            background: transparent;
            padding: 8px 10px;
            width: 100%;
            font-size: 15px;
            outline: none;
            color: var(--text-primary);
            font-family: 'Lora', serif;
        }

        .search-input::placeholder {
            color: var(--text-muted);
        }

        .search-icon {
            color: var(--text-muted);
            font-size: 18px;
            margin-right: 8px;
        }

        .breadcrumb {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            font-family: 'Lora', serif;
            font-size: 14px;
            color: var(--text-muted);
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb a:hover {
            color: var(--primary-dark);
        }

        .breadcrumb span {
            color: var(--text-primary);
        }

        .breadcrumb i {
            margin: 0 8px;
            color: var(--text-muted);
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 0 20px;
        }

        .content {
            width: 100%;
        }

        .category-heading {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 600;
            color: var(--primary);
            text-align: center;
            margin-bottom: 12px;
        }

        .category-description {
            font-family: 'Lora', serif;
            font-size: 16px;
            color: var(--text-muted);
            text-align: center;
            max-width: 700px;
            margin: 0 auto 30px;
        }

        .sort-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: var(--card-bg);
            padding: 15px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }

        .results-count {
            font-size: 14px;
            color: var(--text-muted);
        }

        .sort-dropdown {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 14px;
            color: var(--text-primary);
            cursor: pointer;
            outline: none;
            font-family: 'Lora', serif;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sort-dropdown:hover, 
        .sort-dropdown:focus {
            border-color: var(--primary-light);
        }

        .view-options {
            display: flex;
            gap: 10px;
        }

        .view-option {
            font-size: 18px;
            color: var(--text-muted);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .view-option.active {
            color: var(--primary);
        }

        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .category-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .category-icon {
            font-size: 36px;
            color: var(--primary);
            background: var(--bg-color);
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 15px;
            transition: all 0.3s ease;
        }

        .category-card:hover .category-icon {
            background: var(--primary);
            color: var(--card-bg);
        }

        .category-name {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .category-count {
            font-family: 'Lora', serif;
            font-size: 13px;
            color: var(--text-muted);
            background: var(--bg-color);
            padding: 4px 10px;
            border-radius: 20px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 40px;
        }

        .page-item {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background: var(--card-bg);
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .page-item:hover {
            background: var(--primary-light);
            color: var(--card-bg);
        }

        .page-item.active {
            background: var(--primary);
            color: var(--card-bg);
            border-color: var(--primary);
        }

        .page-item.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .footer {
            background: var(--card-bg);
            padding: 40px 20px;
            border-top: 1px solid var(--border-color);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        .footer-column h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 8px;
        }

        .footer-column ul li a {
            font-family: 'Lora', serif;
            font-size: 14px;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-column ul li a:hover {
            color: var(--primary);
        }

        .newsletter-form {
            display: flex;
            gap: 10px;
        }

        .newsletter-input {
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-family: 'Lora', serif;
            font-size: 14px;
            flex: 1;
        }

        .newsletter-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            background: var(--primary);
            color: var(--card-bg);
            font-family: 'Lora', serif;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .newsletter-btn:hover {
            background: var(--primary-dark);
        }

        @media screen and (max-width: 992px) {
            .categories {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            .footer-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media screen and (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .sort-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .view-options {
                display: none;
            }

            .footer-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 576px) {
            .categories {
                grid-template-columns: 1fr;
            }

            .category-heading {
                font-size: 24px;
            }

            .category-description {
                font-size: 14px;
            }

            .footer-container {
                grid-template-columns: 1fr;
            }

            .breadcrumb {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="#" class="logo">
                <i class="fas fa-leaf"></i>
                HandmadeShop
            </a>
            <div class="nav-links">
                <a href="#" class="nav-link">Home</a>
                <a href="#" class="nav-link active">Categories</a>
                <a href="#" class="nav-link">Products</a>
                <a href="#" class="nav-link">Artisans</a>
                <a href="#" class="nav-link">About</a>
                <a href="#" class="nav-link">Contact</a>
                <i class="fas fa-search nav-icon"></i>
                <i class="fas fa-heart nav-icon"></i>
                <i class="fas fa-shopping-cart nav-icon">
                    <span class="nav-icon-badge">2</span>
                </i>
                <i class="fas fa-user nav-icon"></i>
            </div>
        </div>
    </header>

    <!-- Search Bar -->
    <div class="search-container">
        <div class="search-bar">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Search for handmade products...">
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="home.html">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>Categories</span>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <main class="content">
            <h1 class="category-heading">Browse Categories</h1>
            <p class="category-description">Discover unique handmade products crafted with care by talented artisans from around the world. Explore our diverse collection of handcrafted treasures.</p>

            <!-- <div class="sort-options">
                <div class="results-count">7 categories found</div>
                <select class="sort-dropdown">
                    <option>Sort by: Featured</option>
                    <option>Sort by: Newest</option>
                    <option>Sort by: Popular</option>
                    <option>Sort by: Price: Low to High</option>
                    <option>Sort by: Price: High to Low</option>
                </select>
                <div class="view-options">
                    <i class="fas fa-th view-option active" title="Grid view"></i>
                    <i class="fas fa-list view-option" title="List view"></i>
                </div>
            </div> -->

            <div class="categories">
                <?php
                $categories = [
                    ['name' => 'Jewelry', 'icon' => 'fas fa-gem', 'count' => '245 items'],
                    ['name' => 'Home Decor', 'icon' => 'fas fa-home', 'count' => '182 items'],
                    ['name' => 'Clothing', 'icon' => 'fas fa-tshirt', 'count' => '154 items'],
                    ['name' => 'Accessories', 'icon' => 'fas fa-hat-cowboy', 'count' => '97 items'],
                    ['name' => 'Art', 'icon' => 'fas fa-palette', 'count' => '128 items'],
                    ['name' => 'Toys', 'icon' => 'fas fa-puzzle-piece', 'count' => '76 items'],
                    ['name' => 'Stationery', 'icon' => 'fas fa-pen-nib', 'count' => '63 items']
                ];

                foreach ($categories as $category) {
                    echo "<div class='category-card' onclick=\"window.location.href='collection.php?category=" . urlencode($category['name']) . "'\">";
                    echo "<div class='category-icon'><i class='" . $category['icon'] . "'></i></div>";
                    echo "<div class='category-name'>" . $category['name'] . "</div>";
                    echo "<div class='category-count'>" . $category['count'] . "</div>";
                    echo "</div>";
                }
                ?>
            </div>

            <div class="pagination">
                <div class="page-item disabled"><i class="fas fa-chevron-left"></i></div>
                <div class="page-item active">1</div>
                <div class="page-item">2</div>
                <div class="page-item">3</div>
                <div class="page-item"><i class="fas fa-chevron-right"></i></div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h3>About</h3>
                <p style="font-family: 'Lora', serif; font-size: 14px; color: var(--text-muted);">
                    HandmadeShop is your destination for unique, handcrafted products made by artisans worldwide.
                </p>
            </div>
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="category.php">Categories</a></li>
                    <li><a href="category.php">Products</a></li>
                    <li><a href="artisans.php">Artisans</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Customer Service</h3>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Shipping</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contact Us</h3>
                <ul>
                    <li><i class="fas fa-envelope"></i> support@handmadeshop.com</li>
                    <li><i class="fas fa-phone"></i> +1 234 567 890</li>
                    <li><i class="fas fa-map-marker-alt"></i> 123 Artisan Lane, Craft City</li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Newsletter</h3>
                <p style="font-family: 'Lora', serif; font-size: 14px; color: var(--text-muted);">
                    Subscribe to our newsletter for updates and offers.
                </p>
                <form class="newsletter-form">
                    <input type="email" class="newsletter-input" placeholder="Your email">
                    <button type="submit" class="newsletter-btn">Subscribe</button>
                </form>
            </div>
        </div>
    </footer>
</body>
</html>