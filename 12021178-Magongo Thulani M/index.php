<?php
session_start();
include('db_connection.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Update visit count for homepage
$page = 'homepage';
$result = mysqli_query($conn, "SELECT * FROM rm_stats WHERE page = '$page'");
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $visits = $row['visits'] + 1;
    mysqli_query($conn, "UPDATE rm_stats SET visits = $visits WHERE page = '$page'");
} else {
    $visits = 1;
    mysqli_query($conn, "INSERT INTO rm_stats (page, visits) VALUES ('$page', 1)");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - ReadMore Book Store</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        nav { background: #333; padding: 10px; text-align: center; }
        nav a { color: white; text-decoration: none; margin: 0 15px; font-weight: bold; }
        .container { padding: 20px; max-width: 800px; margin: auto; background: white; margin-top: 20px; border-radius: 8px; }
    </style>
</head>

<nav>
    <a href="index.php">Home</a>
    <a href="books.php">Browse Books</a>
    <a href="wishlist.php">Wishlist</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h2>
    <p>This is your homepage.</p>
    <p><strong>Total visits to this page:</strong> <?= $visits ?></p>
</div>

</body>
</html>
