<?php
include('./header.php');
?>

<div class="card mt-5">
    <div class="card-header">Books Detail</div>
    <div class="card-body">
        <a href="search_books.php" class="btn btn-secondary mb-4"><i class="bi bi-caret-left-fill"></i>&nbsp;Back</a>
        <ul class="list-group">
            <?php
            require_once('./lib/db_login.php');

            $isbn = $_GET['isbn'];

            $query = "SELECT b.*, c.name 
            FROM books b JOIN categories c ON b.categoryid = c.categoryid
            WHERE b.isbn = '$isbn'";

            $result = $db->query($query);

            if (!$result) {
                die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
            }

            while ($row = $result->fetch_object()) {
                echo '<li class="list-group-item">';
                echo '<table>';
                echo '<tr>';
                echo '<td><strong>ISBN</strong></td>';
                echo '<td><strong> : </strong> ' . $row->isbn . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>Title</strong></td>';
                echo '<td><strong> : </strong> ' . $row->title . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>Category</strong></td>';
                echo '<td><strong> : </strong> ' . $row->name . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>Author</strong></td>';
                echo '<td><strong> : </strong> ' . $row->author . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>Price</strong></td>';
                echo '<td><strong> : </strong> ' . $row->price . '</td>';
                echo '</tr>';
                echo '</table>';
                echo '</li>';
            }

            // Query untuk mengambil review dari tabel 'book_reviews' berdasarkan ISBN
            $queryReviews = "SELECT review FROM book_reviews WHERE isbn = '$isbn'";
            $resultReviews = $db->query($queryReviews);

            if (!$resultReviews) {
                die("Could not query the database for reviews: <br />" . $db->error . "<br>Query: " . $queryReviews);
            }

            echo '<li class="list-group-item">';
            echo '<strong>Review</strong>';
            echo '<ul>';
            while ($rowReview = $resultReviews->fetch_object()) {
                echo '<li>' . $rowReview->review . '</li>';
                echo '<br>';
                echo '<td><a class="btn btn-danger btn-sm" href="delete_review.php?id=' . $isbn . '"><i class="bi bi-trash"></i>&nbsp; Delete</a></td>';
            }
            echo '</ul>';
            echo '</li>';

            // Tambahkan handling untuk input review
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $review = test_input($_POST['review']);
                $isbn = test_input($_GET['isbn']); // Menggunakan ISBN yang sudah diterima melalui URL

                if ($review == '') {
                    $error_review = "Review is required";
                } else {
                    // Perhatikan penggunaan prepared statement untuk menghindari SQL injection
                    $queryReviews = "INSERT INTO book_reviews (isbn, review) VALUES (?, ?)";
                    $stmtReviews = $db->prepare($queryReviews);

                    if ($stmtReviews) {
                        // Binding parameter
                        $stmtReviews->bind_param("ss", $isbn, $review);

                        // Jalankan query
                        if ($stmtReviews->execute()) {
                            $stmtReviews->close();
                            header('Location: detail_books.php?isbn=' . $isbn);
                            exit;
                        } else {
                            echo "Gagal menambahkan review ke database: " . $stmtReviews->error;
                        }
                    } else {
                        echo "Gagal menyiapkan statement SQL: " . $db->error;
                    }
                }
            }

            $result->free();
            $resultReviews->free();
            $db->close();
            ?>

        <br>

        <form action="detail_books.php?isbn=<?php echo $isbn; ?>" method="POST">
            <table class="table table-striped">
                <tr>
                    <th> Add Review</th>
                </tr>
                <tr>
                    <td>
                        <div>
                            <textarea name="review" id="review" style="width: 500px; height: 150px;"></textarea>
                            <br>
                            <button type="submit" class="btn btn-primary" name="submit" value="submit"><i class="bi bi-plus"></i>&nbsp;Add</button>
                        </div>
                    </td>
                </tr>
            </table>  
        </form>
    </div>
</div>

<?php include('./footer.php'); ?>
