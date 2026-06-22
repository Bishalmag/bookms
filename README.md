# 📚 Book Management System

A web-based **Book Management System** built using **PHP and MySQL** that allows users and administrators to manage books, authors, and categories efficiently. The system also includes **user ratings & reviews** and implements **search and filtering algorithms** for better data retrieval.

---

## 🚀 Features

- 📖 Add, edit, delete books (CRUD operations)
- 🧑 Manage authors
- 📚 Manage categories
- 🔍 Search books by title, author, or category
- 🎯 Filter books based on categories and attributes
- ⭐ User ratings and reviews system
- 📊 Structured relational database design
- ⚡ Efficient data retrieval using SQL queries and filtering logic

---

## 🛠️ Tech Stack

- PHP (Backend)
- MySQL (Database)
- HTML
- CSS
- JavaScript (basic interactivity)

---

## 🧠 Algorithms & Concepts Used

This project applies several fundamental programming and database concepts:

- 🔎 **Search Algorithm**
  - Implemented using SQL `LIKE` queries for pattern matching
  - Enables fast lookup of books by title, author, or category

- 🎯 **Filtering Algorithm**
  - Data filtering based on selected categories, authors, or price ranges
  - Uses conditional SQL queries for optimized results

- 📊 **Sorting Logic**
  - Books can be sorted by title, price, or rating (if implemented)

- 🔗 **Relational Database Design**
  - Proper use of foreign keys between:
    - Books ↔ Authors
    - Books ↔ Categories
    - Books ↔ Ratings

---

## 📂 Database Structure

### 🧑 Authors Table
- author_id
- name

### 📚 Categories Table
- category_id
- type
- description

### 📖 Books Table
- book_id
- title
- isbn
- price
- author_id
- category_id
- publication_year
- publisher
- copies_available
- total_copies
- image

### ⭐ Ratings Table
- rating_id
- user_id
- book_id
- ratings
- reviews
- rated_on

---

## ⚙️ Installation & Setup

### 1. Clone the repository
```bash
git clone https://github.com/your-username/book-management-system.git
2. Move project to server

Place the project in:

XAMPP: htdocs/
WAMP: www/
3. Start server
Start Apache
Start MySQL
4. Create Database
Open phpMyAdmin
Create a database (e.g., book_system)
Import the SQL file (if provided)
5. Configure Database Connection

Update your config.php:

$host = "localhost";
$user = "root";
$password = "";
$dbname = "book_system";

$conn = mysqli_connect($host, $user, $password, $dbname);
6. Run Project

Open in browser:

http://localhost/book-management-system/
