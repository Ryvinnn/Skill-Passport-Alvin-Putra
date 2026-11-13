
<?php
require_once '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id = (int) $_POST['PelangganID'];
$nama = trim($_POST['NamaPelanggan']);
$alamat = trim($_POST['Alamat']);
$telepon = trim($_POST['NomorTelepon']);

if ($id <= 0 || $nama === '' || $alamat === '' || $telepon === '') {
    die("Data pelanggan tidak valid atau belum lengkap.");
}

$query = mysqli_prepare(
    $conn,
    "UPDATE pelanggan
     SET NamaPelanggan = ?, Alamat = ?, `Nomor Telepon` = ?
     WHERE PelangganID = ?"
);

mysqli_stmt_bind_param($query, "sssi", $nama, $alamat, $telepon, $id);

if (mysqli_stmt_execute($query)) {
    header("Location: index.php");
    exit;
} else {
    die("Gagal mengubah data pelanggan: " . mysqli_stmt_error($query));
}
?>
