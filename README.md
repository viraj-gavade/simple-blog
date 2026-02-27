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
