<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Insert 6 books only if the table is empty
$check = mysqli_query($conn, "SELECT COUNT(*) as total FROM rm_books");
$row = mysqli_fetch_assoc($check);
if ($row['total'] == 0) {
    $books = [
        ["The Alchemist", "Paulo Coelho", "A journey of a shepherd boy to find his personal legend."],
        ["1984", "George Orwell", "A dystopian future ruled by totalitarianism."],
        ["To Kill a Mockingbird", "Harper Lee", "A young girl's view on race and justice in the 1930s."],
        ["Atomic Habits", "James Clear", "Guide to building good habits and breaking bad ones."],
        ["The Subtle Art of Not Giving a F*ck", "Mark Manson", "A counterintuitive approach to living a good life."],
        ["Sapiens", "Yuval Noah Harari", "A brief history of humankind."]
    ];
    foreach ($books as $book) {
        $title = mysqli_real_escape_string($conn, $book[0]);
        $author = mysqli_real_escape_string($conn, $book[1]);
        $desc = mysqli_real_escape_string($conn, $book[2]);
        mysqli_query($conn, "INSERT INTO rm_books (title, author, description) VALUES ('$title', '$author', '$desc')");
    }
}

// Add book to wishlist
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_wishlist'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];
    $check = mysqli_query($conn, "SELECT * FROM rm_wishlist WHERE user_id = $user_id AND book_id = $book_id");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO rm_wishlist (user_id, book_id) VALUES ($user_id, $book_id)");
    }
}

// Fetch all books
$books_result = mysqli_query($conn, "SELECT * FROM rm_books");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Books - ReadMore</title>
    <style>
        body { font-family: Arial; background: #f8f8f8; margin: 0; padding: 0; }
        nav { background: #333; padding: 10px; text-align: center; }
        nav a { color: white; text-decoration: none; margin: 0 15px; font-weight: bold; }
        .container { padding: 20px; max-width: 900px; margin: auto; background: white; margin-top: 20px; border-radius: 8px; }
        .book { border-bottom: 1px solid #ddd; padding: 15px 0; }
        .book h3 { margin: 0; }
        button { background: #22c55e; border: none; padding: 6px 12px; color: white; border-radius: 4px; margin-top: 10px; }
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
    <h2>Available Books</h2>
    <?php while ($book = mysqli_fetch_assoc($books_result)) { ?>
        <div class="book">
            <h3><?= htmlspecialchars($book['title']) ?></h3>
            <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
            <p><?= htmlspecialchars($book['description']) ?></p>
            <form method="post">
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                <button type="submit" name="add_to_wishlist">Add to Wishlist</button>
            </form>
        </div>
    <?php } ?>
</div>

</body>
</html>
