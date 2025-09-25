# ApexPlanet - Task 3

ApexPlanet Task 3 is a PHP & MySQL mini-blog platform with a modern Bootstrap interface. Users can log in, create, edit, like, and delete posts. The UI is interactive, featuring modals, AJAX actions, and a responsive layout.

---

## Features

- User authentication (login/logout)
- Personalized welcome message with user’s name and avatar
- Add new post (modal form)
- Edit and delete posts (only your own)
- Like posts (AJAX, instant update)
- Responsive, modern UI with Bootstrap 5
- Search posts by title/content
- Dark mode toggle

---

## Setup Instructions

### 1. Clone or Copy the Project

Copy the project folder to your XAMPP `htdocs` directory, e.g.:
```
c:\xamp\htdocs\apexplanet\Task3\
```

### 2. Create the Database

- Open [phpMyAdmin](http://localhost/phpmyadmin)
- Click "New", enter `apexplanet` as the database name, and click "Create"

### 3. Create the Tables

**Users Table:**
```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  name VARCHAR(100)
);
```

**Posts Table:**
```sql
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  title VARCHAR(255),
  content TEXT,
  likes INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 4. Add a User

Insert a user for login (password should be hashed in production):

```sql
INSERT INTO users (username, password, name) VALUES
('admin', 'admin123', 'Admin User');
```
*(For real use, hash the password!)*

### 5. Configure Database Connection

In all PHP files that connect to MySQL (e.g., `like_post.php`, `delete_post.php`, etc.), ensure the database name, username, and password match your setup:

```php
$conn = new mysqli("localhost", "root", "", "apexplanet");
```

### 6. Run the App

Visit [http://localhost/apexplanet/Task3/](http://localhost/apexplanet/Task3/) in your browser.

---

## Usage

- **Login** with your username and password.
- **Add a new post** using the "Add New Post" button.
- **Like** a post by clicking the heart icon (AJAX, instant update).
- **Edit** or **delete** your own posts using the pencil and trash icons.
- **Search** posts using the search bar.
- **Toggle dark mode** with the moon icon.

---

## File Structure

```
Task3/
├── index.php           # Main dashboard and post listing
├── login.php           # User authentication
├── like_post.php       # Handles AJAX like requests
├── delete_post.php     # Handles AJAX delete requests
├── README.md           # This file
└── ...                 # Other supporting files (CSS, JS, etc.)
```

---

## Troubleshooting

- **Database errors:**  
  Make sure the database and tables exist, and the connection info is correct.
- **AJAX not working:**  
  Check that `like_post.php` and `delete_post.php` are in the correct folder and accessible.
- **"Invalid ID" error:**  
  The like/delete scripts must be called via POST with a valid `id` parameter.
- **XAMPP not running:**  
  Start Apache and MySQL from the XAMPP control panel.

---

## Security Notes

- Always hash passwords in production.
- Validate and sanitize all user input.
- Restrict edit/delete actions to the post owner.

---

## Credits

- Built with PHP, MySQL, Bootstrap 5, and vanilla JavaScript.
