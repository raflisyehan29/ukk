<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli("localhost", "root", "", "ukk");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$pesanBerhasil = $pesanError = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $petugasID = $_GET['id'];
        $queryGetPetugas = "SELECT * FROM petugas WHERE id_petugas = $petugasID";
        $resultGetPetugas = $conn->query($queryGetPetugas);

        if ($resultGetPetugas->num_rows > 0) {
            $petugasInfo = $resultGetPetugas->fetch_assoc();
        } else {
            echo '<p class="text-center">Petugas tidak ditemukan.</p>';
            exit;
        }
    } else {
        echo '<p class="text-center">ID Petugas tidak valid.</p>';
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $petugasID = $_POST['petugasID'];
    $namaPetugas = $_POST['nama_petugas'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    $queryUpdatePetugas = "UPDATE petugas SET 
                           nama_petugas = '$namaPetugas', 
                           username = '$username', 
                           password = '$password', 
                           level = '$level' 
                           WHERE id_petugas = $petugasID";

    if ($conn->query($queryUpdatePetugas) === TRUE) {
       $pesan = "Petugas baru berhasil diperbarui.";
         header('Location: petugas.php');
    } else {
        $pesanError = "Error updating petugas: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Edit Petugas</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            max-width: 300px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .btn-primary {
            width: 100%;
        }

        .btn-secondary {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2>Edit Petugas</h2>
          <?php if (isset($pesan)) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= $pesan ?>
                        </div>
        <?php endif; ?>
        <?php if (!empty($pesanError)) : ?>
            <div id="errorAlert" class="alert alert-danger fade show" role="alert">
                <?= $pesanError ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="petugasID" value="<?= $petugasInfo['id_petugas'] ?>">
            <div class="form-group">
                <label for="nama_petugas">Nama Petugas:</label>
                <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" value="<?= $petugasInfo['nama_petugas'] ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $petugasInfo['username'] ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" value="<?= $petugasInfo['password'] ?>" required>
            </div>
            <div class="form-group">
                <label for="level">Level:</label>
                <select class="form-control" id="level" name="level" required>
                    <option value="1" <?= $petugasInfo['level'] == 1 ? 'selected' : '' ?>>Admin</option>
                    <option value="2" <?= $petugasInfo['level'] == 2 ? 'selected' : '' ?>>Kasir</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="petugas.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
