<?php
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_cookie'])) {
        setcookie("user", "", time() - 3600, "/");
        header("Location: login.php");
        exit();
    }

    if (isset($_COOKIE['user']) && !empty($_POST['title'])) {
        $title = htmlspecialchars($_POST['title']);
        $selectBook = "SELECT bid FROM Book WHERE title = ?";
        $stmtSelect = $pdo->prepare($selectBook);
        $stmtSelect->bindParam(1, $title);
        $stmtSelect->execute();
        $book = $stmtSelect->fetch(PDO::FETCH_ASSOC);
        if ($book) {
            $bookId = $book['bid'];
            $deletePublish = "DELETE FROM Publish WHERE bid = ?";
            $stmtDeletePublish = $pdo->prepare($deletePublish);
            $stmtDeletePublish->bindParam(1, $bookId);
            $stmtDeletePublish->execute();
            $deleteOrphanAuthors = "DELETE FROM Author WHERE aid NOT IN (SELECT DISTINCT aid FROM Publish)";
            $pdo->prepare($deleteOrphanAuthors)->execute();
            $deleteBook = "DELETE FROM Book WHERE bid = ?";
            $stmtDeleteBook = $pdo->prepare($deleteBook);
            $stmtDeleteBook->bindParam(1, $bookId);

            if ($stmtDeleteBook->execute()) {
                header("location: deletedRecord.php");
                exit();
            } else {
                echo "Something went wrong deleting the book.";
            }
        } else {
            echo "Book not found.";
        }
    } else {
        echo "Please log in and provide a book title.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Book</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php require 'navbar.php'; ?>
    <div class="container">
        <h2>Delete a Book from the Library</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="title">Book title *</label>
            <input type="text" id="title" name="title" required>
            <input type="submit" value="Delete Record">
        </form>
        <form action="show_books.php" method="GET">
            <button type="submit">Home</button>
        </form>
    </div>
    <footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> Library . All rights reserved.</p>
</footer>
</body>
</html>
