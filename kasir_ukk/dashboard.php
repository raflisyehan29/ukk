<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$recordsPerPage = 5;
$offset = ($page - 1) * $recordsPerPage;

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

  
</head>
<body>

<?php
include "sidebar.php";
?>

<div id="content">
    <h2>Product List</h2>
    <div class="card mt-2">
        <div class="card-body">

            <form action="" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari Berdasarkan Nama Produk" name="search" value="<?php echo $searchTerm; ?>">
                    <button type="submit" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
</svg></button>
                </div>
            </form>

            <?php
            $dbHost = "localhost";
            $dbUser = "root";
            $dbPassword = "";
            $dbName = "ukk";

            $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $query = "SELECT * FROM produk WHERE NamaProduk LIKE '%$searchTerm%' ORDER BY ProdukID DESC LIMIT $offset, $recordsPerPage";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo '<table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>';
                $counter = $offset + 1;
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>
                            <td>' . $counter . '</td>
                            <td>' . $row["NamaProduk"] . '</td>
                            <td>Rp' . number_format($row["Harga"], 0, ',', '.') . '</td>
                            <td>' . $row["Stok"] . '</td>
                            <td>';

                    if ($_SESSION['level'] == 'admin') {
                        echo '<a href="edit_produk.php?id=' . $row["ProdukID"] . '" class="btn btn-warning">Edit</a>
                              <a href="hapus_produk.php?id=' . $row["ProdukID"] . '" onclick="return confirm(\'Anda yakin ingin menghapus?\')" class="btn btn-danger">Hapus</a>';
                    } else {
                        echo '  <a href="tambah_pembelian.php?id=' . $row["ProdukID"] . '" class="btn btn-success">Tambah Transaksi</a>';
                    }

                    echo '</td>
                        </tr>';
                    $counter++;
                }
                echo '</tbody></table>';

                
                $totalPages = ceil($conn->query("SELECT COUNT(*) FROM produk WHERE NamaProduk LIKE '%$searchTerm%'")->fetch_row()[0] / $recordsPerPage);
                echo '<nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="?page=1&search=' . $searchTerm . '">First</a></li>';
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '&search=' . $searchTerm . '">' . $i . '</a></li>';
                }
                echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '&search=' . $searchTerm . '">Last</a></li>
                        </ul>
                      </nav>';
            } else {
                echo '<div class="alert alert-warning" role="alert">No products found.</div>';
            }

            $conn->close();

            if ($_SESSION['level'] == 'admin') {
                echo '<a href="tambah_produk.php" class="btn btn-primary">Tambah</a>';
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
