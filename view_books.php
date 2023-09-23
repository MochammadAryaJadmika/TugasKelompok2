<?php
    include('./header.php') 
?>

    <div class="card mt-5">
        <div class="card-header">Books Data</div>
        <div class="card-body">
            <a href="add_books.php" class="btn btn-primary mb-4">+ Add Books Data</a>
            <br>
            <table class="table table-striped">
                <tr>
                    <th>ISBN</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                <?php
                require_once('./lib/db_login.php');

                $query = "SELECT b.*, c.name 
                FROM books b JOIN categories c ON b.categoryid = c.categoryid
                ORDER BY b.title";
      
                $result = $db->query($query);
                
                if (!$result) {
                    die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
                }
                
                while ($row = $result->fetch_object()) {
                    echo '<tr>';
                    echo '<td>' . $row->isbn . '</td>';
                    echo '<td>' . $row->title . '</td>';
                    echo '<td>' . $row->name . '</td>';
                    echo '<td>' . $row->author . '</td>';
                    echo '<td>' . $row->price . '</td>';
                    echo '<td><a class="btn btn-warning btn-sm" href="edit_books.php?id=' . $row->isbn . '">Edit</a>&nbsp;
                    <a class="btn btn-danger btn-sm" href="delete_books.php?id=' . $row->isbn . '">Delete</a></td>';
                    echo '</tr>';
                }
      
                echo '</table>';
                echo '<br />';
                echo 'Total Rows = ' . $result->num_rows;

                $result->free();
                $db->close();
                ?>
        </div>
    </div>

<?php include('./footer.php') ?>