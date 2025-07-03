<?php
require_once 'admin_auth.php';
include '../db.php'; // Ensure this contains your PDO $conn
include('header.php');
include('sidebar.php');

$query = "SELECT title, image FROM books WHERE copies_available = 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Out of Stock Books</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 30px;
            margin-top: 30px;
        }

        .book-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .book-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 200px;
            text-align: center;
        }

        .book-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 6px;
        }

        .book-title {
            margin-top: 10px;
            font-weight: 600;
            color: #333;
        }

        .no-books {
            text-align: center;
            color: #777;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>

    <h1 style="text-align:center;">Out of Stock Books</h1>

    <?php if (count($books) > 0): ?>
        <div class="book-list">
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <img src="uploads/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                    <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-books">All books are in stock.</div>
    <?php endif; ?>

</body>
</html>
<?php include('footer.php'); ?>

