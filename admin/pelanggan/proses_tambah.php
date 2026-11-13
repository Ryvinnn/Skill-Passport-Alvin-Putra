
<?php
require_once '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah.php");
    exit;
}

$nama = trim($_POST['NamaPelanggan']);
$alamat = trim($_POST['Alamat']);
$telepon = trim($_POST['NomorTelepon']);

if ($nama === '' || $alamat === '' || $telepon === '') {
    die("Semua data pelanggan wajib diisi.");
}

$query = mysqli_prepare(
    $conn,
    "INSERT INTO pelanggan (NamaPelanggan, Alamat, `Nomor Telepon`)
     VALUES (?, ?, ?)"
);

mysqli_stmt_bind_param($query, "sss", $nama, $alamat, $telepon);

if (mysqli_stmt_execute($query)) {
    header("Location: index.php");
    exit;
} else {
    die("Gagal menambahkan pelanggan: " . mysqli_error($conn));
}
?>
