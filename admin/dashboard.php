<?php
session_start();

include('header.php');
include('sidebar.php');

// Database connection
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "book_management_system";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Books</title>
    <style>
        .center-title {
            text-align: center;
            margin: 30px 0;
        }
        .book-list {
            margin: 0 auto;
            width: 90%;
        }
        .book-table {
            width: 100%;
            border-collapse: collapse;
        }
        .book-table th, .book-table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }
        .book-table th {
            background-color: #28a745;
            color: white;
        }
        .book-table img {
            max-width: 100px;  /* Set a max width for images */
            max-height: 100px;
        }
    </style>
</head>
<body>
    <div class="center-title">
        <h1>All Books</h1>
    </div>

    <div class="book-list">
        <table class="book-table">
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Publication Year</th>
                    <th>Publisher</th>
                    <th>Copies Available</th>
                    <th>Total Copies</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Updated query with JOINs to fetch author name, category name, and image
                $sql = "SELECT 
                            b.book_id,
                            b.title,
                            b.isbn,
                            a.name AS author_name,
                            c.type AS category,
                            b.publication_year,
                            b.publisher,
                            b.copies_available,
                            b.total_copies,
                            b.image
                        FROM books b
                        JOIN authors a ON b.author_id = a.author_id
                        JOIN categories c ON b.category_id = c.category_id";

                $result = mysqli_query($conn, $sql);

                // Check if there are any results
                if (mysqli_num_rows($result) > 0) {
                    // Loop through the results and display them in the table
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['book_id'] . "</td>";
                        echo "<td>" . $row['title'] . "</td>";
                        echo "<td>" . $row['isbn'] . "</td>";
                        echo "<td>" . $row['author_name'] . "</td>";
                        echo "<td>" . $row['category'] . "</td>";
                        echo "<td>" . $row['publication_year'] . "</td>";
                        echo "<td>" . $row['publisher'] . "</td>";
                        echo "<td>" . $row['copies_available'] . "</td>";
                        echo "<td>" . $row['total_copies'] . "</td>";
                        // Display the image, if available
                        if (!empty($row['image'])) {
                            echo "<td><img src='uploads/" . $row['image'] . "' alt='Book Image'></td>";
                        } else {
                            echo "<td>No Image</td>";
                        }
                        // Pass the book_id as a GET parameter to edit and delete pages
                        echo "<td><a href='editbook.php?book_id=" . $row['book_id'] . "' class='edit-btn'>Edit</a> |
                                  <a href='deletebook.php?book_id=" . $row['book_id'] . "' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this book?');\">Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    // If no books found, display this message
                    echo "<tr><td colspan='11'>No books found</td></tr>";
                }
                
                // Close connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
