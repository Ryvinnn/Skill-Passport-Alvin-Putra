
<?php

session_start();

require_once "../../config/koneksi.php";

$query = mysqli_query($conn, "SELECT * FROM produk");

if (!$query) {
    die("Query gagal: " . mysqli_error($conn));
}

$nama = $_SESSION['Nama'] ?? 'Petugas';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Aplikasi Kasir</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="dashboard-container">

    <aside class="sidebar">

        <h2>Kasir App</h2>

        <p class="sidebar-label">MENU UTAMA</p>

        <nav>
            <a href="../dashboard.php">Dashboard</a>
            <a href="index.php" class="active">Data Produk</a>
            <a href="../pelanggan/index.php">Data Pelanggan</a>
            <a href="../penjualan/index.php">Transaksi Penjualan</a>
            <a href="../laporan/index.php">Riwayat Penjualan</a>
        </nav>

        <a href="../../proses/logout.php" class="sidebar-logout">
            Logout
        </a>

    </aside>

    <main class="main-content">

        <header class="topbar">

            <h3>Data Produk</h3>

            <div class="user-info">
                <?= htmlspecialchars($nama) ?>
                <span>Petugas</span>
            </div>

        </header>

        <section class="content">

            <div class="welcome">
                <h2>Data Produk</h2>
                <p>Kelola seluruh data produk toko.</p>
            </div>

            <div class="content-box">

                <div class="product-header">
                    <h3>Daftar Produk</h3>

                    <a href="tambah.php" class="btn-tambah">
                        + Tambah Produk
                    </a>
                </div>

                <div class="table-container">

                    <table class="product-table">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Produk</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php
                        $no = 1;

                        if (mysqli_num_rows($query) > 0) :

                            while ($produk = mysqli_fetch_assoc($query)) :
                        ?>

                            <tr>
                                <td><?= $no++; ?></td>

                                <td>
                                    <?= htmlspecialchars($produk['Produk_id']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($produk['Nama_produk']); ?>
                                </td>

                                <td>
                                    Rp <?= number_format($produk['Harga'], 0, ',', '.'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($produk['Stok']); ?>
                                </td>

                                <td>
                                    <a href="edit.php?id=<?= $produk['Produk_id']; ?>" class="btn-edit">
                                        Edit
                                    </a>

                                    <form action="hapus.php" method="POST"
                                          style="display: inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus produk ini?');">

                                        <input type="hidden" name="id"
                                               value="<?= $produk['Produk_id']; ?>">

                                        <button type="submit" class="btn-hapus">
                                            Hapus
                                        </button>

                                    </form>
                                </td>
                            </tr>

                        <?php
                            endwhile;

                        else :
                        ?>

                            <tr>
                                <td colspan="6" class="empty-data">
                                    Belum ada data produk.
                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

    </main>

</div>

</body>
</html>
