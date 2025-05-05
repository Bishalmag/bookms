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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .center-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 28px;
        }

        .book-list {
            margin-left: 22%;
            padding: 20px;
        }

        table {
            width: 95%;
            margin: 0 auto;
            border-collapse: separate;
            border-spacing: 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
        }

        thead {
            background-color: #4a6fa5;
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            color: #555;
            text-align: center;
        }

        tr:last-child td {
            border-bottom: none;
        }

        img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-btns {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        a.edit-btn, a.delete-btn {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s ease;
        }

        a.edit-btn {
            background-color: #4a6fa5;
            color: white;
            border: 1px solid #3a5a80;
        }

        a.edit-btn:hover {
            background-color: #3a5a80;
        }

        a.delete-btn {
            background-color: #e74c3c;
            color: white;
            border: 1px solid #c0392b;
        }

        a.delete-btn:hover {
            background-color: #c0392b;
        }

        .no-books {
            text-align: center;
            color: #777;
            font-style: italic;
            padding: 20px;
        }

        @media (max-width: 1200px) {
            table {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="book-list">
    <h1 class="center-title">All Books</h1>

    <table>
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>ISBN</th>
                <th>Price</th>
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
            $sql = "SELECT 
                        b.book_id,
                        b.title,
                        b.isbn,
                        b.price,
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

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['book_id'] . "</td>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['isbn'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>" . $row['author_name'] . "</td>";
                    echo "<td>" . $row['category'] . "</td>";
                    echo "<td>" . $row['publication_year'] . "</td>";
                    echo "<td>" . $row['publisher'] . "</td>";
                    echo "<td>" . $row['copies_available'] . "</td>";
                    echo "<td>" . $row['total_copies'] . "</td>";
                    echo "<td>";
                    if (!empty($row['image'])) {
                        echo "<img src='uploads/" . $row['image'] . "' alt='Book Image'>";
                    } else {
                        echo "No Image";
                    }
                    echo "</td>";
                    echo "<td class='action-btns'>
                            <a class='edit-btn' href='editbook.php?book_id=" . $row['book_id'] . "'>Edit</a>
                            <a class='delete-btn' href='deletebook.php?book_id=" . $row['book_id'] . "' onclick=\"return confirm('Are you sure you want to delete this book?');\">Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='12' class='no-books'>No books found</td></tr>";
            }

            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
</body>
</html>
