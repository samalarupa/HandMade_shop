<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'handmade_shop');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user is an admin
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND user_type = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin'] = $user['id'];  // Store user ID in session
            header("Location: admindashboard.php");
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin not found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2e3bff;
            --secondary: #00ffe1;
            --dark: #111827;
            --light: #f9fafb;
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
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--light);
        }
        
        .container {
            width: 90%;
            max-width: 420px;
            padding: 30px;
            background: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            overflow: hidden;
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }
        
        h1 {
            text-align: center;
            margin-bottom: 24px;
            font-weight: 300;
            font-size: 28px;
            color: var(--light);
            letter-spacing: 1px;
            position: relative;
        }
        
        h1::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -8px;
            transform: translateX(-50%);
            width: 60px;
            height: 2px;
            background: var(--secondary);
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary);
        }
        
        input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: none;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            color: var(--light);
            font-size: 16px;
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        input:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--secondary);
            background: rgba(255, 255, 255, 0.08);
        }
        
        input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        button {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border: none;
            border-radius: 8px;
            color: var(--light);
            padding: 14px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        
        .error {
            color: #ff5e5e;
            text-align: center;
            padding: 8px;
            margin-top: 16px;
            background: rgba(255, 94, 94, 0.1);
            border-radius: 4px;
            font-size: 14px;
        }
        
        /* Breadcrumb Styles */
        .breadcrumbs {
            padding: 10px 0;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .breadcrumbs a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
            font-size: 14px;
        }
        
        .breadcrumbs a:hover {
            color: var(--secondary);
        }
        
        .breadcrumbs .separator {
            margin: 0 8px;
            color: rgba(255, 255, 255, 0.4);
            font-size: 12px;
        }
        
        .breadcrumbs .current {
            color: var(--secondary);
            font-weight: 500;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="breadcrumbs">
            <a href="home.html"><i class="fas fa-home"></i> Home</a>
            <span class="separator"><i class="fas fa-chevron-right"></i></span>
            <span class="current">Admin Login</span>
        </div>
        
        <h1>Admin Access</h1>
        <form method="POST" action="adminlogin.php">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    </div>
</body>
</html>