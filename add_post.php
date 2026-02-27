<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "simple_blog");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";
$success = "";

// Handle post submission
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $user_id = $_SESSION['user_id'];
    
    if (empty($title) || empty($content)) {
        $error = "Both title and content are required!";
    } else {
        $query = "INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
        if (mysqli_query($conn, $query)) {
            $success = "Post published successfully!";
            // Clear form
            $_POST['title'] = '';
            $_POST['content'] = '';
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
    <title>Add New Post - Blogify</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #E36414 0%, #FB8B24 100%);
        }
        
        body { 
            background: #FAF9F6;
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
        
        .user-badge {
            background: linear-gradient(135deg, #E36414 0%, #FB8B24 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .content-wrapper {
            padding: 2rem 0;
        }
        
        .post-form-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 3rem;
            margin-bottom: 2rem;
        }
        
        .form-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 3px solid #f7fafc;
        }
        
        .form-header h2 {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .form-header p {
            color: #718096;
            margin: 0;
        }
        
        .form-label {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-control, .form-control:focus {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: #E36414;
            box-shadow: 0 0 0 4px rgba(227, 100, 20, 0.1);
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 250px;
            line-height: 1.8;
        }
        
        .char-counter {
            text-align: right;
            color: #a0aec0;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(227, 100, 20, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(227, 100, 20, 0.6);
            color: white;
        }
        
        .btn-outline {
            border: 2px solid #e2e8f0;
            background: white;
            color: #4a5568;
            font-weight: 600;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-outline:hover {
            border-color: #cbd5e0;
            background: #f7fafc;
            color: #2d3748;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert i {
            font-size: 1.25rem;
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
        
        .form-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #f7fafc;
        }
        
        .writing-tips {
            background: linear-gradient(135deg, #E3641410 0%, #FB8B2420 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .writing-tips h5 {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .writing-tips ul {
            margin: 0;
            padding-left: 1.5rem;
            color: #4a5568;
        }
        
        .writing-tips li {
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .post-form-card {
                padding: 2rem 1.5rem;
            }
            
            .form-buttons {
                flex-direction: column;
            }
            
            .btn-gradient, .btn-outline {
                width: 100%;
                justify-content: center;
            }
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
                <span class="user-badge me-2">
                    <i class="bi bi-person-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <a class="nav-link" href="index.php">
                    <i class="bi bi-house-fill"></i> Home
                </a>
                <a class="nav-link" href="auth.php?logout=true">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container content-wrapper">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="post-form-card fade-in">
                <div class="form-header">
                    <h2>
                        <i class="bi bi-pencil-square"></i>
                        Create New Post
                    </h2>
                    <p>Share your thoughts, ideas, and stories with the community</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill"></i>
                        <span><?php echo $success; ?></span>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="postForm">
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-type"></i>
                            Post Title
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="title" 
                            id="titleInput"
                            placeholder="Enter an engaging title for your post"
                            required
                            maxlength="200"
                            value="<?php echo $success ? '' : (isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''); ?>"
                        >
                        <div class="char-counter">
                            <span id="titleCounter">0</span> / 200 characters
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-file-text"></i>
                            Post Content
                        </label>
                        <textarea 
                            class="form-control" 
                            name="content" 
                            id="contentInput"
                            placeholder="Write your post content here... Share your experiences, thoughts, and ideas."
                            required
                        ><?php echo $success ? '' : (isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''); ?></textarea>
                        <div class="char-counter">
                            <span id="contentCounter">0</span> characters
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" name="submit" class="btn-gradient">
                            <i class="bi bi-send-fill"></i>
                            Publish Post
                        </button>
                        <a href="index.php" class="btn-outline">
                            <i class="bi bi-x-circle"></i>
                            Cancel
                        </a>
                    </div>
                </form>
                
                <div class="writing-tips">
                    <h5>
                        <i class="bi bi-lightbulb-fill"></i>
                        Writing Tips
                    </h5>
                    <ul>
                        <li>Keep your title clear and descriptive</li>
                        <li>Break long content into paragraphs for better readability</li>
                        <li>Proofread before publishing</li>
                        <li>Be respectful and constructive in your posts</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Character counter for title
    const titleInput = document.getElementById('titleInput');
    const titleCounter = document.getElementById('titleCounter');
    
    titleInput.addEventListener('input', function() {
        titleCounter.textContent = this.value.length;
    });
    
    // Initialize title counter
    titleCounter.textContent = titleInput.value.length;
    
    // Character counter for content
    const contentInput = document.getElementById('contentInput');
    const contentCounter = document.getElementById('contentCounter');
    
    contentInput.addEventListener('input', function() {
        contentCounter.textContent = this.value.length;
    });
    
    // Initialize content counter
    contentCounter.textContent = contentInput.value.length;
    
    // Auto-hide success message after 3 seconds
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.transition = 'opacity 0.5s ease';
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 500);
        }, 3000);
    }
</script>
</body>
</html>
