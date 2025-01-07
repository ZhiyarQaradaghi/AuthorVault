<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="navbar">
        <a href="login.php">Login</a>
    </div>
    <div class="auth-container">
        <h2>Register</h2>
        <form method="POST" action="register.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <input type="submit" name="submit" value="Register">
        </form>
    </div>

    <div class="footer">
        &copy; 2024 Library System. All rights reserved.
    </div>
</body>

<?php
require_once 'database.php';

if (isset($_POST['submit'])) {
    if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['name']) && !empty($_POST['email'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        
        $checkUser = "SELECT * FROM Author WHERE username = ?";
        $stmt = $pdo->prepare($checkUser);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            echo 'Username already taken. <a href="register.php">Try another</a>';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertUser = "INSERT INTO Author (name, email, username, password) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($insertUser);
            $stmt->bindParam(1, $name);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $username);
            $stmt->bindParam(4, $hashedPassword);
            
            if ($stmt->execute()) {
                header("Location: user_books.php");
                exit();
            } else {
                echo 'Registration failed. Please try again.';
            }
        }
    } else {
        echo 'Please fill in all fields.';
    }
}
?>

</html>
