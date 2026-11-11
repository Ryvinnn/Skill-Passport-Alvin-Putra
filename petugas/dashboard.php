
<?php

session_start();

require_once "../config/koneksi.php";

$query_produk = mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk");
$data_produk = mysqli_fetch_assoc($query_produk);

$query_pelanggan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pelanggan");
$data_pelanggan = mysqli_fetch_assoc($query_pelanggan);

$query_penjualan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM penjualan");
$data_penjualan = mysqli_fetch_assoc($query_penjualan);

$nama = $_SESSION['Nama'] ?? 'Petugas';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Aplikasi Kasir</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="dashboard-container">

    <aside class="sidebar">

        <h2>Kasir App</h2>

        <p class="sidebar-label">MENU UTAMA</p>

        <nav>
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="../produk/index.php">Data Produk</a>
            <a href="../pelanggan/index.php">Data Pelanggan</a>
            <a href="../penjualan/index.php">Transaksi Penjualan</a>
            <a href="../laporan/index.php">Riwayat Penjualan</a>
        </nav>

        <a href="../proses/logout.php" class="sidebar-logout">
            Logout
        </a>

    </aside>


    <main class="main-content">

        <header class="topbar">

            <h3>Dashboard Petugas</h3>

            <div class="user-info">
                <?= htmlspecialchars($nama) ?>
                <span>Petugas</span>
            </div>

        </header>


        <section class="content">

            <div class="welcome">
                <h2>Selamat Datang, <?= htmlspecialchars($nama) ?>!</h2>
                <p>Selamat bekerja dan semoga aktivitas berjalan lancar.</p>
            </div>


            <div class="card-container">

                <div class="stat-card">
                    <h4>Total Produk</h4>
                    <h2><?= $data_produk['total'] ?></h2>
                </div>

                <div class="stat-card">
                    <h4>Total Pelanggan</h4>
                    <h2><?= $data_pelanggan['total'] ?></h2>
                </div>

                <div class="stat-card">
                    <h4>Total Penjualan</h4>
                    <h2><?= $data_penjualan['total'] ?></h2>
                </div>

            </div>


            <div class="content-box">

                <h3>Informasi Petugas</h3>

                <p>
                    Gunakan menu navigasi untuk melihat produk,
                    mengelola data pelanggan, dan melakukan transaksi penjualan.
                </p>

            </div>

        </section>

    </main>

</div>

</body>
</html>
