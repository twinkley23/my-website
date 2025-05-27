<?php
session_start();
include('db_connection.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle removal from wishlist
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove'])) {
    $book_id = $_POST['book_id'];
    mysqli_query($conn, "DELETE FROM rm_wishlist WHERE user_id = $user_id AND book_id = $book_id");
}

// Fetch user's wishlist
$query = "SELECT rm_books.* FROM rm_books
          INNER JOIN rm_wishlist ON rm_books.id = rm_wishlist.book_id
          WHERE rm_wishlist.user_id = $user_id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist - ReadMore</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        nav { background: #333; padding: 10px; text-align: center; }
        nav a { color: white; text-decoration: none; margin: 0 15px; font-weight: bold; }
        .container { padding: 20px; max-width: 900px; margin: auto; background: white; margin-top: 20px; border-radius: 8px; }
        .book { border-bottom: 1px solid #ddd; padding: 15px 0; }
        .book h3 { margin: 0; }
        button { background: #ef4444; border: none; padding: 6px 12px; color: white; border-radius: 4px; }
    </style>
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="books.php">Browse Books</a>
    <a href="wishlist.php">Wishlist</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h2>Your Wishlist</h2>
    <?php if (mysqli_num_rows($result) == 0): ?>
        <p>Your wishlist is currently empty.</p>
    <?php else: ?>
        <?php while ($book = mysqli_fetch_assoc($result)): ?>
            <div class="book">
                <h3><?= htmlspecialchars($book['title']) ?></h3>
                <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                <p><?= htmlspecialchars($book['description']) ?></p>
                <form method="post">
                    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                    <button type="submit" name="remove">Remove from Wishlist</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</body>
</html>
