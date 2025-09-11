<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate login for demo (remove this in production)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Replace with actual login logic
    $_SESSION['username'] ;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "apexplanet_db"; // Change if needed

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle Add Post
if (isset($_POST['add_post'])) {
    $title = $conn->real_escape_string($_POST['post_title']);
    $content = $conn->real_escape_string($_POST['post_content']);
    $user_id = $_SESSION['user_id'];
    $conn->query("INSERT INTO posts (user_id, title, content) VALUES ($user_id, '$title', '$content')");
    header("Location: index.php");
    exit;
}

// Handle Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Handle Search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$user_id = $_SESSION['user_id'];
$where = "WHERE user_id = $user_id";
if ($search) {
    $search_esc = $conn->real_escape_string($search);
    $where .= " AND (title LIKE '%$search_esc%' OR content LIKE '%$search_esc%')";
}

// Fetch posts for this user
$result = $conn->query("SELECT * FROM posts $where ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ApexPlanet - My Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
body {
    background: #f6f8fa;
}
.main-card {
    max-width: 700px;
    margin: 2rem auto;
    background: #fff;
    border-radius: 1.5rem;
    box-shadow: 0 4px 32px rgba(0,0,0,0.07);
    padding: 2.5rem 2rem;
}
.user-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
}
.user-info {
    display: flex;
    align-items: center;
    gap: 1.2rem;
}
.user-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
}
.welcome {
    font-size: 1.4rem;
    font-weight: 600;
    color: #22223b;
}
.badge-admin {
    background: #22223b;
    font-size: 1rem;
    padding: 0.35em 0.8em;
    border-radius: 0.7em;
    margin-left: 0.7em;
    display: inline-flex;
    align-items: center;
    gap: 0.3em;
}
.btn-logout {
    font-size: 1.1rem;
    padding: 0.6em 1.4em;
    border-radius: 0.7em;
    font-weight: 500;
}
.action-bar {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}
.btn-add {
    font-size: 1.1rem;
    padding: 0.7em 1.5em;
    border-radius: 0.7em;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5em;
}
.search-bar {
    flex: 1;
    display: flex;
    gap: 0.5rem;
}
.search-bar input {
    border-radius: 0.7em;
    font-size: 1.1rem;
    padding: 0.6em 1em;
}
.btn-search {
    font-size: 1.1rem;
    padding: 0.6em 1.4em;
    border-radius: 0.7em;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5em;
}
.btn-darkmode {
    border-radius: 0.7em;
    font-size: 1.3rem;
    padding: 0.6em 1em;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    color: #22223b;
    transition: background 0.2s, color 0.2s;
}
.btn-darkmode:hover {
    background: #22223b;
    color: #fff;
}
hr {
    margin: 2rem 0 1.5rem 0;
}
h4 {
    font-weight: 700;
    margin-bottom: 1.5rem;
}
.post-card {
    background: #fcfcfc;
    border-radius: 1.2rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    padding: 1.5rem 1.2rem;
    margin-bottom: 1.5rem;
    transition: box-shadow 0.2s, transform 0.2s;
}
.post-card:hover {
    box-shadow: 0 8px 32px rgba(13,110,253,0.10);
    transform: translateY(-2px) scale(1.01);
}
.post-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.3rem;
    color: #22223b;
}
.post-content {
    font-size: 1.05rem;
    color: #444;
    margin-bottom: 1rem;
}
.post-meta {
    display: flex;
    align-items: center;
    gap: 1.2rem;
}
.post-meta small {
    color: #888;
    font-size: 0.98rem;
    display: flex;
    align-items: center;
    gap: 0.3em;
}
.post-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.7rem;
}
.post-actions .btn {
    border-radius: 0.6em;
    font-size: 1.1rem;
    padding: 0.3em 0.9em;
    display: flex;
    align-items: center;
    gap: 0.4em;
    transition: background 0.15s, color 0.15s;
}
.post-actions .btn-outline-danger:hover,
.post-actions .btn-outline-primary:hover,
.post-actions .btn-outline-secondary:hover {
    background: #f1f1f1;
}
@media (max-width: 600px) {
    .main-card { padding: 1rem 0.3rem; }
    .user-header, .action-bar { flex-direction: column; gap: 1rem; }
    .search-bar { flex-direction: column; }
}
    </style>
