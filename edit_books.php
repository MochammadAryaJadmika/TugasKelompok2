<?php
require_once('./lib/db_login.php');
$id = $_GET['id'];

if (!isset($_POST["submit"])) {
    $query = "SELECT * FROM books WHERE isbn = '" . $id . "'";

    $result = $db->query($query);
    if (!$result) {
        die("Could not query the database: <br />" . $db->error);
    } else {
        while ($row = $result->fetch_object()) {
            $title = $row->title;
            $categoryid = $row->categoryid;
            $author = $row->author;
            $price = $row->price;
        }
    }
} else {
    $valid = TRUE;
    
    // title validation
    $title = test_input($_POST['title']);
    if ($title == '') {
        $error_title = "Title is required";
        $valid = FALSE;
    }

    // categoryid validation
    $categoryid = test_input($_POST['categoryid']);
    if ($categoryid == '') {
        $error_categoryid = "Category ID is required";
        $valid = FALSE;
    } else if (!preg_match("/^[0-9]*$/", $categoryid)) {
        $error_categoryid = "Only numbers allowed";
        $valid = FALSE;
    }

    // author validation
    $author = test_input($_POST['author']);
    if ($author == '') {
        $error_author = "Author is required";
        $valid = FALSE;
    }

    // price validation
    $price = test_input($_POST['price']);
    if ($price == '') {
        $error_price = "Price is required";
        $valid = FALSE;
    } else if (!preg_match("/^[0-9]{2}\.[0-9]{2}$/", $price)) {
        $error_price = "Only float(4,2) allowed";
        $valid = FALSE;
    }

    // Update data into database
    if ($valid) {
        $query = "UPDATE books SET title='" . $title . "', categoryid ='" . $categoryid . "', author ='" . $author . "', price = '" . $price . "' WHERE isbn = '" . $id . "'";

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
<?php include('./header.php') ?>
<br>
<div class="card mt-4">
    <div class="card-header">Edit Books Data</div>
    <div class="card-body">
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id ?>" method="POST" autocomplete="on">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= $title; ?>">
                <div class="error"><?php if (isset($error_title)) echo $error_title ?></div>
            </div>

            <div class="form-group">
                <label for="categoryid">Category ID:</label>
                <input type="text" class="form-control" id="categoryid" name="categoryid" value="<?= $categoryid; ?>">
                <div class="error"><?php if (isset($error_categoryid)) echo $error_categoryid ?></div>
            </div>

            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" class="form-control" id="author" name="author" value="<?= $author; ?>">
                <div class="error"><?php if (isset($error_author)) echo $error_author ?></div>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?= $price; ?>">
                <div class="error"><?php if (isset($error_price)) echo $error_price ?></div>
            </div>

            <br>
            <button type="submit" class="btn btn-primary" name="submit" value="submit">Edit</button>
            <a href="view_books.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php include('./footer.php') ?>
<?php
$db->close();
?>
