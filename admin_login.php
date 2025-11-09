<?php
session_start();
require_once 'db_connection.php';

 $error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Debug: Show what we're trying to verify
    // echo "<!-- Debug: Username: $username, Password: $password -->";
    
    // Query database for admin user
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username); // Allow login with email too
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Debug: Show hash comparison
        // echo "<!-- Debug: Stored hash: " . $user['password'] . " -->";
        // echo "<!-- Debug: Verify result: " . (password_verify($password, $user['password']) ? 'true' : 'false') . " -->";
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a new session
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            
            // Redirect to admin dashboard
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
    
    $stmt->close();
}

 $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Temesgen Fikadu Baysa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FFD700;
            --secondary-color: #000000;
            --text-color: #333333;
            --light-gray: #f8f9fa;
            --dark-gray: #6c757d;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
        }

        .login-container {
            width: 400px;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }

        .login-header p {
            color: var(--dark-gray);
        }

        .credentials-display {
            background: #f0f8ff;
            border: 1px solid #007bff;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .credentials-display strong {
            color: #007bff;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: var(--secondary-color);
            font-weight: 600;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            font-size: 1rem;
        }

        .btn:hover {
            background-color: var(--secondary-color);
            color: var(--primary-color);
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            color: #fff;
        }

        .alert-danger {
            background-color: #dc3545;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: var(--dark-gray);
        }

        .back-link:hover {
            color: var(--primary-color);
        }

        .debug-links {
            text-align: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .debug-links a {
            display: inline-block;
            margin: 0 5px;
            padding: 5px 10px;
            background: var(--light-gray);
            color: var(--secondary-color);
            text-decoration: none;
            border-radius: 3px;
            font-size: 0.8rem;
        }

        .debug-links a:hover {
            background: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Admin Login</h1>
            <p>Temesgen Fikadu Baysa Portfolio</p>
        </div>
        
        <div class="credentials-display">
            <strong>🔐 Login Credentials:</strong><br>
            Username: temesgen001@gmail.com<br>
            Password: Temesgen@1.com
        </div>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="post" action="admin_login.php">
            <div class="form-group">
                <label for="username">Username / Email</label>
                <input type="text" id="username" name="username" class="form-control" 
                       value="temesgen001@gmail.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" 
                       value="Temesgen@1.com" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <div class="debug-links">
            <a href="debug_login.php">Debug Login</a>
            <a href="test_connection.php">Test DB</a>
            <a href="setup_database.php">Setup DB</a>
        </div>
        
        <a href="index.php" class="back-link">← Back to Portfolio</a>
    </div>
</body>
</html>