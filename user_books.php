<?php
require_once 'database.php';

if (isset($_COOKIE['user'])) {
    $username = $_COOKIE['user'];

    $query = "SELECT Book.bid, Book.title, Book.category, Book.pages, Book.year, 
                     GROUP_CONCAT(Author.name SEPARATOR ', ') AS authors
              FROM Book
              JOIN Publish ON Book.bid = Publish.bid
              JOIN Author ON Publish.aid = Author.aid
              WHERE Book.bid IN (
                  SELECT Book.bid
                  FROM Book
                  JOIN Publish ON Book.bid = Publish.bid
                  JOIN Author ON Publish.aid = Author.aid
                  WHERE Author.username = ?
              )
              GROUP BY Book.bid
              ORDER BY Book.title";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1, $username);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Books</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
</head>
<body>
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <h3>Currently, there are <?php echo count($books); ?> books in your library:</h3>
        <table>
            <tr>
                <th>Title</th>
                <th>Author(s)</th>
                <th>Category</th>
                <th>Pages</th>
                <th>Year</th>
            </tr>
            <?php foreach ($books as $book) { ?>
            <tr>
                <td><?php echo htmlspecialchars(html_entity_decode($book['title'])); ?></td>
                <td><?php echo htmlspecialchars($book['authors']); ?></td>
                <td><?php echo htmlspecialchars($book['category']); ?></td>
                <td><?php echo htmlspecialchars($book['pages']); ?></td>
                <td><?php echo htmlspecialchars($book['year']); ?></td>
            </tr>
            <?php } ?>
        </table>

        <form method="GET" action="add_book.php">
            <input type="submit" value="Add New Book" class="button">
        </form>

        <form method="POST" action="">
            <input type="hidden" name="delete_cookie" value="1">
            <button type="submit" class="button">Log Out</button>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Library System. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cookie'])) {
    if (isset($_COOKIE['user'])) {
        setcookie("user", "", time() - 3600, "/");
        header("Location: login.php");
        exit();
    }
}
?>
