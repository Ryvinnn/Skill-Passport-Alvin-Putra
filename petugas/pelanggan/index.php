
<?php

session_start();

require_once '../../config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM pelanggan");

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

    <title>Data Pelanggan - Aplikasi Kasir</title>

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

            <h3>Data Pelanggan</h3>

            <div class="user-info">
                <?= htmlspecialchars($nama) ?>
                <span>Petugas</span>
            </div>

        </header>


        <section class="content">

            <div class="product-section">

                <div class="product-header">

                    <h3>Daftar Pelanggan</h3>

                    <a href="tambah.php" class="btn-tambah">
                        + Tambah Pelanggan
                    </a>

                </div>


                <div class="table-container">

                    <table class="product-table">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Pelanggan</th>
                                <th>Nama Pelanggan</th>
                                <th>Alamat</th>
                                <th>Nomor Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>


                        <tbody>

                        <?php

                        $no = 1;

                        if (mysqli_num_rows($query) > 0) :

                            while ($pelanggan = mysqli_fetch_assoc($query)) :

                        ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td>
                                    <?= htmlspecialchars($pelanggan['PelangganID']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pelanggan['NamaPelanggan']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pelanggan['Alamat']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pelanggan['Nomor Telepon']); ?>
                                </td>

                                <td>

                                    <a href="edit.php?id=<?= $pelanggan['PelangganID']; ?>"
                                       class="btn-edit">
                                        Edit
                                    </a>

                                    <form action="hapus.php"
                                          method="POST"
                                          style="display: inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?');">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $pelanggan['PelangganID']; ?>"
                                        >

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
                                    Belum ada data pelanggan.
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
