<?php
session_start();
// Database connection
$conn = mysqli_connect("localhost", "root", "", "simple_blog");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all posts
$query = "SELECT posts.*, users.name FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Blog</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { margin-bottom: 30px; }
        .post-card { margin-bottom: 20px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">My College Blog</a>
        <div class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="nav-link text-white">Welcome, <?php echo $_SESSION['user_name']; ?></span>
                <a class="nav-link" href="add_post.php">Add Post</a>
                <a class="nav-link" href="auth.php?logout=true">Logout</a>
            <?php else: ?>
                <a class="nav-link" href="auth.php">Login / Signup</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="mb-4">Recent Blog Posts</h2>
            
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="card post-card shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo $row['title']; ?></h3>
                            <p class="text-muted small">By <?php echo $row['name']; ?> on <?php echo $row['created_at']; ?></p>
                            <p class="card-text"><?php echo nl2br($row['content']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info">No posts yet. Be the first to write one!</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
