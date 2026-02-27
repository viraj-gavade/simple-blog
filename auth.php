<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "simple_blog");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";
$success = "";

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Handle login (plain-text password, simple implementation)
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }
}

// Handle signup (store plain-text password for simplicity)
if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if email already exists
    $check = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        $error = "Email already registered!";
    } else {
        $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            $success = "Account created successfully! Please login.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Signup - Blogify</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #E36414 0%, #FB8B24 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        body { 
            background: linear-gradient(135deg, #E36414 0%, #FB8B24 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .auth-container {
            width: 100%;
            max-width: 450px;
        }
        
        .auth-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            position: relative;
        }
        
        .auth-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .auth-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.8rem;
        }
        
        .auth-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }
        
        .auth-body {
            padding: 2.5rem;
        }
        
        .form-switch-tabs {
            display: flex;
            margin-bottom: 2rem;
            background: #f7fafc;
            border-radius: 15px;
            padding: 5px;
            position: relative;
        }
        
        .tab-button {
            flex: 1;
            padding: 12px;
            border: none;
            background: transparent;
            color: #718096;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }
        
        .tab-button.active {
            color: white;
        }
        
        .tab-indicator {
            position: absolute;
            height: calc(100% - 10px);
            width: calc(50% - 5px);
            background: var(--primary-gradient);
            border-radius: 12px;
            top: 5px;
            left: 5px;
            transition: transform 0.3s ease;
            z-index: 1;
        }
        
        .tab-indicator.signup-active {
            transform: translateX(100%);
        }
        
        .form-container {
            position: relative;
            overflow: hidden;
        }
        
        .auth-form {
            display: none;
            animation: slideIn 0.4s ease;
        }
        
        .auth-form.active {
            display: block;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .form-label {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: #E36414;
            box-shadow: 0 0 0 4px rgba(227, 100, 20, 0.1);
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }
        
        .input-icon .form-control {
            padding-left: 45px;
        }
        
        .btn-auth {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(227, 100, 20, 0.4);
        }
        
        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(227, 100, 20, 0.6);
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: white;
            transform: translateX(-5px);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger {
            background: #fff5f5;
            color: #c53030;
            border-left: 4px solid #fc8181;
        }
        
        .alert-success {
            background: #f0fff4;
            color: #22543d;
            border-left: 4px solid #68d391;
        }
        
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e2e8f0;
        }
        
        .divider span {
            background: white;
            padding: 0 1rem;
            color: #a0aec0;
            position: relative;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <a href="index.php" class="back-link">
        <i class="bi bi-arrow-left"></i> Back to Blog
    </a>
    
    <div class="auth-card">
        <div class="auth-header">
            <h2><i class="bi bi-journal-text"></i> Blogify</h2>
            <p>Share your thoughts and ideas</p>
        </div>
        
        <div class="auth-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-switch-tabs">
                <div class="tab-indicator" id="tabIndicator"></div>
                <button class="tab-button active" id="loginTab" onclick="switchForm('login')">
                    Login
                </button>
                <button class="tab-button" id="signupTab" onclick="switchForm('signup')">
                    Sign Up
                </button>
            </div>
            
            <div class="form-container">
                <!-- Login Form -->
                <form method="POST" action="" class="auth-form active" id="loginForm">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <div class="input-icon">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-icon">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="login" class="btn-auth">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>
                
                <!-- Signup Form -->
                <form method="POST" action="" class="auth-form" id="signupForm">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <div class="input-icon">
                            <i class="bi bi-person-fill"></i>
                            <input type="text" class="form-control" name="name" placeholder="Enter your name" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <div class="input-icon">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-icon">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" class="form-control" name="password" placeholder="Create a password" required minlength="6">
                        </div>
                    </div>
                    
                    <button type="submit" name="signup" class="btn-auth">
                        <i class="bi bi-person-plus-fill"></i> Create Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function switchForm(formType) {
        const loginForm = document.getElementById('loginForm');
        const signupForm = document.getElementById('signupForm');
        const loginTab = document.getElementById('loginTab');
        const signupTab = document.getElementById('signupTab');
        const tabIndicator = document.getElementById('tabIndicator');
        
        if (formType === 'login') {
            loginForm.classList.add('active');
            signupForm.classList.remove('active');
            loginTab.classList.add('active');
            signupTab.classList.remove('active');
            tabIndicator.classList.remove('signup-active');
        } else {
            signupForm.classList.add('active');
            loginForm.classList.remove('active');
            signupTab.classList.add('active');
            loginTab.classList.remove('active');
            tabIndicator.classList.add('signup-active');
        }
    }
    
    // Auto-switch to signup if there's a success message
    <?php if ($success): ?>
        switchForm('login');
    <?php endif; ?>
</script>
</body>
</html>