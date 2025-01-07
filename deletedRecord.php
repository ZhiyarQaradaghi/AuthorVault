<!DOCTYPE html>
<html>
<head>
    <title>Record Deleted</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>One record has been successfully deleted!</h2>
        <form action="add_book.php" method="GET">
            <button type="submit">Add Record</button>
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
