
<?php
session_start();
require_once '../config/koneksi.php';


$queryProduk = mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk");
$dataProduk = mysqli_fetch_assoc($queryProduk);

$queryPelanggan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pelanggan");
$dataPelanggan = mysqli_fetch_assoc($queryPelanggan);

$queryPenjualan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM penjualan");
$dataPenjualan = mysqli_fetch_assoc($queryPenjualan);

$queryPendapatan = mysqli_query($conn, "SELECT SUM(Total_harga) AS total FROM penjualan");
$dataPendapatan = mysqli_fetch_assoc($queryPendapatan);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Aplikasi Kasir</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="dashboard-container">

    <aside class="sidebar">
        <h2>APLIKASI KASIR</h2>

        <nav>
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="produk/index.php">Data Produk</a>
            <a href="pelanggan/index.php">Data Pelanggan</a>
            <a href="penjualan/index.php">Data Penjualan</a>
            <a href="../proses/logout.php">Logout</a>
        </nav>
    </aside>

    <main class="main-content">

        <header class="topbar">
            <h2>Dashboard Admin</h2>
            <p>Selamat datang di Aplikasi Kasir</p>
        </header>

        <section class="dashboard-cards">

            <div class="card">
                <h3>Total Produk</h3>
                <p><?= $dataProduk['total']; ?></p>
            </div>

            <div class="card">
                <h3>Total Pelanggan</h3>
                <p><?= $dataPelanggan['total']; ?></p>
            </div>

            <div class="card">
                <h3>Total Transaksi</h3>
                <p><?= $dataPenjualan['total']; ?></p>
            </div>

            <div class="card">
                <h3>Total Pendapatan</h3>
                <p>Rp <?= number_format($dataPendapatan['total'] ?? 0, 0, ',', '.'); ?></p>
            </div>

        </section>

    </main>

</div>

</body>
</html>
