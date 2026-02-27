<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "simple_blog");

// LOGOUT LOGIC
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$message = "";

// SIGNUP LOGIC
if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($name) && !empty($email) && !empty($password)) {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            $message = "<div class='alert alert-success'>Signup successful! Please login.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: Email might already exist.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>All fields are required!</div>";
    }
}

// LOGIN LOGIC
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: index.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Invalid email or password!</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Please fill in all fields!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup - Simple Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding-top: 50px; }
        .auth-container { max-width: 800px; margin: auto; }
        .card { margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container auth-container">
    <div class="text-center mb-4">
        <h2>College Blog Portal</h2>
        <a href="index.php" class="btn btn-link">Back to Home</a>
    </div>

    <?php echo $message; ?>

    <div class="row">
        <!-- Login Form -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white"><h4>Login</h4></div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Signup Form -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white"><h4>Signup</h4></div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="signup" class="btn btn-success w-100">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
