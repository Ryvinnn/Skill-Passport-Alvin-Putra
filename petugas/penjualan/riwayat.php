
<?php

session_start();

require_once '../../config/koneksi.php';

$query = mysqli_query($conn, "
    SELECT
        penjualan.PenjualanID,
        penjualan.Tanggal_penjualan,
        penjualan.Total_harga,
        pelanggan.NamaPelanggan
    FROM penjualan
    LEFT JOIN pelanggan
        ON penjualan.PelangganID = pelanggan.PelangganID
    ORDER BY penjualan.PenjualanID DESC
");

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

    <title>Riwayat Transaksi - Aplikasi Kasir</title>

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
            <a href="../penjualan/index.php">Transaksi Penjualan</a>
            <a href="index.php" class="active">Riwayat Penjualan</a>
        </nav>

        <a href="../../proses/logout.php" class="sidebar-logout">
            Logout
        </a>

    </aside>


    <main class="main-content">

        <header class="topbar">

            <h3>Riwayat Transaksi</h3>

            <div class="user-info">
                <?= htmlspecialchars($nama) ?>
                <span>Petugas</span>
            </div>

        </header>


        <section class="content">

            <div class="product-section">

                <div class="product-header">

                    <h3>Daftar Transaksi</h3>

                    <a href="../penjualan/index.php" class="btn-tambah">
                        + Transaksi Baru
                    </a>

                </div>


                <div class="table-container">

                    <table class="product-table">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Penjualan</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>


                        <tbody>

                        <?php

                        $no = 1;

                        if (mysqli_num_rows($query) > 0) :

                            while ($data = mysqli_fetch_assoc($query)) :

                        ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td>
                                    <?= htmlspecialchars($data['PenjualanID']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['Tanggal_penjualan']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['NamaPelanggan'] ?? '-'); ?>
                                </td>

                                <td>
                                    Rp <?= number_format($data['Total_harga'], 0, ',', '.'); ?>
                                </td>

                                <td>

                                    <a href="detail.php?id=<?= $data['PenjualanID']; ?>"
                                       class="btn-edit">
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        <?php

                            endwhile;

                        else :

                        ?>

                            <tr>
                                <td colspan="6" class="empty-data">
                                    Belum ada transaksi penjualan.
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
