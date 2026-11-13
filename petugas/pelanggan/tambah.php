
<?php

session_start();

require_once '../../config/koneksi.php';

$nama = $_SESSION['Nama'] ?? 'Petugas';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Pelanggan - Aplikasi Kasir</title>

    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="dashboard-container">

    <aside class="sidebar">

        <h2>Kasir App</h2>

        <p class="sidebar-label">MENU UTAMA</p>

        <nav>
            <a href="../dashboard.php">Dashboard</a>
            <a href="../produk/index.php">Data Produk</a>
            <a href="index.php" class="active">Data Pelanggan</a>
            <a href="../penjualan/index.php">Transaksi Penjualan</a>
            <a href="../laporan/index.php">Riwayat Penjualan</a>
        </nav>

        <a href="../../proses/logout.php" class="sidebar-logout">
            Logout
        </a>

    </aside>


    <main class="main-content">

        <header class="topbar">

            <h3>Tambah Pelanggan</h3>

            <div class="user-info">
                <?= htmlspecialchars($nama) ?>
                <span>Petugas</span>
            </div>

        </header>


        <section class="content">

            <div class="product-section">

                <div class="product-header">

                    <h3>Form Tambah Pelanggan</h3>

                    <a href="index.php" class="btn-edit">
                        Kembali
                    </a>

                </div>


                <form action="proses_tambah.php" method="POST">

                    <div class="form-group">

                        <label>Nama Pelanggan</label>

                        <input
                            type="text"
                            name="NamaPelanggan"
                            placeholder="Masukkan nama pelanggan"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Alamat</label>

                        <textarea
                            name="Alamat"
                            placeholder="Masukkan alamat pelanggan"
                            required
                        ></textarea>

                    </div>


                    <div class="form-group">

                        <label>Nomor Telepon</label>

                        <input
                            type="text"
                            name="NomorTelepon"
                            placeholder="Masukkan nomor telepon"
                            required
                        >

                    </div>


                    <button type="submit" class="btn-tambah">
                        Simpan Pelanggan
                    </button>

                </form>

            </div>

        </section>

    </main>

</div>

</body>
</html>
