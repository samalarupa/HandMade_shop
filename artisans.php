<?php
// artisans.php
session_start();
include 'config.php'; // This will include your database connection file

// Query to fetch artisans
$query = "SELECT username, mobile, place, specialty FROM users WHERE user_type = 'artisan'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meet Our Artisans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6B4A2F; /* Warm brown */
            --primary-dark: #4A3723;
            --primary-light: #8B6F47; /* Lighter brown */
            --text-color: #4A3723; /* Darker brown */
            --bg-color: #F0E9E0; /* Cream/beige */
            --card-bg: #FFFFFF; /* White for cards */
            --border-color: #D9D1C7; /* Light cream border */
            --accent-color: #8B6F47; /* Lighter brown */
            --border-radius: 12px;
            --box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: var(--bg-color);
            background-image: linear-gradient(to bottom, var(--bg-color), #E0D8D0); /* Adjusted gradient */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            padding: 0 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 10px; /* Reduced space for smaller navbar */
        }
        
        /* Navbar Styles */
        .navbar {
            background-color: var(--card-bg);
            box-shadow: var(--box-shadow);
            padding: 0.3rem 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .navbar .navbar-brand {
            color: var(--primary-color);
            font-size: 1.2rem; /* Smaller brand text */
            font-weight: 600;
        }
        
        .navbar .nav-link {
            color: var(--primary-color) !important;
            font-weight: 500;
            margin-left: 0.7rem; /* Reduced margin */
            font-size: 0.95rem; /* Smaller text */
            transition: color 0.3s ease;
        }
        
        .navbar .nav-link:hover {
            color: var(--primary-dark) !important;
        }
        
        /* Existing Styles */
        header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .page-title {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
            font-weight: 600;
        }
        
        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 4px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }
        
        .subtitle {
            color: var(--accent-color);
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .artisans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .artisan-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        
        .artisan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .artisan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--primary-color);
        }
        
        .artisan-header {
            padding: 25px 25px 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .artisan-name {
            font-size: 1.4rem;
            color: var(--accent-color);
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .artisan-specialty {
            display: inline-block;
            background-color: rgba(107, 74, 47, 0.1); /* Adjusted to match primary-color transparency */
            color: var(--primary-color);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .artisan-body {
            padding: 20px 25px;
        }
        
        .artisan-info {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .artisan-info:last-child {
            margin-bottom: 0;
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(107, 74, 47, 0.1); /* Adjusted to match primary-color */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-color);
        }
        
        .info-text {
            flex: 1;
        }
        
        .info-label {
            font-size: 0.85rem;
            color: var(--accent-color);
            margin-bottom: 2px;
        }
        
        .info-value {
            font-weight: 500;
            color: var(--text-color);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .empty-icon {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .empty-text {
            font-size: 1.2rem;
            color: var(--accent-color);
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .artisans-grid {
                grid-template-columns: 1fr;
            }
            
            .navbar .navbar-brand {
                font-size: 1rem; /* Even smaller on mobile */
            }
            
            .navbar .nav-link {
                margin-left: 0.4rem;
                font-size: 0.85rem; /* Smaller text on mobile */
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="home.html">Handmade Goods</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="home.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="category.html">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">Your Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <header>
            <h1 class="page-title">Meet Our Artisans</h1>
            <p class="subtitle">Discover our talented artisans specializing in various crafts. Feel free to contact them directly to discuss custom projects and collaborations.</p>
        </header>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="artisans-grid">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="artisan-card">
                        <div class="artisan-header">
                            <h3 class="artisan-name"><?php echo htmlspecialchars($row['username']); ?></h3>
                            <span class="artisan-specialty"><?php echo htmlspecialchars($row['specialty']); ?></span>
                        </div>
                        <div class="artisan-body">
                            <div class="artisan-info">
                                <div class="info-icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="info-text">
                                    <div class="info-label">Contact Number</div>
                                    <div class="info-value"><?php echo htmlspecialchars($row['mobile']); ?></div>
                                </div>
                            </div>
                            <div class="artisan-info">
                                <div class="info-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="info-text">
                                    <div class="info-label">Location</div>
                                    <div class="info-value"><?php echo htmlspecialchars($row['place']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-user-slash"></i>
                </div>
                <p class="empty-text">No artisans found at the moment.</p>
                <p>Check back later as we continue to expand our community of talented craftspeople.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <?php mysqli_close($conn); ?>
</body>
</html>