<?php
session_start();
include('db_connection.php');

$name = $email = $password = $confirm_password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate fields
    if (empty($name)) $errors[] = "Full Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

    // If valid, insert into database
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO rm_users (full_name, email, password) VALUES ('$name', '$email', '$hashed')";
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "This email is already registered or an error occurred.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - ReadMore Book Store</title>
    <style>
        body { font-family: Arial; background: #eef2f3; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px #ccc; width: 300px; }
        h2 { text-align: center; }
        input { width: 100%; padding: 8px; margin: 6px 0; border: 1px solid #ccc; border-radius: 4px; }
        .error { color: red; font-size: 0.9em; }
        button { width: 100%; padding: 10px; background: #0284c7; border: none; color: white; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <form method="post">
        <h2>Register</h2>
        <?php if (!empty($errors)) foreach ($errors as $e) echo "<div class='error'>$e</div>"; ?>
        <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($name) ?>">
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="confirm_password" placeholder="Confirm Password">
        <button type="submit">Register</button>
    </form>
</body>
</html>
