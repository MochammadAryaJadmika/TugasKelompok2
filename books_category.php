<?php
include('./header.php');
?>
<div class="card mt-5">
    <div class="card-header">Books Data</div>
    <div class="card-body">
        
        <br>
        <?php
        require_once('./lib/db_login.php');

        $query = "SELECT c.name AS category, b.isbn, b.title, b.author, b.price 
                  FROM books b 
                  JOIN categories c ON b.categoryid = c.categoryid
                  ORDER BY c.name, b.title";

        $result = $db->query($query);

        if (!$result) {
            die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
        }

        $currentCategory = '';
        echo '<table class="table table-striped">';
        echo '<tr>';
        echo '<th>Category</th>';
        echo '<th>ISBN</th>';
        echo '<th>Title</th>';
        echo '<th>Author</th>';
        echo '<th>Price</th>';
        echo '</tr>';

        while ($row = $result->fetch_object()) {
            if ($row->category !== $currentCategory) {
                echo '<tr>';
                echo '<td><strong>' . $row->category . '</strong></td>';
                echo '<td>' . $row->isbn . '</td>';
                echo '<td>' . $row->title . '</td>';
                echo '<td>' . $row->author . '</td>';
                echo '<td>' . $row->price . '</td>';
                echo '</tr>';
                $currentCategory = $row->category;
            } else {
                echo '<tr>';
                echo '<td></td>'; // Empty cell for category name
                echo '<td>' . $row->isbn . '</td>';
                echo '<td>' . $row->title . '</td>';
                echo '<td>' . $row->author . '</td>';
                echo '<td>' . $row->price . '</td>';
                echo '</tr>';
            }
        }

        echo '</table>';
        echo 'Total Rows = ' . $result->num_rows;
        echo '<br />';

        $result->free();
        $db->close();
        ?>
        <br>
        <a href="view_books.php" class="btn btn-secondary mb-4"><i class="bi bi-caret-left-fill"></i>&nbsp;Back</a>
    </div>
</div>
<?php include('./footer.php'); ?>
