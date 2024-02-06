<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "ukk";

$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerID = mysqli_real_escape_string($conn, $_POST['customerID']);

    $queryDeletePelanggan = "DELETE FROM pelanggan WHERE PelangganID = '$customerID'";

    $message = $conn->query($queryDeletePelanggan) === TRUE ?
        '<div class="alert alert-success" role="alert">Customer deleted successfully.</div>' :
        '<div class="alert alert-danger" role="alert">Error deleting customer: ' . $conn->error . '</div>';
} else {
 
    $customerID = mysqli_real_escape_string($conn, $_GET['id']);
    $queryGetPelanggan = "SELECT * FROM pelanggan WHERE PelangganID = '$customerID'";
    $resultGetPelanggan = $conn->query($queryGetPelanggan);

    if ($resultGetPelanggan->num_rows > 0) {
        $rowPelanggan = $resultGetPelanggan->fetch_assoc();
        $namaPelanggan = $rowPelanggan['NamaPelanggan'];
        $alamat = $rowPelanggan['Alamat'];
        $noTelepon = $rowPelanggan['NomorTelepon'];
    } else {
        $message = '<div class="alert alert-warning" role="alert">Customer not found.</div>';
    }
}


$conn->close();
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

include 'sidebar.php';
?>
<div id="content">
    
            <div class="card">
                <div class="card-header">
                    Delete Customer
                </div>
                <div class="card-body">
                    <?php echo $message; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="customerID" class="form-label">Customer ID</label>
                            <input type="text" class="form-control" id="customerID" name="customerID" value="<?php echo $customerID; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="namaPelanggan" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="namaPelanggan" name="namaPelanggan" value="<?php echo isset($namaPelanggan) ? $namaPelanggan : ''; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Address</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" readonly><?php echo isset($alamat) ? $alamat : ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="noTelepon" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="noTelepon" name="noTelepon" value="<?php echo isset($noTelepon) ? $noTelepon : ''; ?>" readonly>
                        </div>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                        <a href="pelanggan.php" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
