<?php
session_start();
// Database connection
$conn = mysqli_connect("localhost", "root", "", "simple_blog");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = "SELECT posts.*, users.name FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = $id LIMIT 1";
$result = mysqli_query($conn, $query);
$post = $result ? mysqli_fetch_assoc($result) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { 
            background: #FAF9F6;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 1.5rem;
        }
        .container-card {
            max-width: 900px;
            margin: 0 auto;
        }
        .post-card {
            border-radius: 12px;
            padding: 2rem;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }
        .post-title { font-size: 2rem; font-weight:700; }
        .post-meta { color: #718096; margin-bottom: 1.25rem; }
        .post-content { color: #4a5568; line-height:1.8; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #E36414 0%, #FB8B24 100%);">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-journal-text"></i>Blogify
        </a>
        <div class="navbar-nav ms-auto align-items-center">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="badge bg-light text-dark me-2"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a class="nav-link text-white" href="add_post.php">Add Post</a>
                <a class="nav-link text-white" href="auth.php?logout=true">Logout</a>
            <?php else: ?>
                <a class="nav-link text-white" href="auth.php">Login / Signup</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container container-card mt-4">
    <?php if ($post): ?>
        <div class="post-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                    <div class="post-meta">
                        <span class="me-3"><i class="bi bi-person-fill"></i> <?php echo htmlspecialchars($post['name']); ?></span>
                        <span class="me-3"><i class="bi bi-calendar3"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                        <span><i class="bi bi-clock"></i> <?php echo date('g:i A', strtotime($post['created_at'])); ?></span>
                    </div>
                </div>
                <div>
                    <a href="index.php" class="btn btn-outline-secondary">&larr; Back</a>
                </div>
            </div>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>
        </div>
    <?php else: ?>
        <div class="post-card text-center">
            <h3>Post not found</h3>
            <p class="text-muted">The post you are looking for does not exist.</p>
            <a href="index.php" class="btn btn-gradient">Return to Home</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
