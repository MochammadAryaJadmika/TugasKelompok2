<?php
include('./header.php');

$valid = true;

// Input Validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('./lib/db_login.php');

    $isbn = test_input($_POST['isbn']);
    if ($isbn == '') {
        $error_isbn = "ISBN is required";
        $valid = false;
    }

    $title = test_input($_POST['title']);
    if ($title == '') {
        $error_title = "Title is required";
        $valid = false;
    }

    $categoryid = test_input($_POST['categoryid']);
    if ($categoryid == '') {
        $error_categoryid = "Category ID is required";
        $valid = false;
    } else if (!preg_match("/^[0-9]*$/", $categoryid)) {
        $error_categoryid = "Only numbers allowed";
        $valid = false;
    }

    $author = test_input($_POST['author']);
    if ($author == '') {
        $error_author = "Author is required";
        $valid = false;
    }

    $price = test_input($_POST['price']);
    if ($price == '') {
        $error_price = "Price is required";
        $valid = false;
    } else if (!preg_match("/^[0-9]{2}\.[0-9]{2}$/", $price)) {
        $error_price = "Only float(4,2) allowed";
        $valid = false;
    }

    if ($valid) {
        $query = "INSERT INTO books (isbn, title, categoryid, author, price) VALUES ('$isbn', '$title', '$categoryid', '$author', '$price')";
        $result = $db->query($query);
        if (!$result) {
            die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
        } else {
            $db->close();
            header('Location: view_books.php');
        }
    }
    
}
?>

<div class="card mt-5">
    <div class="card-header">Add Books Data</div>
    <div class="card-body">
        <form action="add_books.php" method="POST">
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" class="form-control" id="isbn" name="isbn" required>
                <div class="error"><?php if (isset($error_isbn)) echo $error_isbn ?></div>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
                <div class="error"><?php if (isset($error_title)) echo $error_title ?></div>
            </div>
            <div class="form-group">
                <label for="categoryid">Category ID:</label>
                <input type="text" class="form-control" id="categoryid" name="categoryid" required>
                <div class="error"><?php if (isset($error_categoryid)) echo $error_categoryid ?></div>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" class="form-control" id="author" name="author" required>
                <div class="error"><?php if (isset($error_author)) echo $error_author ?></div>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" required>
                <div class="error"><?php if (isset($error_price)) echo $error_price ?></div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Add Book</button>
            <a href="view_books.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include('./footer.php'); ?>