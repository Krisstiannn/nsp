<?php
include "/xampp/htdocs/nsp/services/koneksi.php";
include "/xampp/htdocs/nsp/library/fpdf.php";
session_start();

$start_date = date('Y-m-d', strtotime($_POST['start_date'] ?? date('d-m-Y')));
$end_date = date('Y-m-d', strtotime($_POST['end_date'] ?? date('d-m-Y')));

$query = "
    SELECT kode_barang, nama_barang, serial_number, tanggal_masuk, kondisi_barang,
    TIMESTAMPDIFF(YEAR, tanggal_masuk, CURDATE()) AS usia_tahun
    FROM inventaris
    WHERE tanggal_masuk BETWEEN '$start_date' AND '$end_date'
    ORDER BY tanggal_masuk ASC
";

$result = $conn->query($query);

if (isset($_POST['cetak'])) {
    $logoPath = 'netsun.jpg';
    list($logoWidth, $logoHeight) = getimagesize($logoPath);

    $maxLogoHeight = 25;
    $maxLogoWidth = 50;
    $scale = min($maxLogoHeight / $logoHeight, $maxLogoWidth / $logoWidth);
    $newLogoWidth = $logoWidth * $scale;
    $newLogoHeight = $logoHeight * $scale;

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->Image($logoPath, 10, 10, $newLogoWidth, $newLogoHeight);

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(60);
    $pdf->Cell(0, 7, 'PT. Net Sun Power (NSP)', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(60);
    $pdf->Cell(0, 7, 'Telp: 085654807560', 0, 1, 'L');
    $pdf->Cell(60);
    $pdf->Cell(0, 7, 'Jl. Handil Bakti, Semangat Dalam Komp Mitra Bakti Jalur 1 Blok D no 24', 0, 1, 'L');
    $pdf->Ln(5);
    $pdf->Cell(275, 0, '', 'B', 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(190, 10, 'Laporan Evaluasi Inventaris Barang', 0, 1, 'C');
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(190, 10, "Periode: $start_date - $end_date", 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 10, 'Kode Barang', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Serial Number', 1, 0, 'C');
    $pdf->Cell(35, 10, 'Nama Barang', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Tgl Masuk', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Kondisi', 1, 0, 'C');
    $pdf->Cell(35, 10, 'Perlu Diganti?', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);
    foreach ($result as $row) {
        $perlu_diganti = ($row['kondisi_barang'] != 'Baik' || $row['usia_tahun'] > 3) ? 'YA' : 'TIDAK';

        $pdf->Cell(30, 10, $row['kode_barang'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['serial_number'], 1, 0, 'C');
        $pdf->Cell(35, 10, $row['nama_barang'], 1, 0, 'C');
        $pdf->Cell(30, 10, date('d-m-Y', strtotime($row['tanggal_masuk'])), 1, 0, 'C');
        $pdf->Cell(30, 10, $row['kondisi_barang'], 1, 0, 'C');
        $pdf->Cell(35, 10, $perlu_diganti, 1, 1, 'C');
    }

    $pdf->Ln(15);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(120);
    $pdf->Cell(70, 7, 'Banjarmasin, ' . date('d-m-Y'), 0, 1, 'C');
    $pdf->Ln(20);
    $pdf->Cell(120);
    $pdf->Cell(70, 7, '______________________', 0, 1, 'C');
    $pdf->Cell(120);
    $pdf->Cell(70, 7, $_SESSION['nama_karyawan'] ?? 'Petugas', 0, 1, 'C');
    $pdf->Output();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gudang | Inventaris</title>
    <<link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="/nsp/plugins/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="/nsp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <link rel="stylesheet" href="/nsp/dist/css/adminlte.min.css">
        <link rel="icon" href="/nsp/storage/nsp.jpg">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <?php include "/xampp/htdocs/nsp/layouts/header.php" ?>

        <?php include "/xampp/htdocs/nsp/layouts/sidebar.php" ?>

        <div class="content-wrapper bg-gradient-white">

            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Laporan Evaluasi Inventaris Barang</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header border-transparent">
                                    <div class="card-header">
                                        <form action="" method="POST">
                                            <div class="card-title">

                                                <label for="start_date">Dari Tanggal:</label>
                                                <input type="date" name="start_date" value="<?= $start_date ?>"
                                                    required>

                                                <label for="end_date">Sampai Tanggal:</label>
                                                <input type="date" name="end_date" value="<?= $end_date ?>" required>

                                                <button type="submit" class="btn btn-sm btn-warning"
                                                    name="filter">Tampilkan</button>
                                                <button name="cetak" class="btn btn-sm btn-success">Cetak</button>
                                            </div>
                                        </form>

                                        <div class="card-title float-right">
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" name="table_search" class="form-control float-right"
                                                    placeholder="Search">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead class="bg-gradient-cyan">
                                                <tr>
                                                    <th>Kode Barang</th>
                                                    <th>Serial Number</th>
                                                    <th>Nama Barang</th>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Kondisi</th>
                                                    <th>Perlu Diganti?</th>
                                                </tr>
                                            </thead>

                                            <?php foreach ($result as $laporan) {
                                                $perlu_diganti = ($laporan['kondisi_barang'] != 'Baik' || $laporan['usia_tahun'] > 3) ? 'YA' : 'TIDAK';
                                            ?>
                                            <tbody>
                                                <tr>
                                                    <td><?= $laporan['kode_barang'] ?></td>
                                                    <td><?= $laporan['serial_number'] ?></td>
                                                    <td><?= $laporan['nama_barang'] ?></td>
                                                    <td><?= date('d-m-Y', strtotime($laporan['tanggal_masuk'])) ?></td>
                                                    <td><?= $laporan['kondisi_barang'] ?></td>
                                                    <td><?= $perlu_diganti ?></td>
                                                </tr>
                                            </tbody>
                                            <?php } ?>

                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
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