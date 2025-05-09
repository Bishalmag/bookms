<?php
require_once 'user_auth.php';
include('header.php');

// Database config
$host = 'localhost';
$dbname = 'book_management_system';
$username = 'root';
$password = '';

// Initialize variables
$books = [];
$error = '';

try {
    // Connect to DB
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all books with author and category
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
                a.name AS author_name,
                c.genre AS category
              FROM books b
              JOIN authors a ON b.author_id = a.author_id
              JOIN categories c ON b.category_id = c.category_id
              ORDER BY b.title ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

   // Fetch average ratings from the ratings table
$ratings_stmt = $pdo->prepare("SELECT book_id, AVG(ratings) AS avg_rating FROM ratings GROUP BY book_id");
$ratings_stmt->execute();
$ratings_data = $ratings_stmt->fetchAll(PDO::FETCH_ASSOC);

// Extract average ratings for calculation
$avg_ratings = array_column($ratings_data, 'avg_rating');
$mean = array_sum($avg_ratings) / count($avg_ratings);

// Calculate standard deviation
$variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $avg_ratings)) / count($avg_ratings);
$std_dev = sqrt($variance);

// Create Z-score map for each book
$ratings_map = []; // book_id => z-score
foreach ($ratings_data as $rating) {
    $book_id = $rating['book_id'];
    $avg_rating = $rating['avg_rating'];
    $z_score = ($std_dev > 0) ? ($avg_rating - $mean) / $std_dev : 0;
    $ratings_map[$book_id] = round($z_score, 2);
}


} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
            padding-top: 150%;
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
        .rating-stars {
            font-size: 24px;
            margin: 10px 0;
        }
        .rating-stars .star {
            cursor: pointer;
            margin-right: 5px;
        }
        #ratingMessage {
            display: none;
        }
        /* Ratings Display Styles */
.book-hover-buttons {
    display: none;
    position: absolute;
    bottom: 20px;
    left: 50%; /* Center the container */
    transform: translateX(-20%); /* Center alignment */
    width: 80%; /* Control the width */
    max-width: 300px; /* Maximum width */
    text-align: center;
    background: rgba(0, 0, 0, 0.7);
    padding: 15px;
    border-radius: 8px;
    backdrop-filter: blur(5px);
    transition: all 0.3s ease;
    z-index: 10; /* Ensure it appears above other elements */
}
.book-card:hover .book-hover-buttons {
    display: block;
    animation: fadeInUp 0.3s ease;
}

/* Rating Text Styles */
.book-hover-buttons div {
    color: #fff;
    margin-bottom: 10px;
    font-size: 0.9rem;
    font-weight: 500;
}

.book-hover-buttons div:first-child {
    font-size: 1rem;
    font-weight: 600;
    color: #FFD700; /* Gold color for rating */
}

/* Z-Score Style */
.book-hover-buttons div:nth-child(2) {
    font-size: 0.8rem;
    color: #aaa;
    margin-bottom: 15px;
}

/* Button Styles */
.book-hover-buttons .btn {
    padding: 5px 12px;
    font-size: 0.8rem;
    margin: 0 3px;
    border-radius: 20px;
    transition: all 0.2s ease;
}

.book-hover-buttons .btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.book-hover-buttons .btn-warning {
    background-color: #f6c23e;
    border-color: #f6c23e;
    color: #212529;
}

.book-hover-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.book-hover-buttons .btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2e59d9;
}

.book-hover-buttons .btn-warning:hover {
    background-color: #f4b619;
    border-color: #f4b619;
}

/* Animation for hover effect */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .book-hover-buttons {
        padding: 5px;
    }
    
    .book-hover-buttons .btn {
        padding: 4px 8px;
        font-size: 0.7rem;
    }
}
    </style>
</head>
<body>

<div class="container py-5">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Search Bar -->
    <div class="mb-4">
        <input type="text" id="bookSearch" class="form-control" placeholder="Search by title, author, or category" onkeyup="debounceSearch()">
    </div>

    <!-- Books Display -->
    <?php if (empty($books)): ?>
        <div class="alert alert-info text-center">No books available.</div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="bookContainer">
            <?php foreach ($books as $book): ?>
                <div class="col">
                    <div class="card book-card" data-search="<?= strtolower($book['title'] . ' ' . $book['author_name'] . ' ' . $book['category']) ?>">
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


 
  <!-- original displaying ratings without z-score  -->
 <!-- $ratings_stmt = $pdo->prepare("SELECT book_id, AVG(ratings) AS avg_rating FROM ratings GROUP BY book_id");
    $ratings_stmt->execute();
    $ratings_map = [];
    foreach ($ratings_stmt->fetchAll(PDO::FETCH_ASSOC) as $rating) {
        $ratings_map[$rating['book_id']] = round($rating['avg_rating'], 2);
    } -->
