<?php
session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $produkID = $_GET['id'];
} else {
    header('Location: dashboard.php');
    exit;
}


$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "ukk";


$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$queryGetProduk = "SELECT * FROM produk WHERE ProdukID = $produkID";
$resultGetProduk = $conn->query($queryGetProduk);

if ($resultGetProduk->num_rows > 0) {
    $produkInfo = $resultGetProduk->fetch_assoc();
} else {
    echo '<div class="alert alert-warning" role="alert">Produk tidak ditemukan.</div>';
    exit;
}

$queryGetPelanggan = "SELECT * FROM pelanggan";
$resultGetPelanggan = $conn->query($queryGetPelanggan);

$conn->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pelangganID = $_POST['pelangganID'];
    $jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : 1;

    $queryTambahPenjualan = "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) 
                             VALUES (NOW(), " . ($produkInfo['Harga'] * $jumlah) . ", $pelangganID)";

    $connInsertPenjualan = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($connInsertPenjualan->connect_error) {
        die("Koneksi gagal: " . $connInsertPenjualan->connect_error);
    }

    if ($connInsertPenjualan->query($queryTambahPenjualan) === TRUE) {
     
        $penjualanID = $connInsertPenjualan->insert_id;

        $queryTambahDetailPenjualan = "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) 
                                       VALUES ($penjualanID, $produkID, $jumlah, " . ($produkInfo['Harga'] * $jumlah) . ")";

        if ($connInsertPenjualan->query($queryTambahDetailPenjualan) === TRUE) {
          
            $stokSekarang = $produkInfo['Stok'] - $jumlah;
            $queryUpdateStok = "UPDATE produk SET Stok = $stokSekarang WHERE ProdukID = $produkID";
            $connInsertPenjualan->query($queryUpdateStok);

            $pesan = "Penjualan berhasil ditambahkan.";
        } else {
            echo '<div class="alert alert-danger" role="alert">Error menambahkan detail pembelian: ' . $connInsertPenjualan->error . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Error menambahkan pembelian: ' . $connInsertPenjualan->error . '</div>';
    }

$userLevel = $_SESSION['level'];
if ($userLevel !== 'petugas'){
    echo '<script>alert("Hanya Petugas !");
    window.location.href  = " index.php";</script>';
    die();
}
    $connInsertPenjualan->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pembelian</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2>Tambah Pembelian</h2>
        <?php if (isset($pesan)) : ?>
            <div class="alert alert-success" role="alert">
                <?= $pesan ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="produk">Nama Produk:</label>
                <input type="text" class="form-control" id="produk" value="<?= $produkInfo['NamaProduk'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="harga">Harga:</label>
                <input type="text" class="form-control" id="harga" value="<?= $produkInfo['Harga'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah:</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" value="1" required>
            </div>
            <div class="form-group">
                <label for="subtotal">Subtotal:</label>
                <input type="text" class="form-control" id="subtotal" value="<?= $produkInfo['Harga'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="pelangganID">Pelanggan:</label>
                <select class="form-control" id="pelangganID" name="pelangganID" required>
                    <?php
                    while ($rowPelanggan = $resultGetPelanggan->fetch_assoc()) {
                        echo '<option value="' . $rowPelanggan['PelangganID'] . '">' . $rowPelanggan['NamaPelanggan'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <p></p>
            <button type="submit" class="btn btn-success">Tambah Pembelian</button>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

    <script>
      
        var hargaInput = document.getElementById('harga');
        var jumlahInput = document.getElementById('jumlah');
        var subtotalInput = document.getElementById('subtotal');

        
        function hitungSubtotal() {
            var harga = parseFloat(hargaInput.value);
            var jumlah = parseInt(jumlahInput.value);
            var subtotal = harga * jumlah;
            subtotalInput.value = subtotal.toFixed(2); 
        }

        hargaInput.addEventListener('input', hitungSubtotal);
        jumlahInput.addEventListener('input', hitungSubtotal);

        hitungSubtotal();
    </script>

</body>

</html>