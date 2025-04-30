<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "book_management_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all books
$sql = "SELECT * FROM books";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Books</title>
    <style>
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        a.edit-btn, a.delete-btn {
            background-color: #28a745;
            color: white;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 4px;
        }
        a.edit-btn:hover, a.delete-btn:hover {
            background-color: #218838;
        }
        .delete-btn {
            background-color: #dc3545; /* Red background for delete */
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">All Books</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>ISBN</th>
            <th>Publisher</th>
            <th>Year</th>
            <th>Copies Available</th>
            <th>Total Copies</th>
            <th>Image </th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($book = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $book['book_id']; ?></td>
                <td><?php echo $book['title']; ?></td>
                <td><?php echo $book['isbn']; ?></td>
                <td><?php echo $book['publisher']; ?></td>
                <td><?php echo $book['publication_year']; ?></td>
                <td><?php echo $book['copies_available']; ?></td>
                <td><?php echo $book['total_copies']; ?></td>
                <td><img src="uploads/<?php echo $book['image']; ?>" width="100" alt="Book Image"></td>
                <td><a href="editbook.php?book_id=<?php echo $book['book_id']; ?>" class="edit-btn">Edit</a></td>
                <td><a href="deletebook.php?book_id=<?php echo $book['book_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>

<?php mysqli_close($conn); ?>
