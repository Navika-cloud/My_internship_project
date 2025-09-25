# ApexPlanet - Task 4: Security Enhancements

## Overview
**Task 4** focuses on improving the security of the ApexPlanet web application. This includes protection against common web vulnerabilities, implementing form validation, and introducing user roles with proper permissions.

---

## Timeline
**Duration:** 10 Days  
**Task:** Secure the application against SQL injections, enforce validation, and implement role-based access control.

---

## Objectives
- Secure all database interactions using **prepared statements** (MySQLi / PDO).  
- Implement **server-side validation** for all user input.  
- Add **client-side validation** for better user experience.  
- Introduce **user roles** (admin, user) and **role-based permissions**:
  - Admins can edit or delete any post.  
  - Users can edit or delete only their own posts.  
- Implement **CSRF protection** for all forms.  
- Ensure **session security** with proper cookie settings and timeout.

---

## Implementation Steps
1. **Database Security**
   - All queries use **prepared statements** to prevent SQL injection.
   - User passwords are **hashed** using `password_hash()`.

2. **Form Validation**
   - Server-side checks for empty fields and length constraints.
   - Client-side validation using HTML5 attributes.

3. **Role-Based Access**
   - Users are assigned the `user` role by default.
   - Admins can manage all posts.
   - Users can manage only their own posts.
   - Edit/Delete buttons are displayed conditionally based on role.

4. **CSRF Protection**
   - All forms include a **CSRF token** stored in the session.
   - Tokens are validated on form submission.

5. **Session Security**
   - Session cookies are set with `httponly` and `samesite` attributes.
   - Session timeout implemented (30 minutes inactivity).
   - `session_regenerate_id()` is used on login to prevent fixation.

6. **User Experience**
   - Bootstrap 5 used for responsive design.
   - Modals used for adding/editing posts.
   - Search functionality implemented with prepared statements.

---

## Deliverables
- Fully secured **index.php** with author display and role-based edit/delete.  
- **register.php** and **login.php** with secure registration and login.  
- **edit.php** and **delete_post.php** with proper permission checks.  
- CSRF-protected forms for all user actions.  
- `config.php` with secure session settings.  

---

## Usage
1. Clone the repository into your local server (e.g., XAMPP).  
2. Import `apexplanet_db` database in MySQL.  
3. Open `register.php` to create a new user.  
4. Login via `login.php`.  
5. Add, edit, delete, or like posts according to your role.

---

## Security Notes
- All user inputs are sanitized using `htmlspecialchars()` to prevent XSS.  
- Prepared statements prevent SQL injection.  
- CSRF tokens prevent unauthorized form submissions.  
- Session cookies and timeouts improve overall security.

---

**ApexPlanet**  
Software Pvt Ltd
