<?php
include "/xampp/htdocs/nsp/services/koneksi.php";
include "/xampp/htdocs/nsp/library/fpdf.php";
session_start();

$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : date('Y-m');
$bulan_angka = date('m', strtotime($bulan . "-01"));
$tahun = date('Y', strtotime($bulan . "-01"));

$upah_per_jam = 20000; // SET UPAH

$nama_bulan = [
    '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
    '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
    '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
];

$bulan_tulisan = $nama_bulan[$bulan_angka] . " " . $tahun;

/*
LOGIC LEMBUR:
- Jam kerja normal: 09:00 - 17:00
- Lebih dari 17:00 = lembur
*/
$query = "
SELECT 
    k.nip_karyawan,
    k.nama_karyawan,
    k.posisi_karyawan,

    COUNT(CASE 
        WHEN TIME_TO_SEC(a.jam_keluar) > TIME_TO_SEC('17:00:00') 
        THEN 1 END) AS jumlah_hari_lembur,

    SUM(
        CASE 
            WHEN TIME_TO_SEC(a.jam_keluar) > TIME_TO_SEC('17:00:00') 
            THEN (TIME_TO_SEC(a.jam_keluar) - TIME_TO_SEC('17:00:00')) / 3600
            ELSE 0 
        END
    ) AS total_jam_lembur

FROM karyawan k
JOIN absen a ON k.nip_karyawan = a.nip_karyawan
WHERE DATE_FORMAT(a.tanggal, '%Y-%m') = '$bulan'
GROUP BY k.nip_karyawan
";

$result = $conn->query($query);

if (isset($_POST['cetak'])) {

    $logoPath = 'netsun.jpg';
    list($logoWidth, $logoHeight) = getimagesize($logoPath);

    $scale = min(50/$logoWidth, 25/$logoHeight);
    $newLogoWidth = $logoWidth * $scale;
    $newLogoHeight = $logoHeight * $scale;

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();

    // LOGO
    $pdf->Image($logoPath, 10, 10, $newLogoWidth, $newLogoHeight);

    // HEADER
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(60);
    $pdf->Cell(0, 7, 'PT. Net Sun Power (NSP)', 0, 1);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(60);
    $pdf->Cell(0, 7, 'Telp: 085654807560', 0, 1);

    $pdf->Cell(60);
    $pdf->Cell(0, 7, 'Jl. Handil Bakti, Banjarmasin', 0, 1);

    $pdf->Ln(5);
    $pdf->Cell(190, 0, '', 'B', 1);
    $pdf->Ln(5);

    // JUDUL
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(190, 10, 'Laporan Rekapitulasi Lembur', 0, 1, 'C');

    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(190, 8, "Bulan: $bulan_tulisan", 0, 1, 'C');
    $pdf->Ln(5);

    // TABLE HEADER
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(10, 8, 'No', 1);
    $pdf->Cell(25, 8, 'NIP', 1);
    $pdf->Cell(35, 8, 'Nama', 1);
    $pdf->Cell(30, 8, 'Jabatan', 1);
    $pdf->Cell(20, 8, 'Hari', 1);
    $pdf->Cell(25, 8, 'Total Jam', 1);
    $pdf->Cell(20, 8, 'Rata2', 1);
    $pdf->Cell(25, 8, 'Upah', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 9);
    $no = 1;

    while ($row = $result->fetch_assoc()) {

        $rata = $row['jumlah_hari_lembur'] > 0 
            ? $row['total_jam_lembur'] / $row['jumlah_hari_lembur'] 
            : 0;

        $upah = $row['total_jam_lembur'] * $upah_per_jam;

        $pdf->Cell(10, 8, $no++, 1);
        $pdf->Cell(25, 8, $row['nip_karyawan'], 1);
        $pdf->Cell(35, 8, $row['nama_karyawan'], 1);
        $pdf->Cell(30, 8, $row['posisi_karyawan'], 1);
        $pdf->Cell(20, 8, $row['jumlah_hari_lembur'], 1);
        $pdf->Cell(25, 8, round($row['total_jam_lembur'],2), 1);
        $pdf->Cell(20, 8, round($rata,2), 1);
        $pdf->Cell(25, 8, number_format($upah,0,',','.'), 1);
        $pdf->Ln();
    }

    $pdf->Output();
    exit;
}
?>

<!-- ================= HTML ================= -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Laporan Lembur</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="/nsp/plugins/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="/nsp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <link rel="stylesheet" href="/nsp/dist/css/adminlte.min.css">
        <link rel="icon" href="/nsp/storage/nsp.jpg">

</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php include "/xampp/htdocs/nsp/layouts/header.php" ?>
<?php include "/xampp/htdocs/nsp/layouts/sidebar.php" ?>

<div class="content-wrapper">

<section class="content-header">
<div class="container-fluid">
<h1>Laporan Lembur Karyawan</h1>
</div>
</section>

<section class="content">
<div class="card">

<div class="card-header">
<form method="POST" class="form-inline">
<input type="month" name="bulan" value="<?= $bulan ?>" class="form-control mr-2">
<button type="submit" name="tampil" class="btn btn-primary btn-sm mr-2">Tampilkan</button>
<button type="submit" name="cetak" class="btn btn-success btn-sm">Cetak</button>
</form>
</div>

<div class="card-body table-responsive">
<table class="table table-bordered text-center">
<thead class="bg-gradient-cyan">
<tr>
<th>No</th>
<th>NIP</th>
<th>Nama</th>
<th>Jabatan</th>
<th>Hari Lembur</th>
<th>Total Jam</th>
<th>Rata-rata</th>
<th>Upah</th>
</tr>
</thead>

<tbody>
<?php 
$no = 1;
$result = $conn->query($query);

while ($row = $result->fetch_assoc()):
$rata = $row['jumlah_hari_lembur'] > 0 
    ? $row['total_jam_lembur'] / $row['jumlah_hari_lembur'] 
    : 0;

$upah = $row['total_jam_lembur'] * $upah_per_jam;
?>

<tr>
<td><?= $no++ ?></td>
<td><?= $row['nip_karyawan'] ?></td>
<td><?= $row['nama_karyawan'] ?></td>
<td><?= $row['posisi_karyawan'] ?></td>
<td><?= $row['jumlah_hari_lembur'] ?></td>
<td><?= round($row['total_jam_lembur'],2) ?> Jam</td>
<td><?= round($rata,2) ?> Jam</td>
<td>Rp <?= number_format($upah,0,',','.') ?></td>
</tr>

<?php endwhile; ?>
</tbody>

</table>
</div>

</div>
</section>

</div>

<?php include "/xampp/htdocs/nsp/layouts/footer.php" ?>
</div>

 <script src="/nsp/plugins/jquery/jquery.min.js"></script>
    <script src="/nsp/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/nsp/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="/nsp/dist/js/adminlte.js"></script>
    <script src="/nsp/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
    <script src="/nsp/plugins/raphael/raphael.min.js"></script>
    <script src="/nsp/plugins/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="/nsp/plugins/jquery-mapael/maps/usa_states.min.js"></script>
    <script src="/nsp/plugins/chart.js/Chart.min.js"></script>
    <script src="/nsp/dist/js/demo.js"></script>
    <script src="/nsp/dist/js/pages/dashboard2.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>