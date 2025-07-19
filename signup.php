<?php
include 'config.php'; // your DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];

    $query = "INSERT INTO users (username, email, password, user_type) VALUES ('$username', '$email', '$password', '$user_type')";
    if (mysqli_query($conn, $query)) {
        $user_id = mysqli_insert_id($conn);
        if ($user_type == 'artisan') {
            header("Location: artisan_details.php?user_id=$user_id");
            exit();
        } else {
            header("Location: login.php");
            exit();
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        
        .signup-container {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            padding: 2.5rem;
        }
        
        .signup-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .signup-header h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .signup-header p {
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
        
        .role-selector {
            display: flex;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
        }
        
        .role-option {
            flex: 1;
            text-align: center;
            position: relative;
        }
        
        .role-option input {
            position: absolute;
            opacity: 0;
            height: 0;
            width: 0;
        }
        
        .role-option label {
            display: block;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            background-color: var(--bg-color);
        }
        
        .role-option input:checked + label {
            background-color: var(--primary-color);
            color: white;
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
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }
        
        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 500px) {
            .signup-container {
                padding: 1.5rem;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h1>Create Your Account</h1>
            <p>Join our community today</p>
        </div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Choose a username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Your email address" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
            </div>
            
            <div class="form-group">
                <label>Select Role:</label>
                <div class="role-selector">
                    <div class="role-option">
                        <input type="radio" id="buyer" name="user_type" value="buyer" checked>
                        <label for="buyer">Buyer</label>
                    </div>
                    <div class="role-option">
                        <input type="radio" id="artisan" name="user_type" value="artisan">
                        <label for="artisan">Artisan</label>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="submit-btn">Sign Up</button>
            
            <div class="login-link">
                Already have an account? <a href="login.php">Log in</a>
            </div>
        </form>
    </div>
</body>
</html>