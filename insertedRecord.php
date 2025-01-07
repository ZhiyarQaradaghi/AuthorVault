<?php
require_once 'database.php';


if (!isset($_COOKIE['user'])) {
    echo "<script>alert('You must be logged in to view this page.'); window.location.href = 'login.php';</script>";
    exit();
}


$username = $_COOKIE['user'];


$getAuthorName = "SELECT name FROM Author WHERE username = ?";
$stmt = $pdo->prepare($getAuthorName);
$stmt->bindParam(1, $username);
$stmt->execute();
$author = $stmt->fetch();

if ($author) {
    $authorName = $author['name'];
} else {
    $authorName = "Unknown Author";  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Added</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Thank you, <?php echo htmlspecialchars($authorName); ?>!</h2>
        <p>One record has been successfully added to your account!</p>
        <form action="add_book.php" method="GET">
            <button type="submit">Add Another Record</button>
        </form>
        <form action="show_books.php" method="GET">
            <button type="submit">Show Books</button>
        </form>
    </div>
    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Library. All rights reserved.</p>
    </footer>
</body>
</html>
