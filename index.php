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
    <title>Blogify</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        body { 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .navbar-brand i {
            margin-right: 8px;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 5px;
            border-radius: 8px;
            padding: 8px 16px !important;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            color: white;
        }
        
        .page-header {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-left: 5px solid #667eea;
        }
        
        .page-header h2 {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            color: #718096;
            margin-bottom: 0;
        }
        
        .post-card {
            margin-bottom: 25px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            background: white;
        }
        
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .post-card .card-body {
            padding: 2rem;
        }
        
        .post-title {
            color: #2d3748;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.3;
        }
        
        .post-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            color: #718096;
            font-size: 0.9rem;
        }
        
        .meta-item i {
            margin-right: 6px;
            color: #667eea;
        }
        
        .post-content {
            color: #4a5568;
            line-height: 1.8;
            font-size: 1rem;
        }
        
        .empty-state {
            background: white;
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #cbd5e0;
            margin-bottom: 1.5rem;
        }
        
        .empty-state h3 {
            color: #2d3748;
            margin-bottom: 1rem;
        }
        
        .empty-state p {
            color: #718096;
            margin-bottom: 2rem;
        }
        
        .user-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .content-wrapper {
            padding: 2rem 0;
        }
        
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }
            
            .post-card .card-body {
                padding: 1.5rem;
            }
            
            .post-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-journal-text"></i>Blogify
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="user-badge me-2">
                        <i class="bi bi-person-circle"></i>
                        <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </span>
                    <a class="nav-link" href="add_post.php">
                        <i class="bi bi-plus-circle"></i> Add Post
                    </a>
                    <a class="nav-link" href="auth.php?logout=true">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                <?php else: ?>
                    <a class="nav-link btn-gradient" href="auth.php">
                        <i class="bi bi-person-circle"></i> Login / Signup
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container content-wrapper">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="page-header fade-in">
                <h2><i class="bi bi-newspaper"></i> Recent Blog Posts</h2>
                <p>Discover stories, thoughts, and ideas from our community</p>
            </div>
            
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $delay = 0; ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="card post-card fade-in" style="animation-delay: <?php echo $delay; ?>s">
                        <div class="card-body">
                            <h3 class="post-title">
                                <a href="view_post.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </a>
                            </h3>
                            <div class="post-meta">
                                <span class="meta-item">
                                    <i class="bi bi-person-fill"></i>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-calendar3"></i>
                                    <?php echo date('F j, Y', strtotime($row['created_at'])); ?>
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-clock"></i>
                                    <?php echo date('g:i A', strtotime($row['created_at'])); ?>
                                </span>
                            </div>
                            <div class="post-content">
                                <?php
                                    $excerpt = strlen($row['content']) > 250 ? substr($row['content'], 0, 250) . '...' : $row['content'];
                                    echo nl2br(htmlspecialchars($excerpt));
                                ?>
                                <div class="mt-3">
                                    <a href="view_post.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-gradient">
                                        Read Full Post
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $delay += 0.1; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state fade-in">
                    <i class="bi bi-inbox"></i>
                    <h3>No Posts Yet</h3>
                    <p>Be the first to share your thoughts and ideas!</p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="add_post.php" class="btn btn-gradient">
                            <i class="bi bi-plus-circle"></i> Write Your First Post
                        </a>
                    <?php else: ?>
                        <a href="auth.php" class="btn btn-gradient">
                            <i class="bi bi-person-circle"></i> Login to Post
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>