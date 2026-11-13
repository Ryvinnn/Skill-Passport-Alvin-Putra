
<?php

session_start();

require_once "../../config/koneksi.php";

// Ambil nama petugas
$nama = $_SESSION['Nama'] ?? 'Petugas';

// Ambil filter tanggal
$tanggalAwal = $_GET['tanggal_awal'] ?? '';
$tanggalAkhir = $_GET['tanggal_akhir'] ?? '';

// Query laporan penjualan
$sql = "
    SELECT
        penjualan.PenjualanID,
        penjualan.Tanggal_penjualan,
        penjualan.Total_harga,
        pelanggan.NamaPelanggan
    FROM penjualan
    LEFT JOIN pelanggan
        ON penjualan.PelangganID = pelanggan.PelangganID
";

// Filter berdasarkan tanggal
if ($tanggalAwal !== '' && $tanggalAkhir !== '') {
    $sql .= "
        WHERE DATE(penjualan.Tanggal_penjualan)
        BETWEEN ? AND ?
    ";
}

$sql .= " ORDER BY penjualan.PenjualanID DESC";

$query = mysqli_prepare($conn, $sql);

if ($tanggalAwal !== '' && $tanggalAkhir !== '') {
    mysqli_stmt_bind_param($query, "ss", $tanggalAwal, $tanggalAkhir);
}

mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);

// Hitung total transaksi dan pendapatan
$totalPendapatan = 0;
$totalTransaksi = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Penjualan - Aplikasi Kasir</title>

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

            <h3>Riwayat Penjualan</h3>

            <div class="user-info">
                <?= htmlspecialchars($nama) ?>
                <span>Petugas</span>
            </div>

        </header>


        <section class="content">

            <div class="welcome">
                <h2>Riwayat Penjualan</h2>
                <p>Lihat dan kelola laporan transaksi penjualan.</p>
            </div>


            <div class="content-box">

                <h3>Filter Laporan</h3>

                <form action="" method="GET">

                    <div class="form-group">
                        <label>Tanggal Awal</label>

                        <input
                            type="date"
                            name="tanggal_awal"
                            value="<?= htmlspecialchars($tanggalAwal) ?>"
                            required
                        >
                    </div>


                    <div class="form-group">
                        <label>Tanggal Akhir</label>

                        <input
                            type="date"
                            name="tanggal_akhir"
                            value="<?= htmlspecialchars($tanggalAkhir) ?>"
                            required
                        >
                    </div>


                    <button type="submit" class="btn-tambah">
                        Tampilkan Laporan
                    </button>

                    <a
                        href="cetak.php?tanggal_awal=<?= urlencode($tanggalAwal) ?>&tanggal_akhir=<?= urlencode($tanggalAkhir) ?>"
                        class="btn-edit"
                        target="_blank"
                    >
                        Cetak Laporan
                    </a>

                    <a
                        href="export.php?tanggal_awal=<?= urlencode($tanggalAwal) ?>&tanggal_akhir=<?= urlencode($tanggalAkhir) ?>"
                        class="btn-edit"
                    >
                        Export Excel
                    </a>

                    <a href="index.php" class="btn-edit">
                        Reset
                    </a>

                </form>

            </div>


            <div class="content-box">

                <h3>Hasil Laporan Penjualan</h3>

                <div class="table-container">

                    <table class="product-table">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Penjualan</th>
                                <th>Tanggal</th>
                                <th>Nama Pelanggan</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php
                        $no = 1;

                        if ($totalTransaksi > 0) :

                            while ($data = mysqli_fetch_assoc($result)) :

                                $totalPendapatan += (float) $data['Total_harga'];
                        ?>

                            <tr>

                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($data['PenjualanID']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['Tanggal_penjualan']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['NamaPelanggan'] ?? '-') ?>
                                </td>

                                <td>
                                    Rp <?= number_format($data['Total_harga'], 0, ',', '.') ?>
                                </td>

                            </tr>

                        <?php
                            endwhile;

                        else :
                        ?>

                            <tr>
                                <td colspan="5" class="empty-data">
                                    Tidak ada transaksi pada periode ini.
                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>


                        <tfoot>
                            <tr>
                                <th colspan="4">Total Pendapatan</th>

                                <th>
                                    Rp <?= number_format($totalPendapatan, 0, ',', '.') ?>
                                </th>
                            </tr>
                        </tfoot>

                    </table>

                </div>

                <p>
                    Total Transaksi: <?= $totalTransaksi ?>
                </p>

            </div>

        </section>

    </main>

</div>

</body>
</html>
