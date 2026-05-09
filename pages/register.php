<?php
include('../db.php');  // Database connection
session_start();

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = 'user'; // Default role for users

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email is already registered!');</script>";
        $stmt->close();
    } else {
        $stmt->close();

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        $stmt->execute();

        // Log the user in after successful registration
        $_SESSION['user_id'] = $conn->insert_id;
        $stmt->close();
        $success_message = "Registration successful! Redirecting to login...";
        $redirect_url = "login.php";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border: #e5e7eb;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.5;
        }

        .register-container {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            transition: box-shadow 0.2s ease;
        }

        .register-container:hover {
            box-shadow: var(--shadow-hover);
        }

        h2 {
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin: 0 0 8px 0;
            color: var(--text-primary);
        }

        .subtitle {
            text-align: center;
            color: var(--text-secondary);
            font-size: 16px;
            margin: 0 0 32px 0;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 16px;
            background-color: #ffffff;
            color: var(--text-primary);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            outline: none;
        }

        input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        input::placeholder {
            color: var(--text-secondary);
        }

        button {
            width: 100%;
            padding: 12px 16px;
            background-color: var(--primary-color);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }

        button:hover {
            background-color: var(--primary-hover);
        }

        button:active {
            transform: translateY(1px);
        }

        .error-message {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }

        .links {
            text-align: center;
            margin-top: 24px;
        }

        .links a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
        }

        .modal-content h3 {
            margin: 0 0 16px 0;
            color: #22c55e;
            font-size: 24px;
        }

        .modal-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 16px;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 32px 24px;
                margin: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" placeholder="Choose a username" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Create a password" required>
            </div>
            <button type="submit" name="register">Create Account</button>
        </form>

        <div class="links">
            <p >Already have an account? </p><a href="login.php">Sign in</a>
        </div>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
    </div>

    <div id="successModal" class="modal">
        <div class="modal-content">
            <h3>✓ Success</h3>
            <p><?php echo isset($success_message) ? htmlspecialchars($success_message) : ''; ?></p>
        </div>
    </div>

    <script>
        <?php if (isset($redirect_url)): ?>
        window.addEventListener('load', function() {
            var modal = document.getElementById('successModal');
            modal.classList.add('show');
            setTimeout(function() {
                window.location.href = '<?= htmlspecialchars($redirect_url); ?>';
            }, 2000);
        });
        <?php endif; ?>
    </script>
</body>
</html>