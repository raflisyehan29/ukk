<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
include 'sidebar.php';
?>

<div id="content">
    <div class="card">
        <div class="card-header">
            Daftar Pelanggan
        </div>
        <div class="card-body">
            <?php
            $dbHost = "localhost";
            $dbUser = "root";
            $dbPassword = "";
            $dbName = "ukk";

            $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Pagination settings
            $recordsPerPage = 5;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $recordsPerPage;

            $query = "SELECT * FROM pelanggan ORDER BY PelangganID DESC LIMIT $offset, $recordsPerPage";
            $result = $conn->query($query);

            $counter = $offset + 1;

            if ($result->num_rows > 0) {
                echo '<table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">ID Pelanggan</th>
                                <th scope="col">Nama Pelanggan</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Nomor Hp</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>
                            <td>' . $counter . '</td>
                            <td>' . $row["PelangganID"] . '</td>
                            <td>' . $row["NamaPelanggan"] . '</td>
                            <td>' . $row["Alamat"] . '</td>
                            <td>' . $row["NomorTelepon"] . '</td>
                            <td>
                                <a href="edit_pelanggan.php?id=' . $row["PelangganID"] . '" class="btn btn-warning">Edit</a>
                                <a href="hapus_pelanggan.php?id=' . $row["PelangganID"] . '" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>';
                    $counter++;
                }
                echo '</tbody></table>';

                // Pagination controls
                echo '<nav aria-label="Page navigation">
                        <ul class="pagination justify-content-start">'; // Change justify-content-center to justify-content-start
                for ($i = 1; $i <= ceil($conn->query("SELECT COUNT(*) FROM pelanggan")->fetch_row()[0] / $recordsPerPage); $i++) {
                    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                }
                echo '</ul>
                      </nav>';
            } else {
                echo '<div class="alert alert-warning" role="alert">No customers found.</div>';
            }

            // Close the database connection
            $conn->close();
            ?>

            <a href="tambah_pelanggan.php" class="btn btn-primary">Tambah</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
