<?php
// Database configuration for XAMPP
$host = 'localhost';
$dbname = 'book_management_system';
$username = 'root';  // Default XAMPP username
$password = '';      // Default XAMPP password (empty)

// Initialize variables
$books = [];
$error = '';
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Build the query (keeping your original query structure)
    $query = "SELECT 
                b.book_id, 
                b.title, 
                b.author_id,
                b.price,
                b.publisher,
                b.publication_year,
                b.copies_available,
                b.total_copies,
                b.image,
                c.type AS category
              FROM books b
              JOIN authors a ON b.author_id = a.author_id
              JOIN categories c ON b.category_id = c.category_id
              WHERE 1=1";
    
    $params = [];
    
    // Add search filter if provided
    if (!empty($search)) {
        $query .= " AND (b.title LIKE ? OR a.name LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    // Add category filter if provided
    if (!empty($category)) {
        $query .= " AND c.type = ?";
        $params[] = $category;
    }
    
    $query .= " ORDER BY b.title ASC";
    
    // Prepare and execute
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch average ratings for books
    $ratings_query = "SELECT book_id, AVG(rating) AS avg_rating FROM ratings GROUP BY book_id";
    $ratings_stmt = $pdo->prepare($ratings_query);
    $ratings_stmt->execute();
    $ratings = $ratings_stmt->fetchAll(PDO::FETCH_ASSOC);
    $ratings_map = [];
    foreach ($ratings as $rating) {
        $ratings_map[$rating['book_id']] = round($rating['avg_rating'], 2);
    }
    
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .book-card {
            transition: transform 0.3s;
            height: 100%;
            overflow: hidden;
            position: relative;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .book-img-container {
            position: relative;
            width: 100%;
            padding-top: 150%; /* 3:2 aspect ratio */
            overflow: hidden;
        }
        .book-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .book-card:hover .book-img {
            transform: scale(1.05);
        }
        .book-info-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
            padding: 20px 15px 15px;
        }
        .book-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: white;
        }
        .book-meta {
            font-size: 0.9rem;
            margin-bottom: 3px;
            color: rgba(255,255,255,0.9);
        }
        .availability-badge {
            font-size: 0.8rem;
            margin-top: 5px;
        }
        .book-hover-buttons {
            display: none;
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            width: 100%;
        }
        .book-card:hover .book-hover-buttons {
            display: block;
        }
        .no-image-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-5">Book Management System</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <!-- Search Form -->
        <div class="search-container mb-5">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by title or author" 
                           value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php
                        // Fetch unique categories from books
                        try {
                            $catStmt = $pdo->query("SELECT DISTINCT type FROM categories ORDER BY type");
                            while ($cat = $catStmt->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($category == $cat['type']) ? 'selected' : '';
                                echo "<option value='".htmlspecialchars($cat['type'])."' $selected>".htmlspecialchars($cat['type'])."</option>";
                            }
                        } catch (Exception $e) {
                            echo "<!-- Error fetching categories: ".htmlspecialchars($e->getMessage())." -->";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                    <a href="?" class="btn btn-outline-secondary w-100 mt-2">Reset</a>
                </div>
            </form>
        </div>
        
        <!-- Books Display -->
        <?php if (empty($books)): ?>
            <div class="alert alert-info text-center">No books found matching your criteria.</div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($books as $book): ?>
                    <div class="col">
                        <div class="card book-card">
                            <div class="book-img-container">
                                <?php if (!empty($book['image'])): ?>
                                    <img src="admin/uploads/<?= htmlspecialchars($book['image']) ?>" class="book-img" alt="<?= htmlspecialchars($book['title']) ?>">
                                <?php else: ?>
                                    <div class="no-image-placeholder">
                                        <span>No Image Available</span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="book-info-overlay">
                                    <h5 class="book-title"><?= htmlspecialchars($book['title']) ?></h5>
                                    
                                    <div class="book-meta">
                                        <?php if (!empty($book['category'])): ?>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($book['category']) ?></span>
                                        <?php endif; ?>
                                        <span class="fw-bold">$<?= number_format($book['price'], 2) ?></span>
                                    </div>
                                    
                                    <div class="book-meta">
                                        <small>Publisher: <?= htmlspecialchars($book['publisher']) ?></small><br>
                                        <small>Year: <?= htmlspecialchars($book['publication_year']) ?></small>
                                    </div>
                                    
                                    <div class="availability-badge">
                                        <?php if ($book['copies_available'] > 0): ?>
                                            <span class="badge bg-success">
                                                Available: <?= $book['copies_available'] ?> of <?= $book['total_copies'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="book-hover-buttons">
                                    <!-- Rating display -->
                                    <?php if (isset($ratings_map[$book['book_id']])): ?>
                                        <div>Rating: <?= $ratings_map[$book['book_id']] ?> / 5</div>
                                    <?php endif; ?>
                                    <button class="btn btn-success">Buy</button>
                                    <!-- Add rating input -->
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ratingModal" data-book-id="<?= $book['book_id'] ?>">Rate This Book</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Rate the Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <
