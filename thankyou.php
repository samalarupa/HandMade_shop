<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Handmade Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #0d1117;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: 
                radial-gradient(circle at 5% 10%, rgba(48, 185, 117, 0.05) 0%, transparent 25%),
                radial-gradient(circle at 95% 90%, rgba(48, 185, 117, 0.05) 0%, transparent 25%);
        }
        
        .container {
            width: 100%;
            max-width: 500px;
            padding: 3rem;
            background: linear-gradient(145deg, #161b22, #0d1117);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(48, 185, 117, 0.15);
        }
        
        .success-icon {
            font-size: 5rem;
            color: #30B975;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #30B975, #1a6b44);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
        }
        
        .success-icon::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 80px;
            background: radial-gradient(circle, rgba(48, 185, 117, 0.2) 0%, rgba(48, 185, 117, 0) 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            z-index: -1;
        }
        
        h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }
        
        .message {
            color: #b8bcc0;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .continue-btn {
            background: linear-gradient(135deg, #30B975, #1a6b44);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(48, 185, 117, 0.3);
        }
        
        .continue-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(48, 185, 117, 0.4);
        }
        
        /* Animated particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: #30B975;
            border-radius: 50%;
            opacity: 0;
            animation: rise 4s ease-in-out infinite;
        }
        
        .particle:nth-child(1) {
            left: 10%;
            animation-delay: 0s;
        }
        
        .particle:nth-child(2) {
            left: 30%;
            animation-delay: 0.8s;
        }
        
        .particle:nth-child(3) {
            left: 50%;
            animation-delay: 1.5s;
        }
        
        .particle:nth-child(4) {
            left: 70%;
            animation-delay: 0.5s;
        }
        
        .particle:nth-child(5) {
            left: 90%;
            animation-delay: 2.2s;
        }
        
        @keyframes rise {
            0% {
                bottom: -10px;
                opacity: 0;
            }
            50% {
                opacity: 0.7;
            }
            100% {
                bottom: 100%;
                opacity: 0;
            }
        }
        
        /* Responsive styles */
        @media (max-width: 600px) {
            .container {
                width: 90%;
                padding: 2rem 1.5rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .message {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>Thank You!</h1>
        <p class="message">
            Your order has been placed successfully.<br>
            We'll begin crafting your handmade item shortly.
        </p>
        
        <a href="category.php" class="continue-btn">Continue Shopping</a>
    </div>
</body>
</html>