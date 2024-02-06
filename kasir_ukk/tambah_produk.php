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
    <title>Add Product</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Add Product
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

                  
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $namaproduk = $_POST['namaproduk'];
                        $productPrice = $_POST['productPrice'];
                        $productStock = $_POST['productStock'];

                     
                        $username = "admin"; 
                        $userLevel = "admin"; 

                    
                        if ($userLevel === "admin") {
                         
                            $queryPetugas = "SELECT id_petugas FROM petugas WHERE username = '$username'";
                            $resultPetugas = $conn->query($queryPetugas);

                            if ($resultPetugas->num_rows > 0) {
                                $rowPetugas = $resultPetugas->fetch_assoc();
                                $idPetugas = $rowPetugas["id_petugas"];

                            
                                $queryTambahProduk = "INSERT INTO produk ( NamaProduk, Harga, Stok) VALUES ( '$namaproduk', '$productPrice', '$productStock')";
                                
                                if ($conn->query($queryTambahProduk) === TRUE) {
                                    echo '<div class="alert alert-success" role="alert">Product added successfully.</div>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">Error adding product: ' . $conn->error . '</div>';
                                }
                            }
                        }
                    }
                    ?>

                    <form method="post">
                        <div class="mb-3">
                            <label for="namaproduk" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="namaproduk" name="namaproduk" required>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="productStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="productStock" name="productStock" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
<p></p>
                    <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

</body>
</html>