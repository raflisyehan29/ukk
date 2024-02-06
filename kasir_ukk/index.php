<?php
session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}


$username = $_SESSION['username'];
$level = $_SESSION['level'];

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "ukk";

$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$queryCountProduk = "SELECT COUNT(*) as jumlahProduk FROM produk";
$resultCountProduk = $conn->query($queryCountProduk);
$rowCountProduk = $resultCountProduk->fetch_assoc();
$jumlahProduk = $rowCountProduk['jumlahProduk'];


$queryCountPelanggan = "SELECT COUNT(*) as jumlahPelanggan FROM pelanggan";
$resultCountPelanggan = $conn->query($queryCountPelanggan);
$rowCountPelanggan = $resultCountPelanggan->fetch_assoc();
$jumlahPelanggan = $rowCountPelanggan['jumlahPelanggan'];


$queryCountPenjualan = "SELECT COUNT(*) as jumlahPenjualan FROM penjualan";
$resultCountPenjualan = $conn->query($queryCountPenjualan);
$rowCountPenjualan = $resultCountPenjualan->fetch_assoc();
$jumlahPenjualan = $rowCountPenjualan['jumlahPenjualan'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        #sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
            color: #ffffff;
        }

        #sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 18px;
            color: #ffffff;
            display: block;
            transition: 0.3s;
        }

        #sidebar a:hover {
            background-color: #495057;
        }

        #content {
            margin-left: 250px;
            padding: 50px;
        }

        .count-card {
            width: 200px;
            height: 200px;
            margin: 10px;
            overflow: hidden;
            border: none;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            text-decoration: none;
            color: #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
        }

        .count-card:hover {
            transform: scale(1.05);
        }

        .card-body {
            padding: 20px;
            text-align: center;
        }

        .card-title {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 24px;
            font-weight: bold;
        }

        .detail-button {
            margin-top: 15px;
        }

        .card-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

     
        .count-card:nth-child(1) {
            background-color: #3498db;
            color: #ffffff; 
        }

        .count-card:nth-child(2) {
            background-color: #e74c3c;
            color: #ffffff; 
        }

        .count-card:nth-child(3) {
            background-color: #2ecc71; 
            color: #ffffff; 
        }
    </style>
</head>
<body>

<?php

 include "sidebar.php";
?>

<div id="content">
<div class="container">
       
        <div class="alert alert-info alert-dismissible fade show" role="alert">
      
        Anda Login Sebagai <?php echo $_SESSION['level']; ?>,
        Selamat Datang, <?php echo $_SESSION['username']; ?>!
       
        </div>
    <div class="row justify-content-center">
        <div class="card count-card">
            <div class="card-body">
                <i class="fas fa-shopping-cart card-icon"></i>
                <h5 class="card-title">Total Produk</h5>
                <p class="card-text"><?php echo $jumlahProduk; ?></p>
            </div>
        </div>
        <div class="card count-card">
            <div class="card-body">
                <i class="fas fa-users card-icon"></i>
                <h5 class="card-title">Total Pelanggan</h5>
                <p class="card-text"><?php echo $jumlahPelanggan; ?></p>
            </div>
        </div>
        <div class="card count-card">
            <div class="card-body">
                <i class="fas fa-chart-line card-icon"></i>
                <h5 class="card-title">Total Penjualan</h5>
                <p class="card-text"><?php echo $jumlahPenjualan; ?></p>
            </div>
        </div>
       
    </div>

    <div class="card mt-2 mb-5">
        <div class="card">
            <div class="card-body text-center">
                &copy; Aplikasi Kasir - Design By Rafli Syehan
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

</body>
</html>
