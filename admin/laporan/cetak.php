
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
    mysqli_stmt_bind_param($query, "ss", $tanggalAwal, $tanggalAkhir);
}

mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);

$totalPendapatan = 0;
$totalTransaksi = mysqli_num_rows($result);
$no = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Penjualan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: black;
        }

        h2, p {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: lightgray;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        .print-button {
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
        }

        @media print {
            .print-button {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>

<button class="print-button" onclick="window.print()">
    Cetak Laporan
</button>

<h2>LAPORAN PENJUALAN</h2>
<p>APLIKASI KASIR</p>

<p>
    Periode:
    <?= $tanggalAwal !== '' ? htmlspecialchars($tanggalAwal) : 'Semua tanggal'; ?>
    sampai
    <?= $tanggalAkhir !== '' ? htmlspecialchars($tanggalAkhir) : 'Semua tanggal'; ?>
</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID Penjualan</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Total Harga</th>
        </tr>
    </thead>

    <tbody>

    <?php if ($totalTransaksi > 0) : ?>

        <?php while ($data = mysqli_fetch_assoc($result)) :
            $totalPendapatan += (float) $data['Total_harga'];
        ?>

            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($data['PenjualanID']); ?></td>
                <td><?= htmlspecialchars($data['Tanggal_penjualan']); ?></td>
                <td><?= htmlspecialchars($data['NamaPelanggan'] ?? '-'); ?></td>
                <td>Rp <?= number_format($data['Total_harga'], 0, ',', '.'); ?></td>
            </tr>

        <?php endwhile; ?>

    <?php else : ?>

        <tr>
            <td colspan="5" style="text-align: center;">
                Tidak ada transaksi.
            </td>
        </tr>

    <?php endif; ?>

    </tbody>

    <tfoot>
        <tr>
            <td colspan="4" class="total">Total Transaksi</td>
            <td><?= $totalTransaksi; ?></td>
        </tr>

        <tr>
            <td colspan="4" class="total">Total Pendapatan</td>
            <td>Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?></td>
        </tr>
    </tfoot>
</table>

<script>
    window.addEventListener('load', function() {
        window.print();
    });
</script>

</body>
</html>

<?php
mysqli_stmt_close($query);
?>
