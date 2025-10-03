<?php
session_start();
include "config.php";

// Force login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Handle Add Post
if (isset($_POST['add_post'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("⚠️ Invalid CSRF token");
    }

    $title = trim($_POST['post_title']);
    $content = trim($_POST['post_content']);

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $title, $content);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit;
    } else {
        $error = "⚠️ Title and content cannot be empty.";
    }
}

// Handle Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Handle Search
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
if ($search) {
    $stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE (title LIKE ? OR content LIKE ?) ORDER BY created_at DESC");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
} else {
    $stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC");
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ApexPlanet - My Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>👋 Welcome, <?= htmlspecialchars($username) ?> (<?= htmlspecialchars($role) ?>)</h4>
        </div>
        <form method="post" class="mb-0">
            <button type="submit" name="logout" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </div>

    <!-- Add Post Button & Search -->
    <div class="d-flex mb-4 gap-2">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPostModal">
            <i class="bi bi-plus-circle"></i> Add New Post
        </button>
        <form class="d-flex flex-grow-1" method="get">
            <input type="text" name="search" class="form-control me-2" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
        </form>
    </div>

    <!-- Posts List -->
    <?php if ($result->num_rows): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($row['content']) ?></p>
                    <p class="text-muted mb-1">Author: <?= htmlspecialchars($row['username']) ?> | <small><?= date('M d, Y H:i', strtotime($row['created_at'])) ?></small></p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-danger btn-sm like-btn" data-id="<?= $row['id'] ?>">
                            <i class="bi bi-heart"></i> <span class="like-count"><?= $row['likes'] ?? 0 ?></span>
                        </button>
                        <?php if ($role === 'admin' || $row['user_id'] == $user_id): ?>
                            <button type="button" class="btn btn-outline-primary btn-sm edit-btn" data-id="<?= $row['id'] ?>" data-title="<?= htmlspecialchars($row['title']) ?>" data-content="<?= htmlspecialchars($row['content']) ?>">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm delete-btn" data-id="<?= $row['id'] ?>" data-csrf="<?= $_SESSION['csrf_token'] ?>">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-warning">No posts found.</div>
    <?php endif; ?>
</div>

<!-- Add Post Modal -->
<div class="modal fade" id="addPostModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Add New Post</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="post_title" class="form-control" maxlength="80" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="post_content" class="form-control" rows="4" maxlength="500" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_post" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Post</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Like button
document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const postId = this.dataset.id;
        fetch('like_post.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + encodeURIComponent(postId) + '&csrf_token=<?= $_SESSION['csrf_token'] ?>'
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.querySelector('.like-count').textContent = data.likes;
            } else {
                alert('Failed to like post.');
            }
        });
    });
});

// Delete button
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this post?')) {
            const postId = this.dataset.id;
            const csrfToken = this.dataset.csrf;
            fetch('delete_post.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + encodeURIComponent(postId) + '&csrf_token=' + encodeURIComponent(csrfToken)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.closest('.card').remove();
                } else {
                    alert(data.msg);
                }
            });
        }
    });
});

// Edit button
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelector('[name="post_title"]').value = this.dataset.title;
        document.querySelector('[name="post_content"]').value = this.dataset.content;
        document.querySelector('form.modal-content').action = "edit.php?id=" + this.dataset.id;
        var modal = new bootstrap.Modal(document.getElementById('addPostModal'));
        modal.show();
    });
});
</script>
</body>
</html>
<?php $conn->close(); ?>
