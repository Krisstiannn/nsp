<?php
include "/xampp/htdocs/nsp/services/koneksi.php";
session_start();
$query_tampil = "SELECT
                    p.nama_pelanggan,
                    p.alamat_pelanggan,
                    rp.no_wo,
                    rp.status,
                    rp.keterangan,
                    rp.material1,
                    rp.material2,
                    rp.material3,
                    rp.jumlah1,
                    rp.jumlah2,
                    rp.jumlah3
                 FROM report_pemasangan rp
                 LEFT JOIN psb p ON p.id = rp.no_wo";
$result_tampil = $conn->query($query_tampil);

if (isset($_POST{
'cetak'})) {
    include "/xampp/htdocs/nsp/library/fpdf.php";;

    $logoPath = 'netsun.jpg';

    list($logoWidth, $logoHeight) = getimagesize($logoPath);
    $maxLogoHeight = 25;
    $maxLogoWidth = 50;
    $scaleHeight = $maxLogoHeight / $logoHeight;
    $scaleWidth = $maxLogoWidth / $logoWidth;
    $scale = min($scaleHeight, $scaleWidth);
    $newLogoWidth = $logoWidth * $scale;
    $newLogoHeight = $logoHeight * $scale;

    $pdf = new FPDF('L', 'mm', 'A4');
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
    $pdf->Cell(275, 10, 'Laporan Karyawan', 0, 1, 'C');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 10, 'Nama Pelanggan', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Alamat Pelanggan', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Status', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Keterangan', 1, 0, 'C');
    $pdf->Cell(25, 10, 'Material', 1, 0, 'C');
    $pdf->Cell(15, 10, 'Jumlah', 1, 0, 'C');
    $pdf->Cell(25, 10, 'Material', 1, 0, 'C');
    $pdf->Cell(15, 10, 'Jumlah', 1, 0, 'C');
    $pdf->Cell(25, 10, 'Material', 1, 0, 'C');
    $pdf->Cell(15, 10, 'Jumlah', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);
    foreach ($result_tampil as $report) {
        $pdf->Cell(40, 10, $report['nama_pelanggan'], 1, 0, 'C');
        $pdf->Cell(50, 10, $report['alamat_pelanggan'], 1, 0, 'C');
        $pdf->Cell(30, 10, $report['status'], 1, 0, 'C');
        $pdf->Cell(40, 10, $report['keterangan'], 1, 0, 'C');
        $pdf->Cell(25, 10, $report['material1'], 1, 0, 'C');
        $pdf->Cell(15, 10, $report['jumlah1'], 1, 0, 'C');
        $pdf->Cell(25, 10, $report['material2'], 1, 0, 'C');
        $pdf->Cell(15, 10, $report['jumlah2'], 1, 0, 'C');
        $pdf->Cell(25, 10, $report['material3'], 1, 0, 'C');
        $pdf->Cell(15, 10, $report['jumlah3'], 1, 1, 'C');
    }
    $pdf->Ln(15);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(180);
    $pdf->Cell(70, 7, 'Jakarta, ' . date('d-m-Y'), 0, 1, 'C');
    $pdf->Ln(20);

    $pdf->Cell(180);
    $pdf->Cell(70, 7, '______________________', 0, 1, 'C');
    $pdf->Cell(180);
    $pdf->Cell(70, 7, $_SESSION['nama_karyawan'], 0, 1, 'C');

    $pdf->Output();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/nsp/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/nsp/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/nsp/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/nsp/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="/nsp/dist/css/adminlte.min.css">
    <link rel="icon" href="/nsp/storage/nsp.jpg">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php include "/xampp/htdocs/nsp/layouts/header.php" ?>
        <?php include "/xampp/htdocs/nsp/layouts/sidebar.php" ?>

        <!-- Main Content -->
        <div class="content-wrapper bg-gradient-white">

            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Laporan Pekerjaan</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Left col -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header border-transparent">
                                    <div class="card-header">
                                        <form action="" method="POST">
                                            <div class="card-title">
                                                <button name="cetak" class="btn btn-sm btn-success ">Cetak</button>
                                            </div>
                                        </form>

                                        <div class="card-title float-right">
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" name="table_search" class="form-control float-right"
                                                    placeholder="Search">

                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-default">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table id="example1" class="table table-bordered text-center">
                                            <thead class="bg-gradient-cyan">
                                                <tr>
                                                    <th>No Working Order</th>
                                                    <th>Nama Pelanggan</th>
                                                    <th>Alamat atau Titik Kordinat</th>
                                                    <th>Status</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($result_tampil as $report) { ?>
                                                    <tr>
                                                        <td><?= $report['no_wo'] ?></td>
                                                        <td><?= $report['nama_pelanggan'] ?></td>
                                                        <td><?= $report['alamat_pelanggan'] ?></td>
                                                        <td><?= $report['status'] ?></td>
                                                        <td><?= $report['keterangan'] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- END Main Content -->

        <?php include "/xampp/htdocs/nsp/layouts/footer.php" ?>
    </div>

    <script src="/nsp/plugins/jquery/jquery.min.js"></script>
    <script src="/nsp/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/nsp/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/nsp/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/nsp/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/nsp/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/nsp/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/nsp/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/nsp/plugins/jszip/jszip.min.js"></script>
    <script src="/nsp/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/nsp/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/nsp/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/nsp/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/nsp/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="/nsp/dist/js/adminlte.min.js"></script>
    <script src="/nsp/dist/js/demo.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>