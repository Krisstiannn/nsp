<?php
include "/xampp/htdocs/nsp/services/koneksi.php";
include "/xampp/htdocs/nsp/library/fpdf.php";
session_start();
$keterangan = "";
$query = "SELECT 
        k.nip_karyawan,
        k.nama_karyawan,
        COUNT(*) AS jumlah_pekerjaan
    FROM karyawan k
    LEFT JOIN wo ON k.id = wo.id_karyawan
    LEFT JOIN report_pemasangan rp ON wo.id_psb = rp.id
    LEFT JOIN report_perbaikan rb ON wo.id_perbaikan = rb.no_wo
    WHERE k.posisi_karyawan = 'teknisi'
    GROUP BY k.id";
$jumlah = $conn->query($query)->fetch_assoc();
$result = $conn->query($query);

// if ($jumlah['jumlah_pekerjaan'] >= '20') {
//     $keterangan = "GOOD PERFORM";
// } else if ($jumlah['jumlah_pekerjaan'] == '10') {
//     $keterangan = "STABIL";
// } else {
//     $keterangan = "BAD PERFORM";
// }

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
    $pdf->Cell(190, 10, 'Laporan Performa Karyawan', 0, 1, 'C');
    $pdf->SetFont('Arial', 'I', 12);
    //$pdf->Cell(190, 10, "Bulan: $bulan_tulisan", 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 10, 'NIP Karyawan', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Nama Karyawan', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Total Pekerjaan', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Keterangan', 1, 1, 'C');


    while ($row = $result->fetch_assoc()) {
    $jumlah = $row['jumlah_pekerjaan'];
    
    if ($jumlah >= 20) {
        $keterangan = "GOOD PERFORM";
    } elseif ($jumlah >= 10) {
        $keterangan = "STABIL";
    } else {
        $keterangan = "BAD PERFORM";
    }

    $pdf->SetFont('Arial', '', 10);
    if (isset($_POST['cetak'])) {
        $pdf->Cell(40, 10, $row['nip_karyawan'], 1, 0, 'C');
        $pdf->Cell(60, 10, $row['nama_karyawan'], 1, 0, 'C');
        $pdf->Cell(50, 10, $jumlah, 1, 0, 'C');
        $pdf->Cell(40, 10, $keterangan, 1, 1, 'C');
    }
    // while ($row = $result->fetch_assoc()) {
    //     $pdf->Cell(40, 10, $row['nip_karyawan'], 1, 0, 'C');
    //     $pdf->Cell(60, 10, $row['nama_karyawan'], 1, 0, 'C');
    //     $pdf->Cell(50, 10, $row['jumlah_pekerjaan'], 1, 0, 'C');
    //     $pdf->Cell(40, 10, $keterangan, 1, 1, 'C');
    // }
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
                            <h1>Laporan Performa Teknisi</h1>
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
                                                    <th>NIP Karyawan</th>
                                                    <th>Nama Karyawan</th>
                                                    <th>Total Pekerjaan</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <?php
                                                        $jumlah = $row['jumlah_pekerjaan'];
                                                        if ($jumlah >= 20) {
                                                            $keterangan = "GOOD PERFORM";
                                                        } elseif ($jumlah >= 10) {
                                                            $keterangan = "STABIL";
                                                        } else {
                                                            $keterangan = "BAD PERFORM";
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td><?= $row['nip_karyawan'] ?></td>
                                                        <td><?= $row['nama_karyawan'] ?></td>
                                                        <td><?= $jumlah ?></td>
                                                        <td><?= $keterangan ?></td>
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