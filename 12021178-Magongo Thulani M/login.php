<?php
session_start();
include('db_connection.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Check if email exists
    $res = mysqli_query($conn, "SELECT * FROM rm_users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($res);

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - ReadMore Book Store</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px #ccc; width: 300px; }
        h2 { text-align: center; }
        input { width: 100%; padding: 8px; margin: 6px 0; border: 1px solid #ccc; border-radius: 4px; }
        .error { color: red; font-size: 0.9em; margin-bottom: 10px; }
        button { width: 100%; padding: 10px; background: #2563eb; border: none; color: white; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <form method="post">
        <h2>Login</h2>
        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
