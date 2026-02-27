<?php
session_start();
// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "simple_blog");
$user_id = $_SESSION['user_id'];

// HANDLE ADD POST
if (isset($_POST['add_post'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (!empty($title) && !empty($content)) {
        $sql = "INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
        mysqli_query($conn, $sql);
        header("Location: index.php");
        exit();
    }
}

// HANDLE DELETE POST
if (isset($_GET['delete'])) {
    $post_id = $_GET['delete'];
    // Only delete if the post belongs to the logged-in user
    $sql = "DELETE FROM posts WHERE id='$post_id' AND user_id='$user_id'";
    mysqli_query($conn, $sql);
    header("Location: add_post.php");
    exit();
}

// Fetch only user's posts for deletion management
$query = "SELECT * FROM posts WHERE user_id='$user_id' ORDER BY created_at DESC";
$my_posts = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts - Simple Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 50px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid mx-5">
        <a class="navbar-brand" href="index.php">My College Blog</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php">Home</a>
            <a class="nav-link" href="auth.php?logout=true">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <!-- Add Post Form -->
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white"><h4>Create New Post</h4></div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Post Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Content</label>
                            <textarea name="content" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" name="add_post" class="btn btn-primary w-100">Publish Post</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- My Posts List -->
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-dark text-white"><h4>My Posts</h4></div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($my_posts)): ?>
                                <tr>
                                    <td><?php echo $row['title']; ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <a href="add_post.php?delete=<?php echo $row['id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Delete this post?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if (mysqli_num_rows($my_posts) == 0): ?>
                                <tr><td colspan="3" class="text-center">You haven't posted anything yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
