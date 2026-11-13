
<?php

require_once "../../config/koneksi.php";

// Pastikan request menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// Validasi ID produk
if (!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
    header("Location: index.php?status=id_invalid");
    exit;
}

$id = (int) $_POST['id'];

try {

    // Hapus data produk berdasarkan Produk_id
    $stmt = $conn->prepare(
        "DELETE FROM produk WHERE Produk_id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Periksa hasil penghapusan
    if ($stmt->affected_rows > 0) {

        $stmt->close();

        header("Location: index.php?status=hapus_sukses");
        exit;

    }

    $stmt->close();

    header("Location: index.php?status=tidak_ditemukan");
    exit;

} catch (mysqli_sql_exception $e) {

    header("Location: index.php?status=gagal_hapus");
    exit;

}

?>
