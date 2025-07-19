<?php
include 'config.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mobile = $_POST['mobile'];
        $place = $_POST['place'];
        $specialty = $_POST['specialty'];

        $update = "UPDATE users SET mobile='$mobile', place='$place', specialty='$specialty' WHERE user_id=$user_id";
        if (mysqli_query($conn, $update)) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisan Details</title>
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
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .details-container {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            padding: 2.5rem;
        }
        
        .details-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .details-header h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .details-header p {
            color: var(--accent-color);
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            transition: border-color 0.3s, box-shadow 0.3s;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(139, 111, 71, 0.2); /* Adjusted to match primary-light */
        }
        
        .submit-btn {
            width: 100%;
            padding: 0.9rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background-color: var(--primary-dark);
        }
        
        .progress-container {
            margin-bottom: 2rem;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--bg-color);
            color: var(--accent-color);
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 0.5rem;
            font-weight: 600;
            position: relative;
            z-index: 2;
        }
        
        .step.active .step-number {
            background-color: var(--primary-color);
            color: white;
        }
        
        .step.completed .step-number {
            background-color: #27ae60;
            color: white;
        }
        
        .step-label {
            font-size: 0.8rem;
            color: var(--accent-color);
        }
        
        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .step.completed .step-label {
            color: #27ae60;
        }
        
        .progress-bar {
            height: 4px;
            width: 100%;
            background-color: var(--bg-color);
            position: relative;
            margin-top: -17px;
            z-index: 1;
        }
        
        .progress-bar-fill {
            height: 100%;
            width: 66%;
            background-color: var(--primary-color);
        }
        
        .helper-text {
            font-size: 0.85rem;
            color: var(--accent-color);
            margin-top: 0.3rem;
        }
        
        @media (max-width: 500px) {
            .details-container {
                padding: 1.5rem;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="details-container">
        <div class="details-header">
            <h1>Complete Your Profile</h1>
            <p>Tell us more about your artisan business</p>
        </div>
        
        <div class="progress-container">
            <div class="progress-steps">
                <div class="step completed">
                    <div class="step-number">1</div>
                    <div class="step-label">Sign Up</div>
                </div>
                <div class="step active">
                    <div class="step-number">2</div>
                    <div class="step-label">Profile Details</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-label">Complete</div>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-bar-fill"></div>
            </div>
        </div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="mobile">Mobile Number</label>
                <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Enter your mobile number" required>
                <div class="helper-text">We'll use this to contact you about orders</div>
            </div>
            
            <div class="form-group">
                <label for="place">Location</label>
                <input type="text" id="place" name="place" class="form-control" placeholder="Your city or region" required>
                <div class="helper-text">This helps buyers find local artisans</div>
            </div>
            
            <div class="form-group">
                <label for="specialty">Specialty</label>
                <input type="text" id="specialty" name="specialty" class="form-control" placeholder="What crafts do you specialize in?" required>
                <div class="helper-text">E.g., Pottery, Woodworking, Jewelry, etc.</div>
            </div>
            
            <button type="submit" class="submit-btn">Complete Registration</button>
        </form>
    </div>
</body>
</html>