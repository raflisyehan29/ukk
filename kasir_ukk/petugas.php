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

$queryCount = "SELECT COUNT(*) AS total_rows FROM petugas";
$resultCount = $conn->query($queryCount);
$totalRows = $resultCount->fetch_assoc()['total_rows'];

$rowsPerPage = 5;
$totalPages = ceil($totalRows / $rowsPerPage);

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}

if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
} elseif ($currentPage < 1) {
    $currentPage = 1;
}

$offset = ($currentPage - 1) * $rowsPerPage;

$queryGetPetugas = "SELECT * FROM petugas ORDER BY id_petugas DESC LIMIT $offset, $rowsPerPage";
$resultGetPetugas = $conn->query($queryGetPetugas);

$userLevel = $_SESSION['level'];
if ($userLevel !== 'admin'){
    echo '<script>alert("Anda Tidak Diizinkan Mengakses Halaman Ini !");
    window.location.href  = " index.php";</script>';
    die();
}



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   
    <title>Manajemen Petugas</title>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div id="content">
    <h2>Manajemen Petugas</h2>
    <div class="card mt-2">
        <div class="card-body">
    <?php if (isset($pesan)) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= $pesan ?>
                        </div>
        <?php endif; ?>
    <table class="table">
        <thead>
        <tr>
            <th>No</th>
           
            <th>Nama Petugas</th>
            <th>Username</th>
            <th>Password</th>
            <th>Level</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
      <?php
$counter = ($currentPage - 1) * $rowsPerPage + 1;

while ($rowPetugas = $resultGetPetugas->fetch_assoc()) {
    echo '<tr>
            <td>' . $counter . '</td>
           
            <td>' . $rowPetugas["nama_petugas"] . '</td>
            <td>' . $rowPetugas["username"] . '</td>
            <td>' . $rowPetugas["password"] . '</td>
            <td>' . $rowPetugas["level"] . '</td>
            <td>
                <a href="edit_petugas.php?id=' . $rowPetugas["id_petugas"] . '"class="btn btn-warning btn-sm">Edit</a>
                <a href="hapus_petugas.php?id=' . $rowPetugas["id_petugas"] . '" onclick="return confirm(\'Anda yakin ingin menghapus?\')" class="btn btn-danger btn-sm">Hapus</a>
            </td>
        </tr>';
    $counter++;
}
?>

        </tbody>
    </table>
  
    <nav aria-label="Page navigation" class="mt-3">
        <ul class="pagination justify-content-start">
            <?php if ($currentPage > 1) : ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo ($currentPage - 1); ?>">Previous</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages) : ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo ($currentPage + 1); ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    </div>

    </div>
    <p></p>
  
    <a href="tambah_petugas.php" class="btn btn-primary">Tambah Petugas</a>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
