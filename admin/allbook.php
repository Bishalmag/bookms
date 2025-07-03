<?php
require_once 'admin_auth.php';
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
        padding: 0;
        margin-left: 150px;
        width: calc(100% - 150px);
        box-sizing: border-box;
    }
    
    .content-container {
        padding: 20px;
        margin-top: 40px;
        margin-bottom: 60px;
        margin-left: 25px;
    }
    
    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
        font-size: 24px;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    thead {
        background-color: #4a6fa5;
        color: white;
        position: sticky;
        top: 70px;
    }
    
    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }
    
    th {
        font-weight: 600;
    }
    
    td {
        color: #555;
    }
    
    img.book-cover {
        width: 50px;
        height: 70px;
        object-fit: cover;
        border-radius: 4px;
    }
    
    .action-btns {
        display: flex;
        gap: 8px;
    }
    
    .edit-btn, .delete-btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .edit-btn {
        background-color: #4a6fa5;
        color: white;
    }
    
    .delete-btn {
        background-color: #e74c3c;
        color: white;
    }
    
    .edit-btn:hover {
        background-color: #3a5a80;
    }
    
    .delete-btn:hover {
        background-color: #c0392b;
    }
    
    @media (max-width: 768px) {
        body {
            margin-left: 0;
            width: 100%;
        }
        
        .content-container {
            padding: 15px;
            margin-top: 60px;
        }
        
        table {
            font-size: 14px;
        }
        
        th, td {
            padding: 8px 10px;
        }
        
        thead {
            top: 60px;
        }
    }
</style>
</head>
<body>

<div class="content-container">
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
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
<?php include('footer.php'); ?>