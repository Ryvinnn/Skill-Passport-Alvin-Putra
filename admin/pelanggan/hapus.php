<?php
require_once '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if (!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
    header("Location: index.php?status=id_invalid");
    exit;
}

$id = (int) $_POST['id'];

try {

    $stmt = $conn->prepare(
        "DELETE FROM produk WHERE Produk_id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

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

ubah code ini dengan acuan diatas