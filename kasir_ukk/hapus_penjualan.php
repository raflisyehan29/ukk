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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   
    $penjualanID = $_GET['id'];

    $queryHapusDetailPenjualan = "DELETE FROM detailpenjualan WHERE PenjualanID = $penjualanID";

    if ($conn->query($queryHapusDetailPenjualan) === TRUE) {
       
        $queryHapusPenjualan = "DELETE FROM penjualan WHERE PenjualanID = $penjualanID";

        if ($conn->query($queryHapusPenjualan) === TRUE) {
            echo '<div class="alert alert-success" role="alert">Penjualan Berhasil Di hapus.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error deleting penjualan: ' . $conn->error . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Error deleting detail penjualan: ' . $conn->error . '</div>';
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Delete Penjualan</title>
</head>
<body>

<div class="container mt-5">
    <a href="penjualan.php" class="btn btn-secondary">Kembali ke Daftar Penjualan</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
