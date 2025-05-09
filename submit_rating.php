<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'You must be logged in to rate.']);
    exit;
}

// Database config
$host = 'localhost';
$dbname = 'book_management_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Validate input
    $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
    $user_id = intval($_POST['user_id']);
    $ratings = isset($_POST['ratings']) ? intval($_POST['ratings']) : 0;
    $review = isset($_POST['review']) ? trim($_POST['review']) : '';

    if ($book_id <= 0 || $ratings < 1 || $ratings > 5) {
        throw new Exception('Invalid rating data.');
    }

    // Check if user already rated this book
    $stmt = $pdo->prepare("SELECT rating_id FROM ratings WHERE user_id = ? AND book_id = ?");
    $stmt->execute([$user_id, $book_id]);

    if ($stmt->rowCount() > 0) {
        // Update existing rating
        $stmt = $pdo->prepare("UPDATE ratings SET ratings = ?, reviews = ?, rated_on = NOW() WHERE user_id = ? AND book_id = ?");
        $stmt->execute([$ratings, $review, $user_id, $book_id]);
        $message = "Rating updated successfully!";
    } else {
        // Insert new rating
        $stmt = $pdo->prepare("INSERT INTO ratings (user_id, book_id, ratings, reviews) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $book_id, $ratings, $review]);
        $message = "Thank you for your rating!";
    }

    echo json_encode(['success' => true, 'message' => $message]);

} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch(Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>