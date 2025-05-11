
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Management System</title>
    <!-- Bootstrap CSS (ensure it's linked correctly) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .navbar-brand img {
    width: 40px; /* Adjust the width as needed */
    height: 40px; /* Make height equal to width for a perfect circle */
    border-radius: 50%; /* This makes the image circular */
    object-fit: cover; /* Ensures the image doesn't stretch */
}

</style>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <!-- Logo added inside navbar-brand -->
        <a class="navbar-brand" href="#">
            <img src="admin/uploads/logo.png" alt="Logo">
            Book Management System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-danger" href="homepage.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="order_history.php">Order History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="cart.php">
                        Cart
                        <?php if (!empty($_SESSION['cart'])): ?>
                            <span class="badge bg-danger"><?= count($_SESSION['cart']) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
