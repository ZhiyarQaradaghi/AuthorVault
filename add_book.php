<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script>
        function addAuthor() {
            var authorDiv = document.createElement('div');
            authorDiv.classList.add('author-fields');
            
            var nameLabel = document.createElement('label');
            nameLabel.setAttribute('for', 'authorName');
            nameLabel.textContent = 'Author Name';

            var nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.name = 'author[]';
            nameInput.required = true;

            var emailLabel = document.createElement('label');
            emailLabel.setAttribute('for', 'authorEmail');
            emailLabel.textContent = 'Author Email';

            var emailInput = document.createElement('input');
            emailInput.type = 'email';
            emailInput.name = 'email[]';
            emailInput.required = true;
            
            authorDiv.appendChild(nameLabel);
            authorDiv.appendChild(nameInput);
            authorDiv.appendChild(emailLabel);
            authorDiv.appendChild(emailInput);
            document.getElementById('authors').appendChild(authorDiv);
        }
    </script>
</head>
<body>

<?php
require_once 'database.php';
if (!isset($_COOKIE['user'])) {
    echo "<script>alert('You must be logged in to add a book.'); window.location.href = 'login.php';</script>";
    exit(); 
}

$username = $_COOKIE['user'];  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
    $category = isset($_POST['category']) ? htmlspecialchars($_POST['category']) : '';
    $publisher = isset($_POST['publisher']) ? htmlspecialchars($_POST['publisher']) : '';
    $year = isset($_POST['year']) && !empty($_POST['year']) ? $_POST['year'] : null;
    if ($year === null) {
        echo "<script>alert('Please provide a valid year!');</script>";
        exit;
    }
    $pages = isset($_POST['pages']) ? (int)$_POST['pages'] : 0;
    $authorNames = isset($_POST['author']) ? $_POST['author'] : [];
    $authorEmails = isset($_POST['email']) ? $_POST['email'] : [];
    $insertBook = "INSERT INTO Book (title, category, pages, year) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($insertBook);
    $stmt->bindParam(1, $title);
    $stmt->bindParam(2, $category);
    $stmt->bindParam(3, $pages);
    $stmt->bindParam(4, $year);
    $stmt->execute();

    $bookId = $pdo->lastInsertId();
    $checkAuthor = "SELECT * FROM Author WHERE username = ?";
    $stmt = $pdo->prepare($checkAuthor);
    $stmt->bindParam(1, $username);
    $stmt->execute();
    $author = $stmt->fetch();

    if (!$author) {
        $email = "user@example.com";  
        $insertAuthor = "INSERT INTO Author (name, email, username) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($insertAuthor);
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $username);  
        $stmt->execute();

        $authorId = $pdo->lastInsertId();
    } else {
        $authorId = $author['aid'];  
    }

    $insertPublish = "INSERT INTO Publish (bid, aid) VALUES (?, ?)";
    $stmt = $pdo->prepare($insertPublish);
    $stmt->bindParam(1, $bookId);
    $stmt->bindParam(2, $authorId);
    $stmt->execute();

    foreach ($authorNames as $key => $authorName) {
        $authorEmail = $authorEmails[$key];

        $checkAuthor = "SELECT * FROM Author WHERE name = ? AND email = ?";
        $stmt = $pdo->prepare($checkAuthor);
        $stmt->bindParam(1, $authorName);
        $stmt->bindParam(2, $authorEmail);
        $stmt->execute();
        $author = $stmt->fetch();

        if (!$author) {
            $insertAuthor = "INSERT INTO Author (name, email) VALUES (?, ?)";
            $stmt = $pdo->prepare($insertAuthor);
            $stmt->bindParam(1, $authorName);
            $stmt->bindParam(2, $authorEmail);
            $stmt->execute();

            $authorId = $pdo->lastInsertId();
        } else {
            $authorId = $author['aid'];
        }

        $insertPublish = "INSERT INTO Publish (bid, aid) VALUES (?, ?)";
        $stmt = $pdo->prepare($insertPublish);
        $stmt->bindParam(1, $bookId);
        $stmt->bindParam(2, $authorId);
        $stmt->execute();
    }

    header("Location: insertedRecord.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require 'navbar.php'; ?>

<div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <h2>Add New Book</h2>
        <label for="title">Book Title <span class="required">*</span></label>
        <input type="text" id="title" name="title" required>

        <label for="category">Category <span class="required">*</span></label>
        <select id="category" name="category" required>
            <option value="">Select a category</option>
            <option value="Fiction">Fiction</option>
            <option value="Non-Fiction">Non-Fiction</option>
            <option value="Science">Science</option>
            <option value="Biography">Biography</option>
            <option value="Fantasy">Fantasy</option>
            <option value="Mystery">Mystery</option>
            <option value="Romance">Romance</option>
            <option value="History">History</option>
            <option value="Self-Help">Self-Help</option>
            <option value="Children's">Children's</option>
        </select>

        <label for="publisher">Publisher <span class="required">*</span></label>
        <input type="text" id="publisher" name="publisher" required>

        <label for="year">Year <span class="required">*</span></label>
        <input type="date" id="year" name="year" required>

        <label for="pages">Pages <span class="required">*</span></label>
        <input type="number" id="pages" name="pages" required>

        <h3>Author 1</h3>
        <input type="text" name="author[]" placeholder="Author Name" required>
        <input type="email" name="email[]" placeholder="Author Email" required>

        <div id="authors"></div>
        <button type="button" onclick="addAuthor()">Add Another Author</button>

        <input type="submit" value="Add Book">
    </form>
</div>

<footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> Library . All rights reserved.</p>
</footer>

</body>
</html>
