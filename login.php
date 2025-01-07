<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="navbar">
        <a href="register.php">Register</a>
    </div>
    <div class="auth-container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="submit" value="Login">
        </form>
    </div>

    <div class="footer">
        &copy; 2024 Library System. All rights reserved.
    </div>
</body>

<?php
require_once 'database.php'; 

if (isset($_POST['submit'])) {
    if (!empty($_POST['password']) && !empty($_POST['username'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        $selectUser = "SELECT * FROM Author WHERE username = ?";
        $result = $pdo->prepare($selectUser);
        $result->bindParam(1, $username);
        $result->execute();
        $row = $result->fetch();

        if ($row) { 
            if (password_verify($password, $row['password'])) {
                setcookie('user', $username, time() + 3600, '/');
                header("Location: user_books.php"); 
                exit(); 
            } else {
                echo 'WRONG PASSWORD. <a href="login.php">Try again</a> or <a href="register.php">register</a>';
            }
        } else {
            echo 'Username not found. <a href="register.php">Register here</a>';
        }
    } else {
        die("Enter username and password");
    }
}
?>
</html>
