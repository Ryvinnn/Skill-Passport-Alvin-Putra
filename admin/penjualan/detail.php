
<?php
require_once '../../config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: riwayat.php");
    exit;
}

$id = (int) $_GET['id'];

$queryPenjualan = mysqli_prepare(
    $conn,
    "SELECT
        penjualan.PenjualanID,
        penjualan.Tanggal_penjualan,
        penjualan.Total_harga,
        pelanggan.NamaPelanggan
     FROM penjualan
     LEFT JOIN pelanggan
        ON penjualan.PelangganID = pelanggan.PelangganID
     WHERE penjualan.PenjualanID = ?"
);

mysqli_stmt_bind_param($queryPenjualan, "i", $id);
mysqli_stmt_execute($queryPenjualan);

$resultPenjualan = mysqli_stmt_get_result($queryPenjualan);
$penjualan = mysqli_fetch_assoc($resultPenjualan);

if (!$penjualan) {
    die("Transaksi tidak ditemukan.");
}

$queryDetail = mysqli_prepare(
    $conn,
    "SELECT
        detail_penjualan.Produk_id,
        produk.Nama_produk,
        produk.Harga,
        detail_penjualan.Jumlah_produk,
        detail_penjualan.Subtotal
     FROM detail_penjualan
     INNER JOIN produk
        ON detail_penjualan.Produk_id = produk.Produk_id
     WHERE detail_penjualan.Penjualan_id = ?"
);

mysqli_stmt_bind_param($queryDetail, "i", $id);
mysqli_stmt_execute($queryDetail);

$resultDetail = mysqli_stmt_get_result($queryDetail);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Transaksi - Aplikasi Kasir</title>

    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="dashboard-container">

    <aside class="sidebar">
        <h2>APLIKASI KASIR</h2>

        <nav>
            <a href="../dashboard.php">Dashboard</a>
            <a href="../produk/index.php">Data Produk</a>
            <a href="../pelanggan/index.php">Data Pelanggan</a>
            <a href="index.php" class="active">Data Penjualan</a>
            <a href="../../proses/logout.php">Logout</a>
        </nav>
    </aside>

    <main class="main-content">

        <header class="topbar">
            <h2>Detail Transaksi</h2>
            <p>Informasi lengkap transaksi penjualan.</p>
        </header>

        <section class="product-section">

            <div class="product-header">
                <h3>Informasi Penjualan</h3>

                <a href="riwayat.php" class="btn-edit">
                    Kembali
                </a>
            </div>

            <div class="form-group">
                <label>ID Penjualan</label>
                <p><?= htmlspecialchars($penjualan['PenjualanID']); ?></p>
            </div>

            <div class="form-group">
                <label>Tanggal Penjualan</label>
                <p><?= htmlspecialchars($penjualan['Tanggal_penjualan']); ?></p>
            </div>

            <div class="form-group">
                <label>Nama Pelanggan</label>
                <p><?= htmlspecialchars($penjualan['NamaPelanggan'] ?? '-'); ?></p>
            </div>

            <div class="table-container">

                <table class="product-table">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    $no = 1;

                    if (mysqli_num_rows($resultDetail) > 0) :
                        while ($detail = mysqli_fetch_assoc($resultDetail)) :
                    ?>

                        <tr>
                            <td><?= $no++; ?></td>

                            <td>
                                <?= htmlspecialchars($detail['Nama_produk']); ?>
                            </td>

                            <td>
                                Rp <?= number_format($detail['Harga'], 0, ',', '.'); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($detail['Jumlah_produk']); ?>
                            </td>

                            <td>
                                Rp <?= number_format($detail['Subtotal'], 0, ',', '.'); ?>
                            </td>
                        </tr>

                    <?php
                        endwhile;
                    else :
                    ?>

                        <tr>
                            <td colspan="5" class="empty-data">
                                Belum ada detail produk.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="4">Total Harga</th>
                            <th>
                                Rp <?= number_format($penjualan['Total_harga'], 0, ',', '.'); ?>
                            </th>
                        </tr>
                    </tfoot>

                </table>

            </div>

        </section>

    </main>

</div>

</body>
</html>
