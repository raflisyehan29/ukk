<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli("localhost", "root", "", "ukk");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recordsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

$sql = "SELECT penjualan.*, pelanggan.NamaPelanggan , produk.NamaProduk
        FROM penjualan 
        LEFT JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
        LEFT JOIN detailpenjualan ON penjualan.PenjualanID = detailpenjualan.PenjualanID
        LEFT JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID
        ORDER BY penjualan.PenjualanID DESC 
        LIMIT $offset, $recordsPerPage";

$result = $conn->query($sql);

$totalRecords = $conn->query("SELECT COUNT(*) FROM penjualan")->fetch_row()[0];
$totalPages = ceil($totalRecords / $recordsPerPage);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Daftar Penjualan</title>
</head>

<body>

<?php
include 'sidebar.php';
?>

<div id="content">
    <div class="container mt-5">
        <?php
        if (isset($_GET['pesan'])) {
            echo '<div class="alert alert-info" role="alert">' . $_GET['pesan'] . '</div>';
        }

        if ($result->num_rows == 0) {
            echo '<div class="alert alert-warning" role="alert">Tidak ada penjualan.</div>';
        } else {
            ?>
            <div class="card mt-2">
                <div class="card-body">
                    <h2>Daftar Penjualan</h2>
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-left">No.</th>
                            <th class="text-left">Tanggal Penjualan</th>
                               <th class="text-left">Nama Produk</th>
                            <th class="text-left">Total Harga</th>
                            <th class="text-left">Nama Pelanggan</th>
                            <th class="text-left">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $counter = $offset + 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='text-left'>" . $counter . "</td>";
                            echo "<td class='text-left'>" . $row["TanggalPenjualan"] . "</td>";
                              echo "<td class='text-left'>" . $row["NamaProduk"] . "</td>";
                            echo "<td class='text-left'>Rp" . number_format($row["TotalHarga"], 0, ',', ',') . "</td>";
                            echo "<td class='text-left'>" . $row["NamaPelanggan"] . "</td>";
                            echo '<td class="text-left">
                              <a href="detail_penjualan.php?id=' . $row["PenjualanID"] . '" class="btn btn-info btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-data" viewBox="0 0 16 16">
                              <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0z"/>
                              <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                              <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                            </svg></a>
                              <a href="hapus_penjualan.php?id=' . $row["PenjualanID"] . '" onclick="return confirm(\'Anda yakin ingin menghapus?\')" class="btn btn-danger btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                              <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                            </svg></a>
                          </td>';
                            echo "</tr>";
                            $counter++;
                        }
                        ?>
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=1">First</a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $totalPages; ?>">Last</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
