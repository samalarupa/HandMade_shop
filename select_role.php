<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture and sanitize role
    $role = isset($_POST['role']) ? htmlspecialchars(trim($_POST['role'])) : '';

    // Validate role
    if (!in_array($role, ['buyer', 'artisan', 'admin'])) {
        $error = "Invalid role selected.";
    } else {
        // Store role in session
        $_SESSION['temp_role'] = $role;

        if ($role === 'artisan') {
            // Redirect to artisan details
            header("Location: artisan_details.php");
            exit();
        } else {
            // Save buyer/admin data
            $username = $_SESSION['temp_username'] ?? '';
            $email = $_SESSION['temp_email'] ?? '';
            $password = password_hash($_SESSION['temp_password'] ?? '', PASSWORD_DEFAULT);

            $conn = new mysqli("localhost", "root", "", "handmade_shop");
            if ($conn->connect_error) {
                $error = "Database connection failed.";
            } else {
                // Use prepared statement to prevent SQL injection
                $sql = "INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $username, $email, $password, $role);

                if ($stmt->execute()) {
                    $stmt->close();
                    $conn->close();
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Error saving user data.";
                    $stmt->close();
                    $conn->close();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Role - Handmade Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #008080; /* Deep teal */
            --primary-dark: #006666;
            --text-color: #e0e0e0;
            --bg-color: #1a1a1a; /* Dark grey */
            --card-bg: #2a2a2a; /* Slightly lighter grey */
            --border-color: #444;
            --accent-color: #ffffff;
        }

        body {
            background: var(--bg-color);
            color: var(--text-color);
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .container {
            max-width: 450px;
            background: var(--card-bg);
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.3);
            padding: 2rem;
            animation: fadeIn 0.5s ease-in;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header h1 {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--accent-color);
        }

        .form-header p {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        .accent-line {
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            margin: 0.5rem auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
            color: var(--text-color);
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 0.25rem;
            transition: background-color 0.3s;
        }

        .form-group label:hover {
            background: #333;
        }

        .form-group input[type="radio"] {
            margin-right: 0.5rem;
            accent-color: var(--primary-color);
        }

        .btn {
            background: var(--primary-color);
            border: none;
            color: var(--accent-color);
            padding: 0.75rem;
            border-radius: 0.25rem;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .error-message {
            color: #ff5555;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            text-align: center;
        }

        .mode-switch {
            position: fixed;
            top: 1rem;
            right: 1rem;
            cursor: pointer;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 576px) {
            .container {
                padding: 1.5rem;
            }

            .form-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Dark mode toggle -->
    <div class="mode-switch" id="darkModeToggle">
        <i class="fas fa-moon"></i>
    </div>

    <!-- Form container -->
    <div class="container">
        <div class="form-header">
            <h1>Select Your Role</h1>
            <p>Choose how you'd like to engage with our marketplace</p>
            <div class="accent-line"></div>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-content">
            <div class="form-group">
                <label>
                    <input type="radio" name="role" value="buyer" required>
                    Buyer
                </label>
                <label>
                    <input type="radio" name="role" value="artisan">
                    Artisan
                </label>
                <label>
                    <input type="radio" name="role" value="admin">
                    Admin
                </label>
            </div>

            <button type="submit" class="btn">Continue</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark mode toggle
        const toggle = document.getElementById('darkModeToggle');
        const body = document.body;

        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
            toggle.innerHTML = '<i class="fas fa-sun"></i>';
        }

        toggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            const isDark = body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
            toggle.innerHTML = `<i class="fas fa-${isDark ? 'sun' : 'moon'}"></i>`;
        });

        // Client-side form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', (e) => {
            const roles = document.querySelectorAll('input[name="role"]:checked');
            const errorContainer = document.querySelector('.error-message');

            if (errorContainer) errorContainer.remove();

            if (!roles.length) {
                e.preventDefault();
                const error = document.createElement('div');
                error.className = 'error-message';
                error.textContent = 'Please select a role.';
                form.insertBefore(error, form.querySelector('.btn'));
            }
        });
    </script>
</body>
</html>