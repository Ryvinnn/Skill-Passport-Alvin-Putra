
<?php

session_start();

require_once '../../config/koneksi.php';

$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY NamaPelanggan ASC");

$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY Nama_produk ASC");

if (!$pelanggan || !$produk) {
    die("Query gagal: " . mysqli_error($conn));
}

$nama = $_SESSION['Nama'] ?? 'Petugas';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transaksi Penjualan - Aplikasi Kasir</title>

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
            <a href="../pelanggan/index.php">Data Pelanggan</a>
            <a href="index.php" class="active">Transaksi Penjualan</a>
            <a href="../laporan/index.php">Riwayat Penjualan</a>
        </nav>

        <a href="../../proses/logout.php" class="sidebar-logout">
            Logout
        </a>

    </aside>


    <main class="main-content">

        <header class="topbar">

            <h3>Transaksi Penjualan</h3>

            <div class="user-info">
                <?= htmlspecialchars($nama) ?>
                <span>Petugas</span>
            </div>

        </header>


        <section class="content">

            <div class="product-section">

                <div class="product-header">

                    <h3>Buat Transaksi Baru</h3>

                    <a href="riwayat.php" class="btn-edit">
                        Riwayat Transaksi
                    </a>

                </div>


                <form action="proses_tambah.php" method="POST">

                    <div class="form-group">

                        <label>Pilih Pelanggan</label>

                        <select name="PelangganID" required>

                            <option value="">-- Pilih Pelanggan --</option>

                            <?php while ($data = mysqli_fetch_assoc($pelanggan)) : ?>

                                <option value="<?= $data['PelangganID']; ?>">
                                    <?= htmlspecialchars($data['NamaPelanggan']); ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Pilih Produk</label>

                        <select name="Produk_id" required>

                            <option value="">-- Pilih Produk --</option>

                            <?php while ($data = mysqli_fetch_assoc($produk)) : ?>

                                <option value="<?= $data['Produk_id']; ?>">
                                    <?= htmlspecialchars($data['Nama_produk']); ?>
                                    - Rp <?= number_format($data['Harga'], 0, ',', '.'); ?>
                                    (Stok: <?= $data['Stok']; ?>)
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Jumlah Produk</label>

                        <input
                            type="number"
                            name="JumlahProduk"
                            min="1"
                            placeholder="Masukkan jumlah produk"
                            required
                        >

                    </div>


                    <button type="submit" class="btn-tambah">
                        Simpan Transaksi
                    </button>

                </form>

            </div>

        </section>

    </main>

</div>

</body>
</html>
