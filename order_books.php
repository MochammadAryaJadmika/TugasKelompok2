<?php
require_once('./lib/db_login.php');

// deklarasi variabel
$where_clause = [];

// filter date
$tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : date('Y-m-d');
$tanggal_selesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : date('Y-m-d');

// Validate the dates
if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
    // Add the date filter to the WHERE clause
    $where_clause[] = "o.date BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'";
}

// Final WHERE clause for the SQL query
$where_clause_sql = !empty($where_clause) ? implode(' AND ', $where_clause) : '1';

// SQL Query
$query = "SELECT b.isbn, b.title, oi.quantity, c.name AS customer, o.amount, o.date
          FROM books AS b
          JOIN order_items AS oi ON b.isbn = oi.isbn
          JOIN orders AS o ON oi.orderid = o.orderid
          JOIN customers AS c ON o.customerid = c.customerid
          WHERE $where_clause_sql";

// Modify the query to include an additional condition for initial load
if (empty($_GET['tanggal_mulai']) && empty($_GET['tanggal_selesai'])) {
    $query = "SELECT b.isbn, b.title, oi.quantity, c.name AS customer, o.amount, o.date
              FROM books AS b
              JOIN order_items AS oi ON b.isbn = oi.isbn
              JOIN orders AS o ON oi.orderid = o.orderid
              JOIN customers AS c ON o.customerid = c.customerid";
}

$result = $db->query($query);
?>


<?php include('./header.php') ?>
<div class="card mt-4">
    <div class="card-header">Data Order</div>
    <div class="card-body">
        <br>
        <form action="order_books.php" method="GET">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_mulai">Start Date:</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo $tanggal_mulai; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_selesai">End Date:</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="<?php echo $tanggal_selesai; ?>">
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Filter Data</button>
            <!-- <hr> -->
        </form>
        
        <br>

        <?php
        if ($result !== null) {
            echo '<table class="table table-striped">';
            echo '<tr>';
            echo '<th>ISBN</th>';
            echo '<th>Title</th>';
            echo '<th>Customer</th>';
            echo '<th>Quantity</th>';
            echo '<th>Amount</th>';
            echo '<th>Date</th>';
            echo '</tr>';
            while ($row = $result->fetch_object()) {
                echo '<tr>';
                echo '<td>' . $row->isbn . '</td>';
                echo '<td>' . $row->title . '</td>';
                echo '<td>' . $row->customer . '</td>';
                echo '<td>' . $row->quantity . '</td>';
                echo '<td>' . $row->amount . '</td>';
                echo '<td>' . $row->date . '</td>';
                echo '</tr>';
            }

            echo '</table>';
            echo 'Total Rows = ' . $result->num_rows;
        } else {
            echo 'No results found.';
        }

        $result->close();
        $db->close();
        ?>


    </div>
</div>

<?php include('./footer.php') ?>
