<?php include('./header.php') ?>
<div class="card mt-5">
    <div class="card-header">Customers Data</div>
    <div class="card-body">
        <a href="add_customer.php" class="btn btn-primary mb-4">+ Add Customer Data</a>
        <br>
        <table class="table table-striped">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Action</th>
            </tr>
            <?php
            // Include our login information
            require_once('./lib/db_login.php');

            // Execute the query
            $query = "SELECT customerid AS ID, name AS Nama, address AS Alamat, city AS Kota FROM customers ORDER BY customerid";
            $result = $db->query($query);

            if (!$result) {
                die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
            }

            // Fetch and display the results
            $i = 1;
            while ($row = $result->fetch_object()) {
                echo '<tr>';
                echo '<td>' . $i . '</td>';
                echo '<td>' . $row->Nama . '</td>';
                echo '<td>' . $row->Alamat . '</td>';
                echo '<td>' . $row->Kota . '</td>';
                echo '<td><a class="btn btn-warning btn-sm" href="edit_customer.php?id=' . $row->ID . '">Edit</a>&nbsp;<a class="btn btn-danger btn-sm" href="confirm_delete_customer.php?id=' . $row->ID . '">Delete</a></td>';
                echo '</tr>';
                $i++;
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