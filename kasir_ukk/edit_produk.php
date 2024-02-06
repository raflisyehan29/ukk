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
    <title>Edit Product</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Edit Product
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

                    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                       
                        $productId = $_GET['id'];

                      
                        $query = "SELECT * FROM produk WHERE ProdukID = $productId";
                        $result = $conn->query($query);

                        if ($result->num_rows == 1) {
                            $row = $result->fetch_assoc();
                            $namaproduk = $row["NamaProduk"];
                            $harga =  $row["Harga"];
                            $stok = $row["Stok"];
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Product not found.</div>';
                            exit();
                        }
                    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                      
                        $productId = $_POST['productId'];
                        $namaproduk = $_POST['namaproduk'];
                        $harga = $_POST['harga'];
                        $stok = $_POST['stok'];

                     
                        $queryUpdate = "UPDATE produk SET NamaProduk = '$namaproduk', Harga = '$harga', Stok = '$stok' WHERE ProdukID = $productId";

                        if ($conn->query($queryUpdate) === TRUE) {
                            echo '<div class="alert alert-success" role="alert">Product updated successfully.</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Error updating product: ' . $conn->error . '</div>';
                        }
                    }
                    ?>

                    <form method="post">
                        <input type="hidden" name="productId" value="<?php echo $productId; ?>">
                        <div class="mb-3">
                            <label for="namaproduk" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="namaproduk" name="namaproduk" value="<?php echo $namaproduk; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Price</label>
                            <input type="number" class="form-control" id="harga" name="harga" value="<?php echo $harga; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $stok; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>
                    <p></p>
                    <a href="dashboard.php"class = "btn btn-secondary">kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

</body>
</html>