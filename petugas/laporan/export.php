
<?php

require_once "../../config/koneksi.php";

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

// Filter tanggal
if ($tanggalAwal !== '' && $tanggalAkhir !== '') {
    $sql .= "
        WHERE DATE(penjualan.Tanggal_penjualan)
        BETWEEN ? AND ?
    ";
}

$sql .= " ORDER BY penjualan.PenjualanID DESC";

// Jalankan query
$query = mysqli_prepare($conn, $sql);

if ($tanggalAwal !== '' && $tanggalAkhir !== '') {
    mysqli_stmt_bind_param(
        $query,
        "ss",
        $tanggalAwal,
        $tanggalAkhir
    );
}

mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);

// Nama file CSV
$namaFile = 'laporan_penjualan_' . date('Y-m-d') . '.csv';

// Header download
header('Content-Type: text/csv; charset=UTF-8');

header(
    'Content-Disposition: attachment; filename="' . $namaFile . '"'
);

// Output CSV
$output = fopen('php://output', 'w');

// BOM agar karakter terbaca dengan baik di Excel
fwrite($output, "\xEF\xBB\xBF");

// Header tabel
fputcsv($output, [
    'ID Penjualan',
    'Tanggal Penjualan',
    'Nama Pelanggan',
    'Total Harga'
], ';');

// Hitung total
$totalPendapatan = 0;
$totalTransaksi = 0;

// Ambil data penjualan
while ($data = mysqli_fetch_assoc($result)) {

    $totalPendapatan += (float) $data['Total_harga'];
    $totalTransaksi++;

    fputcsv($output, [
        $data['PenjualanID'],
        $data['Tanggal_penjualan'],
        $data['NamaPelanggan'] ?? '-',
        $data['Total_harga']
    ], ';');

}

// Tambahkan ringkasan laporan
fputcsv($output, []);

fputcsv($output, [
    'Total Transaksi',
    $totalTransaksi
], ';');

fputcsv($output, [
    'Total Pendapatan',
    $totalPendapatan
], ';');

// Tutup output dan query
fclose($output);

mysqli_stmt_close($query);

exit;

?>
