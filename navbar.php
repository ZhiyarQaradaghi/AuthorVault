<div class="navbar">
    <a href="show_books.php">Home</a>
    <a href="add_book.php">Add Record</a>
    <a href="delete_book.php">Delete Record</a>
    <?php if (isset($_COOKIE['user'])) { ?>
        <a href="user_books.php">Your Books</a>
        <span class="username">Hello, <?php echo htmlspecialchars($_COOKIE['user']); ?></span>
        <form method="POST" action="user_books.php" class="logout-form">
            <button type="submit" name="delete_cookie" class="logout-button">Log Out</button>
        </form>
    <?php } ?>
</div>
