Absolutely! Here’s a **complete README covering Task 1 to Task 5**, summarized and integrated so that Task 5 clearly shows the full project flow and features. You can use this for your final submission.

---

# Internship – Blog Web Application (Task 1 to Task 5)

## Overview

This web-based **Blog Application** was developed as part of an internship project. The project spans **Task 1 to Task 5**, gradually building features and integrating them into a fully functional application. The final Task 5 integrates all previous features and adds enhancements like dynamic likes and role-based management.

---

## Task-wise Summary

### **Task 1 – Setup and Basic User Registration**

**Objective:** Set up the project environment and implement user registration.

**Features:**

* Setup XAMPP server (Apache + MySQL) and project directory.
* Create MySQL database `blog` and tables: `users` (id, username, password, role).
* Implement **registration form** with username and password validation.
* Passwords stored securely using bcrypt hashing.

**Output:**

* Users can register successfully and login later.

---

### **Task 2 – User Login and Session Management**

**Objective:** Implement login, logout, and basic session handling.

**Features:**

* Login form with **username, password, and role selection (Admin/User)**.
* Session management to maintain logged-in state.
* Logout functionality.

**Output:**

* Users can login based on role.
* Session controls access to pages; unauthorized access redirects to login.

---

### **Task 3 – CRUD Operations for Posts**

**Objective:** Allow users to create, read, update, and delete posts.

**Features:**

* **Create Post:** Users can submit title and content.
* **Read Posts:** Posts displayed in a dashboard with pagination.
* **Edit Post:** Users can edit their own posts; Admin can edit any post.
* **Delete Post:** Users can delete their own posts; Admin can delete any post.
* Input validation for posts to ensure all fields are filled.

**Output:**

* Users can fully manage their posts.
* Admin has control over all posts.

---

### **Task 4 – Search, Filter, and Pagination**

**Objective:** Enhance usability by adding search, filter, and pagination features.

**Features:**

* **Search posts** by title or content.
* **Filter posts**: show all posts or only posts created by the logged-in user.
* **Pagination:** Display 5 posts per page; navigate using page links.

**Output:**

* Users can quickly find relevant posts.
* Dashboard displays posts cleanly with pagination controls.

---

### **Task 5 – Integration, Likes System, and Final Testing**

**Objective:** Integrate all features and add dynamic interactions (likes).

**Features:**

* **Dynamic Likes System**:

  * Users can like or unlike posts (1 user = 1 like).
  * Heart icon changes color to indicate liked posts.
  * Like count updates dynamically using AJAX; page does not refresh.
* **Final Integration:** All previous features (CRUD, login, roles, search, filter, pagination) fully integrated.
* **Security & Validation:** Session-based access control, role-based permissions, input validations, password hashing.
* **Testing & Debugging:** Checked all functionality, ensured smooth UX.

**Output:**

* Fully functional blog application.
* Dynamic like count for posts.
* Admin and user roles implemented.
* Posts displayed with pagination, search, and filter options.

## Technologies Used

* **Frontend:** HTML, CSS, JavaScript (AJAX for likes)
* **Backend:** PHP (PDO for MySQL interaction)
* **Database:** MySQL / MariaDB
* **Server:** XAMPP (Apache + MySQL)

---

## Installation Instructions

1. Copy project to `htdocs` folder:

   ```
   C:\xampp\htdocs\internship-task5
   ```
2. Start **Apache** and **MySQL** servers via XAMPP.
3. Create database `blog` in **phpMyAdmin**.
4. Create tables:

   * **users**: `id`, `username`, `password`, `role`
   * **posts**: `id`, `title`, `content`, `username`, `created_at`, `likes`
5. Update `config.php` with your DB credentials:

   ```php
   $host = 'localhost';
   $db   = 'blog';
   $user = 'root';
   $pass = '';
   $charset = 'utf8mb4';
   ```
6. Open in browser: `http://localhost/internship-task5/login.php`

---

## Usage

* Register or login.
* Create, edit, or delete posts according to role.
* Like/unlike posts; count updates dynamically without refreshing.
* Search and filter posts; navigate using pagination.

---

## Author

**Sunil Sannapaneni**