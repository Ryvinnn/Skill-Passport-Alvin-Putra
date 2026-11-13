
<?php
require_once '../../config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$query = mysqli_prepare(
    $conn,
    "SELECT * FROM pelanggan WHERE PelangganID = ?"
);

mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$pelanggan = mysqli_fetch_assoc($result);

if (!$pelanggan) {
    die("Data pelanggan tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Pelanggan - Aplikasi Kasir</title>

    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="dashboard-container">

    <aside class="sidebar">
        <h2>APLIKASI KASIR</h2>

        <nav>
            <a href="../dashboard.php">Dashboard</a>
            <a href="../produk/index.php">Data Produk</a>
            <a href="index.php" class="active">Data Pelanggan</a>
            <a href="#">Data Penjualan</a>
            <a href="../../proses/logout.php">Logout</a>
        </nav>
    </aside>

    <main class="main-content">

        <header class="topbar">
            <h2>Edit Pelanggan</h2>
            <p>Ubah informasi data pelanggan.</p>
        </header>

        <section class="product-section">

            <div class="product-header">
                <h3>Form Edit Pelanggan</h3>

                <a href="index.php" class="btn-edit">
                    Kembali
                </a>
            </div>

            <form action="proses_edit.php" method="POST">

                <input type="hidden"
                       name="PelangganID"
                       value="<?= htmlspecialchars($pelanggan['PelangganID']); ?>">

                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text"
                           name="NamaPelanggan"
                           value="<?= htmlspecialchars($pelanggan['NamaPelanggan']); ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="Alamat" required><?= htmlspecialchars($pelanggan['Alamat']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text"
                           name="NomorTelepon"
                           value="<?= htmlspecialchars($pelanggan['Nomor Telepon']); ?>"
                           required>
                </div>

                <button type="submit" class="btn-tambah">
                    Simpan Perubahan
                </button>

            </form>

        </section>

    </main>

</div>

</body>
</html>
