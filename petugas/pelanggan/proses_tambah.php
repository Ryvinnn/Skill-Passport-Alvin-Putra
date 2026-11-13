
<?php

require_once '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah.php");
    exit;
}


// Mengambil data dari form
$nama = trim($_POST['NamaPelanggan'] ?? '');
$alamat = trim($_POST['Alamat'] ?? '');
$telepon = trim($_POST['NomorTelepon'] ?? '');


// Validasi data
if ($nama === '' || $alamat === '' || $telepon === '') {
    die("Semua data pelanggan wajib diisi.");
}


// Query tambah pelanggan
$query = mysqli_prepare(
    $conn,
    "INSERT INTO pelanggan (NamaPelanggan, Alamat, `Nomor Telepon`)
     VALUES (?, ?, ?)"
);

if (!$query) {
    die("Query gagal: " . mysqli_error($conn));
}


// Menghubungkan parameter
mysqli_stmt_bind_param($query, "sss", $nama, $alamat, $telepon);


// Menjalankan query
if (mysqli_stmt_execute($query)) {

    header("Location: index.php");
    exit;

} else {

    die("Gagal menambahkan pelanggan: " . mysqli_stmt_error($query));

}

?>
