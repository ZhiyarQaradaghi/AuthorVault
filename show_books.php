<?php
require_once 'database.php';

$query = "SELECT Book.bid, Book.title, Book.category, Book.pages, Book.year, GROUP_CONCAT(Author.name SEPARATOR ', ') AS authors
          FROM Book
          JOIN Publish ON Book.bid = Publish.bid
          JOIN Author ON Publish.aid = Author.aid
          GROUP BY Book.bid
          ORDER BY Book.title";
$stmt = $pdo->prepare($query);
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Books</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h2>Currently there are (<?php echo count($books); ?>) books in the library:</h2>
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
        <form action="add_book.php" method="GET">
            <button type="submit" class="button">Add New Record</button>
        </form>
        <form action="delete_book.php" method="GET">
            <button type="submit" class="button">Delete Record</button>
        </form>
    </div>
    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Library. All rights reserved.</p>
    </footer>
</body>
</html>
