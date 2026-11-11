<?php
require_once '../../config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $namaProduk = trim($_POST['NamaProduk']);
    $harga = $_POST['Harga'];
    $stok = $_POST['Stok'];

    if ($namaProduk === '' || $harga === '' || $stok === '') {
        $error = 'Semua kolom wajib diisi.';
    } elseif (!is_numeric($harga) || $harga < 0) {
        $error = 'Harga harus berupa angka dan tidak boleh negatif.';
    } elseif (filter_var($stok, FILTER_VALIDATE_INT) === false || $stok < 0) {
        $error = 'Stok harus berupa bilangan bulat dan tidak boleh negatif.';
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO produk (Nama_produk, Harga, Stok)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param("sdi", $namaProduk, $harga, $stok);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: index.php?status=tambah_sukses");
            exit;
        } else {
            $error = 'Gagal menyimpan produk: ' . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Aplikasi Kasir</title>

    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>

    <div class="dashboard-container">

        <aside class="sidebar">
            <h2>APLIKASI KASIR</h2>

            <nav>
                <a href="../dashboard.php">Dashboard</a>
                <a href="index.php" class="active">Data Produk</a>
                <a href="#">Data Pelanggan</a>
                <a href="#">Data Penjualan</a>
                <a href="../../proses/logout.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">

            <header class="topbar">
                <h2>Tambah Produk</h2>
                <p>Masukkan informasi produk baru.</p>
            </header>

            <section class="form-section">

                <h3>Form Tambah Produk</h3>

                <?php if ($error !== '') : ?>
                    <div class="error-message">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">

                    <div class="form-group">
                        <label for="NamaProduk">Nama Produk</label>
                        <input
                            type="text"
                            id="NamaProduk"
                            name="NamaProduk"
                            placeholder="Masukkan nama produk"
                            value="<?= htmlspecialchars($_POST['NamaProduk'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="Harga">Harga Produk (Rp)</label>
                        <input
                            type="number"
                            id="Harga"
                            name="Harga"
                            placeholder="Contoh: 15000"
                            min="0"
                            step="0.01"
                            value="<?= htmlspecialchars($_POST['Harga'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="Stok">Stok Produk</label>
                        <input
                            type="number"
                            id="Stok"
                            name="Stok"
                            placeholder="Masukkan jumlah stok"
                            min="0"
                            step="1"
                            value="<?= htmlspecialchars($_POST['Stok'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="form-action">
                        <button type="submit" class="btn-simpan">
                            Simpan Produk
                        </button>

                        <a href="index.php" class="btn-batal">
                            Batal
                        </a>
                    </div>

                </form>

            </section>

        </main>

    </div>

</body>

</html>