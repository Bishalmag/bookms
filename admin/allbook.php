<?php

include('header.php');
include('sidebar.php');

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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
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
            margin-left: 19%;
        }
        
        thead {
            background-color: #4a6fa5;
            color: white;
        }
        
        th {
            padding: 10px 12px; /* Reduced padding */
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        td {
            padding: 10px 12px; /* Reduced padding */
            border-bottom: 1px solid #e0e0e0;
            color: #555;
        }

        
        tr:last-child td {
            border-bottom: none;
        }
        
        img {
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .action-btns {
            display: flex;
            gap: 8px;
        }
        
        a.edit-btn, a.delete-btn {
           display: inline-block;
           padding: 6px 10px; /* Reduced padding */
           text-decoration: none;
           border-radius: 5px;
           font-size: 14px;
           font-weight: 500;
           text-align: center;
           transition: all 0.3s ease;
           margin: 0 5px; /* Space between buttons */
        }

        td.action-btns {
           display: flex;
           justify-content: center; /* Centers the buttons horizontally */
           align-items: center; /* Centers the buttons vertically */
           height: 100%; /* Ensure the cell takes full height */
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
        
        /* Responsive adjustments */
        @media (max-width: 1200px) {
            table {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<h2>Book Inventory</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>ISBN</th>
            <th>Price</th>
            <th>Publisher</th>
            <th>Year</th>
            <th>Available</th>
            <th>Total</th>
            <th>Cover</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($book = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($book['book_id']); ?></td>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                    <td>Rs.<?php echo htmlspecialchars(number_format(floatval($book['price']), 2)); ?></td>
                    <td><?php echo htmlspecialchars($book['publisher']); ?></td>
                    <td><?php echo htmlspecialchars($book['publication_year']); ?></td>
                    <td><?php echo htmlspecialchars($book['copies_available']); ?></td>
                    <td><?php echo htmlspecialchars($book['total_copies']); ?></td>
                    <td><img src="uploads/<?php echo htmlspecialchars($book['image']); ?>" width="80" alt="Book Cover"></td>
                    <td class="action-btns">
                        <a href="editbook.php?book_id=<?php echo $book['book_id']; ?>" class="edit-btn">Edit</a>
                        <a href="deletebook.php?book_id=<?php echo $book['book_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="11" class="no-books">No books available</td></tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>

<?php mysqli_close($conn); ?>
<?php include('footer.php'); ?>