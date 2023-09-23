<?php
require_once('./lib/db_login.php');

$categoryid = $isbn = $author = $title = $min_price = $max_price = null;
$where_clause = [];

// Check category
if (isset($_GET['name']) && !empty($_GET['name'])) {
    $name = mysqli_real_escape_string($db, $_GET['name']);;
    $where_clause[] = "name = '$name'";
}

// Check ISBN
if (isset($_GET['isbn']) && !empty($_GET['isbn'])) {
    $isbn = mysqli_real_escape_string($db, $_GET['isbn']);
    $where_clause[] = "isbn LIKE '%$isbn%'";
}

// Check author
if (isset($_GET['author']) && !empty($_GET['author'])) {
    $author = mysqli_real_escape_string($db, $_GET['author']);
    $where_clause[] = "author LIKE '%$author%'";
}

// Check title
if (isset($_GET['title']) && !empty($_GET['title'])) {
    $title = mysqli_real_escape_string($db, $_GET['title']);
    $where_clause[] = "title LIKE '%$title%'";
}

// Check min_price and max_price
if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
    $min_price = floatval($_GET['min_price']);
    $max_price = floatval($_GET['max_price']);
    if ($min_price > 0 || $max_price > 0) {
        $where_clause[] = "price BETWEEN $min_price AND $max_price";
    }
}


// Final WHERE clause for the SQL query
$where_clause_sql = !empty($where_clause) ? implode(' AND ', $where_clause) : '1';

// Full SQL query
$query = "SELECT b.*, c.name 
         FROM books b JOIN categories c ON b.categoryid = c.categoryid
         WHERE $where_clause_sql";

$result = $db->query($query);
?>

<?php include('./header.php') ?>
<div class="card mt-4">
    <div class="card-header">Search Books Data</div>
    <div class="card-body">
        <br>
        <form action="search_books.php" method="GET">
        <table class="table table-striped">
            <tr>
                <th>ISBN</th>
                <th>Title</th>
                <th>Category</th>
                <th>Author</th>
                <th>Price</th>
            </tr>
                <tr>
                    <td>
                        <div>
                            <?php
                                $query1 = "SELECT * FROM categories";
                                $result1 = $db->query($query1);

                                if (!$result1) {
                                    die("Could not query the database: <br />" . $db->error);
                                } else {
                                    echo '<label for="name">Category:</label>';
                                    echo '<select name="name" id="name" style="width: 200px; height: 30px; text-align: center;">';
                                    echo '<option value="">-- Choose Category --</option>';
                                    while ($row = $result1->fetch_object()) {
                                        $name = $row->name;
                                        echo '<option value="' . $name . '">' . $name . '</option>';
                                    }
                                    echo '</select>';
                                }
                            ?>
                        </div>
                    </td>
        
                    <td>
                        <div>
                            <label for="isbn">ISBN:</label>
                            <input type="text" name="isbn" id="isbn">
                        </div>
                    </td>

                    <td>
                        <div>
                            <label for="author">Author:</label>
                            <input type="text" name="author" id="author">
                        </div>
                    </td>

                    <td>
                        <div>
                            <label for="title">Title:</label>
                            <textarea name="title" id="title" style="width: 200px; height: 84px;"></textarea>
                        </div>
                    </td>
                        
                    <td>
                        <div>
                            <label for="min_price">Min Price:</label>
                            <input type="text" name="min_price" id="min_price">
                        </div>
                        <div>
                            <label for="max_price">Max Price:</label>
                            <input type="text" name="max_price" id="max_price">
                        </div>
                    </td>
                </tr>
        </table>
        <button type="submit" class="btn btn-primary" name="submit" value="submit"><i class="bi bi-search"></i>&nbsp; Search</button>
        </form>

        <br>
        <?php
        if ($result !== null) {
            echo '<table class="table table-striped">';
            echo '<tr>';
            echo '<th>ISBN</th>';
            echo '<th>Title</th>';
            echo '<th>Category</th>';
            echo '<th>Author</th>';
            echo '<th>Price</th>';
            echo '</tr>';
            while ($row = $result->fetch_object()) {
                echo '<tr>';
                echo '<td>' . $row->isbn . '</td>';
                echo '<td>' . $row->title . '</td>';
                echo '<td>' . $row->name . '</td>';
                echo '<td>' . $row->author . '</td>';
                echo '<td>' . $row->price . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo 'No results found.';
        }

        $result->close();
        $db->close();
        ?>

    <br>
    <a href="view_books.php" class="btn btn-primary mb-4"><i class="bi bi-caret-left-fill"></i>&nbsp;Back</a>
    </div>
</div>
<?php include('./footer.php') ?>
<br>

