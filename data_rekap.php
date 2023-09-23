<?php
require_once('./lib/db_login.php');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Query untuk mendapatkan jumlah buku per kategori
$category_books_query = "SELECT categories.name as category, COUNT(*) as total FROM books 
                        INNER JOIN categories ON books.categoryid = categories.categoryid 
                        GROUP BY categories.name";
$category_books_result = $db->query($category_books_query);

// Query untuk mendapatkan total buku yang telah di-order per kategori
$category_ordered_query = "SELECT categories.name as category, COUNT(*) as total FROM books 
                        INNER JOIN order_items ON books.isbn = order_items.isbn
                        INNER JOIN categories ON books.categoryid = categories.categoryid 
                        GROUP BY categories.name";
$category_ordered_result = $db->query($category_ordered_query);

// Ambil hasil query
$category_books_data = [];
$category_ordered_data = [];

while($row = $category_books_result->fetch_assoc()) {
    $category_books_data[$row['category']] = $row['total'];
}

while($row = $category_ordered_result->fetch_assoc()) {
    $category_ordered_data[$row['category']] = $row['total'];
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Rekap Data Buku</title>
 
</head>

<?php include('./header.php') ?>
<div class="card mt-4">
    <div class="card-header">Rekap Data</div>
    <div class="card-body">
        <br>
        <form action="data_rekap.php" method="GET">

        <!-- Tampilkan Grafik -->
        <div class="row">
            <div class="col-5">
                <h3>Jumlah Data Buku</h3>
                <div>
                    <canvas id="categoryBooksChart" width="15" height="10"></canvas>
                </div>
            </div>
        
            <div class="col-5">
                <h3>Total Data Buku yang Diorder</h3>
                <div>
                    <canvas id="categoryOrderedChart" width="15" height="10"></canvas>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        var ctx1 = document.getElementById('categoryBooksChart').getContext('2d');
        var ctx2 = document.getElementById('categoryOrderedChart').getContext('2d');

        var categories = <?php echo json_encode(array_keys($category_books_data)); ?>;
        var booksCount = <?php echo json_encode(array_values($category_books_data)); ?>;
        var orderedCount = <?php echo json_encode(array_values($category_ordered_data)); ?>;

        var categoryBooksChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: categories,
                datasets: [{
                    label: 'Jumlah Buku',
                    data: booksCount,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var categoryOrderedChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: categories,
                datasets: [{
                    label: 'Total Di-order',
                    data: orderedCount,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        </script>
    <br>
    <a href="view_books.php" class="btn btn-secondary mb-4"> <i class="bi bi-caret-left-fill"></i>&nbsp;Back</a>
    </div>
        <?php include('./footer.php') ?>
</html>
