<?php
    include('./header.php') 
?>
    <div class="card mt-5">
        <div class="card-header">Books Data</div>
        <div class="card-body">
            <a href="add_books.php" class="btn btn-primary mb-4"><i class="bi bi-plus-circle-fill"></i>&nbsp; Add Books Data</a>
            <a href="search_books.php" class="btn btn-primary mb-4"><i class="bi bi-search"></i>&nbsp; Search Books Data</a>
            <a href="order_books.php" class="btn btn-primary mb-4"><i class="bi bi-cart4"></i>&nbsp; Order Books</a>
            <a href="data_rekap.php" class="btn btn-primary mb-4"> <i class="bi bi-bar-chart-fill"></i>&nbsp;Book Recap</a>
            <a href="books_category.php" class="btn btn-primary mb-4"> <i class="bi bi-journals"></i>&nbsp;Book Category</a>
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
                    echo '<td><a class="btn btn-warning btn-sm" href="edit_books.php?id=' . $row->isbn . '"><i class="bi bi-pencil-square"></i>&nbsp; Edit</a>&nbsp;
                    <a class="btn btn-danger btn-sm" href="delete_books.php?id=' . $row->isbn . '"><i class="bi bi-trash"></i>&nbsp; Delete</a></td>';
                    echo '</tr>';
                }
      
                echo '</table>';
                echo 'Total Rows = ' . $result->num_rows;
                echo '<br />';
                
                $result->free();
                $db->close();
                ?>

            <br>
            <a href="logout.php" class="btn btn-dark mb-4"> Logout</a>
        </div>
    </div>

<?php include('./footer.php') ?>
