<?php
include "/xampp/htdocs/nsp/services/koneksi.php";
include "/xampp/htdocs/nsp/library/fpdf.php";
session_start();

$sql = "
    SELECT m.nama_barang, m.jumlah_awal, 
            COALESCE(r.total_digunakan, 0) AS jumlah_digunakan, 
            (m.jumlah_awal - COALESCE(r.total_digunakan, 0)) AS jumlah_sisa
    FROM material m
    LEFT JOIN (
        SELECT barang, SUM(jumlah) AS total_digunakan 
        FROM (
            SELECT material1 AS barang, jumlah1 AS jumlah FROM report_pemasangan
            UNION ALL
            SELECT material2, jumlah2 FROM report_pemasangan
            UNION ALL
            SELECT material3, jumlah3 FROM report_pemasangan
        ) AS combined_report
        WHERE barang IS NOT NULL
        GROUP BY barang
    ) r ON m.nama_barang = r.barang
";

$result = $conn->query($sql);

if (isset($_POST['cetak'])) {

    $logoPath = 'netsun.jpg';
    list($logoWidth, $logoHeight) = getimagesize($logoPath);

    $maxLogoHeight = 25;
    $maxLogoWidth = 50;
    $scaleHeight = $maxLogoHeight / $logoHeight;
    $scaleWidth = $maxLogoWidth / $logoWidth;
    $scale = min($scaleHeight, $scaleWidth);
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
    $pdf->Cell(190, 10, 'Laporan Penggunaan Barang', 0, 1, 'C');
    $pdf->SetFont('Arial', 'I', 12);
    // $pdf->Cell(190, 10, "Bulan: $bulan_tulisan", 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(60, 10, 'Nama Barang', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Jumlah Stok Barang', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Jumlah Yang Digunakan', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Jumlah Sisa', 1, 1, 'C');



    $pdf->SetFont('Arial', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(60, 10, $row['nama_barang'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['jumlah_awal'], 1, 0, 'C');
        $pdf->Cell(50, 10, $row['jumlah_digunakan'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['jumlah_sisa'], 1, 1, 'C');
    }


    $pdf->Ln(15);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(120);
    $pdf->Cell(70, 7, 'Banjarmasin, ' . date('d-m-Y'), 0, 1, 'C');
    $pdf->Ln(20);

    $pdf->Cell(120);
    $pdf->Cell(70, 7, '______________________', 0, 1, 'C');
    $pdf->Cell(120);
    $pdf->Cell(70, 7, $_SESSION['nama_karyawan'], 0, 1, 'C');
    $pdf->Cell(120);


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
                            <h1>Laporan Penggunaan Material</h1>
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
                                            <button name="cetak" class="btn btn-sm btn-success ">Cetak</button>
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
                                                    <th>Nama Barang</th>
                                                    <th>Jumlah Stok Barang</th>
                                                    <th>Jumlah Yang Digunakan</th>
                                                    <th>Jumlah Sisa</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= $row['nama_barang'] ?></td>
                                                        <td><?= $row['jumlah_awal'] ?></td>
                                                        <td><?= $row['jumlah_digunakan'] ?></td>
                                                        <td><?= $row['jumlah_sisa'] ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
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