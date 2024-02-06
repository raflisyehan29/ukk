
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
            padding: 20px;
        }

        #content h2 {
            margin-bottom: 20px;
        }
    </style>
<div id="sidebar">
  <a href="index.php"><i class="fas fa-chart-line"></i> Dashboard</a>
  <a href="dashboard.php"><i class="fas fa-box"></i> Products</a>
  <a href="penjualan.php"><i class="fas fa-shopping-cart"></i> Penjualan</a>
  <a href="pelanggan.php"><i class="fas fa-users"></i> Pelanggan</a>
  <?php
  if($_SESSION['level'] == "admin") {
      echo '<a href="petugas.php"><i class="fas fa-user"></i> Petugas</a>';
  }
  ?>
  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
