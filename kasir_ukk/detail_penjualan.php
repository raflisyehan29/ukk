<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Detail Penjualan</title>
    <style>
        .container {
            max-width: 400px;
        }

        .card {
            margin-top: 20px;
            border: 2px solid #000;
            border-radius: 10px;
        }

        .header {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .content {
            padding: 20px;
        }

        .info {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="card">
            <div class="header">
                <h4>Struk Pembelian</h4>
            </div>
            <div class="content">
                <?php

                $conn = new mysqli("localhost", "root", "", "ukk");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if (isset($_GET['id'])) {
                    $penjualanID = $_GET['id'];

                    $sql = "SELECT penjualan.*, pelanggan.NamaPelanggan 
                            FROM penjualan 
                            LEFT JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                            WHERE penjualan.PenjualanID = $penjualanID";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo '<div class="info"><strong>Penjualan ID:</strong> ' . $row["PenjualanID"] . '</div>';
                        echo '<div class="info"><strong>Tanggal Penjualan:</strong> ' . $row["TanggalPenjualan"] . '</div>';
                        echo '<div class="info"><strong>Total Harga:</strong> Rp' . number_format($row["TotalHarga"], 0, ',', ',') . '</div>';
                        echo '<div class="info"><strong>Nama Pelanggan:</strong> ' . $row["NamaPelanggan"] . '</div>';

                        $sqlDetail = "SELECT dp.*, p.NamaProduk, p.Harga 
                                      FROM detailpenjualan dp
                                      INNER JOIN produk p ON dp.ProdukID = p.ProdukID
                                      WHERE dp.PenjualanID = $penjualanID";
                        $resultDetail = $conn->query($sqlDetail);

                        if ($resultDetail->num_rows > 0) {
                            echo '<div class="info"><strong>Detail Pembelian:</strong></div>';
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-bordered">';
                            echo '<thead>';
                            echo '<tr><th>Nama Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            while ($rowDetail = $resultDetail->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $rowDetail["NamaProduk"] . '</td>';
                                echo '<td>Rp' . number_format($rowDetail["Harga"], 0, ',', ',') . '</td>';
                                echo '<td>' . $rowDetail["JumlahProduk"] . '</td>';
                                echo '<td>Rp' . number_format($rowDetail["Subtotal"], 0, ',', ',') . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            echo '</table>';
                            echo '</div>';
                        } else {
                            echo '<div class="info">Tidak ada detail penjualan.</div>';
                        }
                    } else {
                        echo '<div class="info">Penjualan tidak ditemukan.</div>';
                    }
                    echo '<div class="text-center btn-back mt-3"><a href="penjualan.php" class="btn btn-secondary">Kembali </a></div>';
                } else {
                    echo '<div class="info">ID Penjualan tidak valid.</div>';
                }

                // Tutup koneksi
                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
