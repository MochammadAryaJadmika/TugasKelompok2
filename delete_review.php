<?php
session_start();

if (isset($_GET['id'])) {
    require_once('./lib/db_login.php');

    $isbn = mysqli_real_escape_string($db, $_GET['id']);

    $query = "DELETE FROM book_reviews WHERE isbn = '$isbn'";

    $result = $db->query($query);
    if (!$result) {
        die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
    } else {
        $db->close();
        header('Location: search_books.php');
    }
}
?>
