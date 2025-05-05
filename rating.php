<?php
// === DB CONNECTION ===
$host = 'localhost';
$dbname = 'book_management_system'; // replace this
$username = 'root';
$password = ''; // empty for XAMPP

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}

// === CONFIG ===
$m = 10; // min votes
$sqlC = "SELECT AVG(rating) AS C FROM ratings"; // fix: 'rating' not 'ratings'
$C = $dbh->query($sqlC)->fetch(PDO::FETCH_ASSOC)['C'] ?? 0;

// === Book stats ===
$sql = "SELECT book_id, COUNT(*) AS v, AVG(rating) AS R FROM ratings GROUP BY book_id";
$ratings = $dbh->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$scores = [];
foreach ($ratings as $row) {
    $v = $row['v'];
    $R = $row['R'];
    $book_id = $row['book_id'];
    $score = ($v / ($v + $m)) * $R + ($m / ($v + $m)) * $C;
    $scores[$book_id] = round($score, 2);
}
arsort($scores);

// === Fetch book titles ===
$books = [];
foreach ($scores as $book_id => $score) {
    $stmt = $dbh->prepare("SELECT title FROM books WHERE book_id = ?");
    $stmt->execute([$book_id]);
    $title = $stmt->fetchColumn();
    if ($title) {
        $books[] = ['title' => $title, 'score' => $score];
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Top Rated Books</title></head>
<body>
<h2>Top Rated Books</h2>
<table border="1" cellpadding="10">
<tr><th>#</th><th>Title</th><th>Score</th></tr>
<?php $i = 1; foreach ($books as $book): ?>
<tr>
  <td><?= $i++ ?></td>
  <td><?= htmlspecialchars($book['title']) ?></td>
  <td><?= $book['score'] ?> / 5</td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
