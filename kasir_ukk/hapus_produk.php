<?php
session_start();

// Check if the user is logged in
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


<div id="content">
    
  
            <?php
            $dbHost = "localhost";
            $dbUser = "root";
            $dbPassword = "";
            $dbName = "ukk";

            $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $productId = $_GET['id'];

               
                $queryHapusProduk = "DELETE FROM produk WHERE ProdukID = $productId";

                if ($conn->query($queryHapusProduk) === TRUE) {
                    echo '<div class="alert alert-success" role="alert">Product deleted successfully.</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error deleting product: ' . $conn->error . '</div>';
                }
            }

           
          
            $conn->close();
            ?>

            <a href="dashboard.php" class = "btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>



</body>
</html>
