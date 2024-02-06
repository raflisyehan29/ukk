<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "ukk";

    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

   
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

  
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    
    $query = "SELECT * FROM petugas WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);


    if ($result === false) {
        
        echo '<div class="alert alert-danger" role="alert">Error menjalankan query: ' . $conn->error . '</div>';
    } else { 
        if ($result->num_rows == 1) {
  
            $row = $result->fetch_assoc();
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['level'] = $row['level'];
  
            header("Location: index.php");
            exit();
        } else {
            
            $pesan = "Username Atau Password Salah.";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #fafafa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            max-width: 400px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .login-card-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
            color: #262626;
        }

        .form-label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #262626;
        }

        .form-control {
            height: 40px;
            font-size: 16px;
        }

        .icon {
            font-size: 20px;
            margin-right: 10px;
            color: #262626;
        }

        .login-btn {
            height: 40px;
            font-size: 16px;
            margin-top: 20px;
            width: 100%;
            background-color: #0095f6;
            color: #fff;
            border: none;
        }

        .login-btn:hover {
            background-color: #0080e6;
        }

        .login-btn:focus {
            outline: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card login-card">
                <div class="card-body">
                    <div class="login-card-header">
                        <i class="fas fa-user icon"></i> Login
                    </div>
                    <?php if (isset($pesan)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $pesan ?>
                        </div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label"><i class="fas fa-user icon"></i> Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"><i class="fas fa-lock icon"></i> Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary login-btn">Login</button>
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
