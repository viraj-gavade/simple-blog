# Simple Blog (Blogify)

## Index (Table of Contents)

- [Project Overview](#project-overview)
- [Tech Stack](#tech-stack)
- [Database](#database)
  - [Schema](#schema)
  - [Sample Data](#sample-data)
- [Installation & Run (Local)](#installation--run-local)
- [Pages & Routes](#pages--routes)
- [Application Flow & Approach](#application-flow--approach)
- [Screenshots (placeholders)](#screenshots-placeholders)
- [Security Notes](#security-notes)
- [Extending the Project](#extending-the-project)
- [Files Changed / Created](#files-changed--created)
- [Author / License](#author--license)

---

## Project Overview

Simple Blog (Blogify) is a small PHP/MySQL blogging application intended as a minimal, easy-to-run example. The homepage shows recent posts as cards with short excerpts; clicking a card opens the full-post page. Authentication supports signup/login (simple/plain-text passwords per project request).

## Tech Stack

- **Server:** Apache (via XAMPP on Windows)
- **Language:** PHP 7.x or 8.x
- **Database:** MySQL / MariaDB
- **CSS:** Bootstrap 5
- **Icons:** Bootstrap Icons

## Database

Database name used in the project: `simple_blog`.

### Schema

Run these SQL statements in your MySQL (phpMyAdmin or CLI) to create the database and tables:

```sql
CREATE DATABASE IF NOT EXISTS simple_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE simple_blog;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `posts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);
```

### Sample Data

```sql
INSERT INTO users (name, email, password) VALUES ('Admin', 'admin@example.com', 'password123');

INSERT INTO posts (user_id, title, content) VALUES
(1, 'Welcome to Blogify', 'This is the first post. Replace with your content.'),
(1, 'Second Post', 'Another example post to test listing.');
```

## Installation & Run (Local)

1. Install XAMPP (Windows). Start **Apache** and **MySQL** services.
2. Place the project folder `simple-blog` in XAMPP's `htdocs` directory (example: `C:\xampp\htdocs\simple-blog` or as in your setup `E:\XAMMP\htdocs\simple-blog`).
3. Create the database and tables (see SQL in the **Database** section). Use phpMyAdmin or MySQL CLI.
4. Open the browser: `http://localhost/simple-blog/index.php` (adjust host/path if different).

Notes:
- Database connection in the code uses: host `localhost`, user `root`, empty password, database `simple_blog`. Update connection strings in `index.php` and `auth.php` if your environment differs.

## Pages & Routes

- `index.php` — Homepage showing recent posts as cards with excerpts. Click a title or the "Read Full Post" button to open the full post.
- `view_post.php` — Displays a single post in full (requires `?id=POST_ID`).
- `auth.php` — Login & Signup UI and logic (stores plain-text passwords per current implementation).
- `add_post.php` — (present in project) Form to create new posts (requires authenticated user).
- `README.md` — This documentation file.

## Application Flow & Approach

- On homepage (`index.php`), posts are fetched with a JOIN to include the author's name and sorted by `created_at DESC`.
- Each post card displays: title (clickable), author name, date/time, excerpt (first ~250 chars), and a "Read Full Post" button linking to `view_post.php?id=`.
- `view_post.php` queries the database for the requested `id` and renders the post content.
- `auth.php` handles signup and login. For simplicity (as requested), passwords are stored and compared in plain text. Sessions are used to keep users logged in.

## Screenshots (placeholders)

Add screenshots into a `screenshots/` folder and update the file names below when creating the Word document.

- Home / Post list: `screenshots/home.png`
- Post detail page: `screenshots/post_detail.png`
- Login / Signup page: `screenshots/auth.png`
- Add post page: `screenshots/add_post.png`

To capture screenshots on Windows:

1. Open the page in your browser (e.g., `http://localhost/simple-blog/index.php`).
2. Use `Win+Shift+S` to capture, save images into `screenshots/`.
3. Insert the images in the Word document where indicated.

## Security Notes

- This project currently stores user passwords in plain text. This is insecure and only done here per explicit request for simplicity. For production use, always store hashed passwords (e.g., `password_hash()` / `password_verify()` in PHP) and use prepared statements to avoid SQL injection.
- Input is partially escaped using `mysqli_real_escape_string` in places, but many queries are constructed with string interpolation. Consider switching to prepared statements (`mysqli_prepare` / PDO) for security.

## Extending the Project

- Replace plain-text passwords with hashed passwords and update `auth.php` accordingly.
- Add prepared statements or migrate to PDO.
- Add pagination to `index.php` for many posts.
- Add image uploads and featured images for posts.
- Add user profiles and edit/delete post functionality (with permissions checks).

## Files Changed / Created (recent changes for this task)

- `index.php` — Updated to show excerpts and add links to the full post.
- `view_post.php` — NEW: Displays a single post by `id`.
- `auth.php` — Updated to store and compare plain-text passwords (per request).
- `README.md` — NEW: This documentation file.

## Author / License

This project was prepared as a simple demo. Adapt and reuse as you like. No license specified — add one if you plan to publish.

---

If you want, I can also:

- Generate Word-compatible screenshots layout (a `.docx` template) with the placeholders filled.
- Add a sample `export.docx` or `documentation.docx` containing the same content and embedded screenshots (you would need to supply the images).

Marking the README task completed will update the todo list.
# Simple Blogging Website - Setup Guide

This is a very simple blogging project for beginners. Follow these steps to run it on your computer using XAMPP.

## 1. Start XAMPP
1. Open the **XAMPP Control Panel**.
2. Click **Start** for **Apache** and **MySQL**.

## 2. Create the Database
1. Open your browser and go to: `http://localhost/phpmyadmin/`
2. Click on the **New** button in the left sidebar.
3. Enter the database name: `simple_blog` and click **Create**.

## 3. Create Tables
Inside the `simple_blog` database, run these SQL commands (click the **SQL** tab in phpMyAdmin):

### Create Users Table:
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(100)
);
```

### Create Posts Table:
```sql
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 4. Add a Test User
Run this SQL to add a default user so you can log in immediately:
```sql
INSERT INTO users (name, email, password) VALUES ('Test User', 'test@gmail.com', '12345');
```

## 5. Project Folder
1. Copy the project folder (containing `index.php`, `auth.php`, `add_post.php`) into your XAMPP installation folder:
   `C:\xampp\htdocs\simple-blog`

## 6. Run the Project
Open your browser and type:
`http://localhost/simple-blog/index.php`

---
**Login Details for Testing:**
- **Email:** test@gmail.com
- **Password:** 12345