<!-- Display Ratings -->
    <div class="book-hover-buttons"> 
         <?php if (isset($ratings_map[$book['book_id']])): ?>
                <?php 
                     $avg_rating = array_values(array_filter($ratings_data, fn($r) => $r['book_id'] == $book['book_id']))[0]['avg_rating'];
                ?>
                    <div>Rating: <?= round($avg_rating, 2) ?> / 5</div>
                      <div>Z-Score: <?= $ratings_map[$book['book_id']] ?></div>
                        <?php else: ?>
                             <div>No ratings yet</div>
                                <?php endif; ?>
                                   <a href="cart.php?add_to_cart=<?= $book['book_id'] ?>" class="btn btn-primary btn-sm flex-fill">Add to Cart</a>
                                       <button class="btn btn-warning btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#ratingModal" data-book-id="<?= $book['book_id'] ?>" data-book-title="<?= htmlspecialchars($book['title']) ?>">
                                        Rate This Book
                                       </button>
                             </div>
                       </div>
                    </div>
                </div>
                     <?php endforeach; ?>
                        </div>
                             <?php endif; ?>
                        </div>


<!-- Rating Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ratingModalTitle">Rate this book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="ratingMessage" class="alert d-none mb-3"></div>
                <form id="ratingForm">
                    <input type="hidden" id="ratingBookId" name="book_id">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Your Rating:</label>
                        <div class="rating-stars">
                            <span class="star" data-value="1">☆</span>
                            <span class="star" data-value="2">☆</span>
                            <span class="star" data-value="3">☆</span>
                            <span class="star" data-value="4">☆</span>
                            <span class="star" data-value="5">☆</span>
                            <input type="hidden" name="ratings" id="selectedRating" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="review" class="form-label">Review (optional):</label>
                        <textarea class="form-control" name="review" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit Rating</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Search functionality
let debounceTimer;

function debounceSearch() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(searchBooks, 300);
}

function searchBooks() {
    const input = document.getElementById('bookSearch').value.toLowerCase();
    const cards = document.querySelectorAll('.book-card');
    let hasResults = false;

    // Remove old no-result alert
    const oldAlert = document.getElementById('noResults');
    if (oldAlert) oldAlert.remove();

    cards.forEach(card => {
        const searchableText = card.getAttribute('data-search');
        if (searchableText.includes(input)) {
            card.parentElement.style.display = 'block';
            hasResults = true;
        } else {
            card.parentElement.style.display = 'none';
        }
    });

    if (!hasResults) {
        const container = document.getElementById('bookContainer');
        const msg = document.createElement('div');
        msg.className = 'alert alert-warning text-center w-100';
        msg.id = 'noResults';
        msg.textContent = `No books found matching "${input}"`;
        container.appendChild(msg);
    }
}

// Rating Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modal with book data
    const ratingModal = document.getElementById('ratingModal');
    if (ratingModal) {
        ratingModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const bookId = button.getAttribute('data-book-id');
            const bookTitle = button.getAttribute('data-book-title');
            
            document.getElementById('ratingBookId').value = bookId;
            document.getElementById('ratingModalTitle').textContent = 'Rate "' + bookTitle + '"';
            document.getElementById('ratingMessage').classList.add('d-none');
        });
    }

    // Star rating interaction
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            document.getElementById('selectedRating').value = value;
            
            document.querySelectorAll('.star').forEach(s => {
                if (s.getAttribute('data-value') <= value) {
                    s.textContent = '★';
                    s.style.color = 'gold';
                } else {
                    s.textContent = '☆';
                    s.style.color = 'gray';
                }
            });
        });
    });

    // AJAX form submission
    const ratingForm = document.getElementById('ratingForm');
    if (ratingForm) {
        ratingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('ratingMessage');
            
            fetch('submit_rating.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.textContent = data.message || data.error;
                messageDiv.classList.remove('d-none');
                
                if (data.success) {
                    messageDiv.classList.remove('alert-danger');
                    messageDiv.classList.add('alert-success');
                    
                    // Close modal after 2 seconds and refresh page
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('ratingModal'));
                        modal.hide();
                        location.reload();
                    }, 2000);
                } else {
                    messageDiv.classList.remove('alert-success');
                    messageDiv.classList.add('alert-danger');
                }
            })
            .catch(error => {
                messageDiv.textContent = "Error submitting rating.";
                messageDiv.classList.remove('d-none', 'alert-success');
                messageDiv.classList.add('alert-danger');
            });
        });
    }
});

// Force reload when using browser back button
window.addEventListener("pageshow", function(event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
        window.location.reload();
    }
});
</script>

<?php include 'footer.php'; ?>