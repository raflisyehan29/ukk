<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "ukk";

$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaPelanggan = mysqli_real_escape_string($conn, $_POST['namaPelanggan']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $noTelepon = mysqli_real_escape_string($conn, $_POST['noTelepon']);

    // Query untuk menyimpan data pelanggan ke database
    $queryTambahPelanggan = "INSERT INTO pelanggan (NamaPelanggan, Alamat, NomorTelepon) 
                            VALUES ('$namaPelanggan', '$alamat', '$noTelepon')";

    if ($conn->query($queryTambahPelanggan) === TRUE) {
        $pesan = "Petugas baru berhasil ditambahkan.";
    } else {
        echo '<div class="alert alert-danger" role="alert">Error adding customer: ' . $conn->error . '</div>';
    }
}

// Tutup koneksi database
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                      Tambah Pelanggan
                    </div>
                    <?php if (isset($pesan)) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= $pesan ?>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <form method="post">
                            
                            <div class="mb-3">
                                <label for="namaPelanggan" class="form-label">Tambah Pelanggan</label>
                                <input type="text" class="form-control" id="namaPelanggan" name="namaPelanggan" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="noTelepon" class="form-label">Nomor Hp</label>
                                <input type="tel" class="form-control" id="noTelepon" name="noTelepon" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Customer</button>
                        </form>
                        <p></p>

                        <a href="pelanggan.php" class="btn btn-secondary">kembali</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

</body>

</html>