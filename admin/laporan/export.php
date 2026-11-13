
<?php
require_once '../../config/koneksi.php';

$tanggalAwal = $_GET['tanggal_awal'] ?? '';
$tanggalAkhir = $_GET['tanggal_akhir'] ?? '';

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

if ($tanggalAwal !== '' && $tanggalAkhir !== '') {
    $sql .= "
        WHERE DATE(penjualan.Tanggal_penjualan)
        BETWEEN ? AND ?
    ";
}

$sql .= " ORDER BY penjualan.PenjualanID DESC";

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

$namaFile = 'laporan_penjualan_' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $namaFile . '"');

$output = fopen('php://output', 'w');

fwrite($output, "\xEF\xBB\xBF");

fputcsv($output, [
    'ID Penjualan',
    'Tanggal Penjualan',
    'Nama Pelanggan',
    'Total Harga'
], ';');

$totalPendapatan = 0;
$totalTransaksi = 0;

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

fputcsv($output, []);
fputcsv($output, [
    'Total Transaksi',
    $totalTransaksi
], ';');

fputcsv($output, [
    'Total Pendapatan',
    $totalPendapatan
], ';');

fclose($output);

mysqli_stmt_close($query);

exit;
