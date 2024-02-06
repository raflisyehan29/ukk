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

$queryEnumValues = "SHOW COLUMNS FROM petugas LIKE 'level'";
$resultEnumValues = $conn->query($queryEnumValues);
$rowEnumValues = $resultEnumValues->fetch_assoc();
$enumValues = explode(",", str_replace("'", "", substr($rowEnumValues['Type'], 5, -1)));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaPetugas = $_POST['nama_petugas'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    $queryTambahPetugas = "INSERT INTO petugas (nama_petugas, username, password, level) 
                           VALUES ('$namaPetugas', '$username', '$password', '$level')";

    if ($conn->query($queryTambahPetugas) === TRUE) {
        $pesan = "Petugas baru berhasil ditambahkan.";
    } else {
        $pesan = "Error adding petugas: " . $conn->error;
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
    <title>Tambah Petugas</title>
</head>

<body>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Tambah Petugas</h2>
            <?php if (isset($pesan)) : ?>
                <div class="alert alert-success" role="alert">
                    <?= $pesan ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="nama_petugas">Nama Petugas:</label>
                    <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" required>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="level">Level:</label>
                    <select class="form-control" id="level" name="level" required>
                        <?php foreach ($enumValues as $enumValue) : ?>
                            <option value="<?= $enumValue ?>"><?= ucfirst($enumValue) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Petugas</button>
                <a href="petugas.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
