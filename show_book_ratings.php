<?php
$conn = new mysqli('localhost', 'root', '', 'book_management_system');

$stmt = $conn->prepare("SELECT AVG(ratings) AS average_rating, COUNT(*) AS total_reviews FROM ratings WHERE book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo "<h3>Average Rating: " . round($result['average_rating'], 1) . " / 5 (" . $result['total_reviews'] . " reviews)</h3>";

$stmt = $conn->prepare("SELECT r.ratings, r.reviews, u.name FROM ratings r JOIN users u ON r.user_id = u.id WHERE r.book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<p><strong>{$row['name']}</strong> rated {$row['ratings']} stars<br>{$row['reviews']}</p><hr>";
}

$conn->close();
?>
