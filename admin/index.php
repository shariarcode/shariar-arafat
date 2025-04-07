<?php
session_start();

// If already logged in, redirect to dashboard
if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Initialize variables
$error = '';

// Handle login form submission
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple authentication (in a real app, you'd have secure password hashing and DB storage)
    $username = 'admin';
    $password = 'admin123'; // This would be a hashed password in a real application
    
    $inputUsername = $_POST['username'] ?? '';
    $inputPassword = $_POST['password'] ?? '';
    
    if($inputUsername === $username && $inputPassword === $password) {
        // Set session variables
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        
        // Redirect to dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Shariar Arafat Portfolio</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: var(--bg-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .login-form {
            display: flex;
            flex-direction: column;
        }
        
        .error-message {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            padding: 0.75rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-gray);
            text-decoration: none;
            font-size: 0.875rem;
            transition: var(--transition);
        }
        
        .back-link:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Portfolio Admin</h1>
            <p>Log in to manage your portfolio</p>
        </div>
        
        <?php if(!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="index.php" method="POST" class="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-sign-in-alt"></i> Log In
            </button>
        </form>
        
        <a href="../index.html" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Portfolio
        </a>
    </div>
</body>
</html> 