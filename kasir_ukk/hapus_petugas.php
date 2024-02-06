<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "ukk");

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ambil ID petugas dari URL
    if (isset($_GET['id'])) {
        $petugasID = $_GET['id'];

        // Query untuk menghapus data petugas
        $queryHapusPetugas = "DELETE FROM petugas WHERE id_petugas = $petugasID";

        if ($conn->query($queryHapusPetugas) === TRUE) {
            $pesan = "Petugas berhasil dihapus.";
            header("Location: petugas.php?pesan=$pesan");
            exit;
        } else {
            $pesan = "Error deleting petugas: " . $conn->error;
            header("Location: petugas.php?pesan=$pesan");
            exit;
        }
    } else {
        $pesan = "ID Petugas tidak valid.";
        header("Location: petugas.php?pesan=$pesan");
        exit;
    }
}

// Tutup koneksi
$conn->close();
?>
