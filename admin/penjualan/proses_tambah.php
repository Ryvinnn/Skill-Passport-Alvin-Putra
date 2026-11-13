
<?php
require_once '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$pelangganID = (int) $_POST['PelangganID'];
$produkID = (int) $_POST['Produk_id'];
$jumlah = (int) $_POST['JumlahProduk'];

if ($pelangganID <= 0 || $produkID <= 0 || $jumlah <= 0) {
    die("Data transaksi tidak valid.");
}

mysqli_begin_transaction($conn);

try {

    $cekPelanggan = mysqli_prepare(
        $conn,
        "SELECT PelangganID FROM pelanggan WHERE PelangganID = ?"
    );

    mysqli_stmt_bind_param($cekPelanggan, "i", $pelangganID);
    mysqli_stmt_execute($cekPelanggan);

    $hasilPelanggan = mysqli_stmt_get_result($cekPelanggan);

    if (mysqli_num_rows($hasilPelanggan) === 0) {
        throw new Exception("Pelanggan tidak ditemukan.");
    }

    $cekProduk = mysqli_prepare(
        $conn,
        "SELECT Produk_id, Harga, Stok
         FROM produk
         WHERE Produk_id = ?
         FOR UPDATE"
    );

    mysqli_stmt_bind_param($cekProduk, "i", $produkID);
    mysqli_stmt_execute($cekProduk);

    $hasilProduk = mysqli_stmt_get_result($cekProduk);
    $dataProduk = mysqli_fetch_assoc($hasilProduk);

    if (!$dataProduk) {
        throw new Exception("Produk tidak ditemukan.");
    }

    if ($dataProduk['Stok'] < $jumlah) {
        throw new Exception("Stok produk tidak mencukupi.");
    }

    $harga = (float) $dataProduk['Harga'];
    $subtotal = $harga * $jumlah;
    $totalHarga = $subtotal;

    $simpanPenjualan = mysqli_prepare(
        $conn,
        "INSERT INTO penjualan
         (Tanggal_penjualan, Total_harga, PelangganID)
         VALUES (CURDATE(), ?, ?)"
    );

    mysqli_stmt_bind_param(
        $simpanPenjualan,
        "di",
        $totalHarga,
        $pelangganID
    );

    if (!mysqli_stmt_execute($simpanPenjualan)) {
        throw new Exception("Gagal menyimpan transaksi.");
    }

    $penjualanID = mysqli_insert_id($conn);

    $simpanDetail = mysqli_prepare(
        $conn,
        "INSERT INTO detail_penjualan
         (Penjualan_id, Produk_id, Jumlah_produk, Subtotal)
         VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $simpanDetail,
        "iiid",
        $penjualanID,
        $produkID,
        $jumlah,
        $subtotal
    );

    if (!mysqli_stmt_execute($simpanDetail)) {
        throw new Exception("Gagal menyimpan detail transaksi.");
    }

    $updateStok = mysqli_prepare(
        $conn,
        "UPDATE produk
         SET Stok = Stok - ?
         WHERE Produk_id = ?"
    );

    mysqli_stmt_bind_param(
        $updateStok,
        "ii",
        $jumlah,
        $produkID
    );

    if (!mysqli_stmt_execute($updateStok)) {
        throw new Exception("Gagal memperbarui stok produk.");
    }

    mysqli_commit($conn);

    header("Location: index.php?status=berhasil");
    exit;

} catch (Throwable $e) {

    mysqli_rollback($conn);

    die("Transaksi gagal: " . $e->getMessage());
}
?>