</head>
<body>
<div class="main-card bg-white p-4 rounded shadow-sm">
    <!-- User Header -->
    <div class="d-flex justify-content-between align-items-center mb-3 user-header">
        <div class="d-flex align-items-center gap-3 user-info">
            <img src="https://i.pravatar.cc/56?u=<?= urlencode($_SESSION['username']) ?>" class="user-avatar" alt="avatar">
            <span class="welcome">👋 Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!!!</span>
        </div>
        <form method="post" class="mb-0">
            <button type="submit" name="logout" class="btn btn-danger btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </div>

    <!-- Actions -->
    <div class="d-flex flex-wrap gap-2 mb-4 action-bar">
        <button class="btn btn-success btn-lg btn-add" data-bs-toggle="modal" data-bs-target="#addPostModal">
            <i class="bi bi-plus-circle"></i> Add New Post
        </button>
        <form class="d-flex flex-grow-1 search-bar" method="get" action="" style="gap: 0.5rem;">
            <input type="text" name="search" class="form-control form-control-lg" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary btn-lg btn-search" type="submit"><i class="bi bi-search"></i> Search</button>
        </form>
        <button class="btn btn-darkmode" id="toggleDark"><i class="bi bi-moon"></i></button>
    </div>
    <hr>

    <!-- Posts List -->
    <h4 class="mb-3">All Posts</h4>
    <?php if ($result->num_rows): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="post-card">
                <div class="post-title"><?= htmlspecialchars($row['title']) ?></div>
                <div class="post-content"><?= htmlspecialchars($row['content']) ?></div>
                <div class="post-meta">
                    <small><i class="bi bi-clock"></i> <?= date('M d, Y H:i', strtotime($row['created_at'])) ?></small>
                </div>
                <div class="post-actions">
                    <button type="button" class="btn btn-outline-danger like-btn" data-id="<?= $row['id'] ?>">
                        <i class="bi bi-heart"></i> <span class="like-count"><?= $row['likes'] ?? 0 ?></span>
                    </button>
                    <button type="button" class="btn btn-outline-primary edit-btn" data-id="<?= $row['id'] ?>">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger delete-btn" data-id="<?= $row['id'] ?>">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-warning">No posts found.</div>
    <?php endif; ?>
</div>

<!-- Add Post Modal -->
<div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content shadow-lg rounded-4 animate__animated animate__fadeInDown" method="post" action="">
      <div class="modal-header bg-success text-white rounded-top-4">
        <h5 class="modal-title d-flex align-items-center gap-2" id="addPostModalLabel">
          <i class="bi bi-pencil-square"></i> Add New Post
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pb-0">
        <p class="text-secondary mb-4"><i class="bi bi-lightbulb"></i> Share your thoughts with the community!</p>
        <div class="mb-3">
          <label for="postTitle" class="form-label fw-semibold">Title</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-type"></i></span>
            <input type="text" class="form-control" id="postTitle" name="post_title" maxlength="80" required oninput="titleCount.value = this.value.length">
          </div>
          <small class="text-muted float-end"><output id="titleCount">0</output>/80</small>
        </div>
        <div class="mb-3">
          <label for="postContent" class="form-label fw-semibold">Content</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
            <textarea class="form-control" id="postContent" name="post_content" rows="4" maxlength="500" required oninput="contentCount.value = this.value.length"></textarea>
          </div>
          <small class="text-muted float-end"><output id="contentCount">0</output>/500</small>
        </div>
        <input type="hidden" name="edit_post_id" id="editPostId">
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="submit" name="add_post" class="btn btn-success px-4 py-2 fw-semibold">
          <i class="bi bi-plus-circle"></i> Add Post
        </button>
      </div>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Dark mode script -->
<script>
document.getElementById('toggleDark').onclick = function() {
    document.body.classList.toggle('bg-dark');
    document.body.classList.toggle('text-white');
    document.querySelectorAll('.main-card, .post-card').forEach(el => {
        el.classList.toggle('bg-dark');
        el.classList.toggle('text-white');
    });
};
// Like button
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const postId = this.dataset.id;
            fetch('like_post.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + encodeURIComponent(postId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.querySelector('.like-count').textContent = data.likes;
                } else {
                    alert('Failed to like post.');
                }
            })
            .catch(() => alert('Error connecting to server.'));
        });
    });
});

// Delete button
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if(confirm('Are you sure you want to delete this post?')) {
                const postId = this.dataset.id;
                fetch('delete_post.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'id=' + encodeURIComponent(postId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.post-card').remove();
                    } else {
                        alert('Failed to delete post.');
                    }
                })
                .catch(() => alert('Error connecting to server.'));
            }
        });
    });
});

// Edit button
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.onclick = function() {
        const postId = this.dataset.id;
        const title = this.dataset.title;
        const content = this.dataset.content;
        // Fill modal fields
        document.getElementById('postTitle').value = title;
        document.getElementById('postContent').value = content;
        document.getElementById('editPostId').value = postId;
        // Show modal
        var modal = new bootstrap.Modal(document.getElementById('addPostModal'));
        modal.show();
    }
});
</script>
</body>
</html>
<?php $conn->close(); ?>