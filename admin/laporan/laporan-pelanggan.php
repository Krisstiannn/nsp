<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";

$tahun = $_GET['tahun'] ?? date('Y');

$bulanIndo = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$data_bulanan = array_fill(1, 12, 0);

$query = "SELECT MONTH(tanggal_aktif) AS bulan, COUNT(*) AS jumlah 
          FROM pelanggan 
          WHERE YEAR(tanggal_aktif) = ? AND status_pelanggan = 'AKTIF'
          GROUP BY MONTH(tanggal_aktif)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $tahun);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $data_bulanan[(int)$row['bulan']] = (int)$row['jumlah'];
}

$totalPelanggan = array_sum($data_bulanan);

if (isset($_POST['cetak'])) {
    include "/xampp/htdocs/nsp/library/fpdf.php";

    $pdf = new FPDF('P', 'mm', 'A4'); // Format Portrait
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();

    $logoPath = 'netsun.jpg';
    if (file_exists($logoPath)) {
        list($logoWidth, $logoHeight) = getimagesize($logoPath);
        $scale = min(25 / $logoHeight, 50 / $logoWidth);
        $pdf->Image($logoPath, 10, 10, $logoWidth * $scale, $logoHeight * $scale);
    }

    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell(60);
    $pdf->Cell(0, 6, 'PT. Net Sun Power (NSP)', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(60);
    $pdf->Cell(0, 6, 'Telp: 085654807560', 0, 1, 'L');
    $pdf->Cell(60);
    $pdf->Cell(0, 6, 'Jl. Handil Bakti, Komp. Mitra Bakti Jalur 1 Blok D No. 24', 0, 1, 'L');
    $pdf->Ln(3);
    $pdf->Cell(190, 0, '', 'B', 1, 'C');
    $pdf->Ln(4);

    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell(190, 8, "LAPORAN JUMLAH PELANGGAN TERPASANG", 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(190, 6, "Tahun: $tahun", 0, 1, 'C');
    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(10, 8, 'No', 1, 0, 'C');
    $pdf->Cell(100, 8, 'Bulan', 1, 0, 'C');
    $pdf->Cell(80, 8, 'Jumlah Pelanggan', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);
    $no = 1;
    foreach ($bulanIndo as $bulan => $nama) {
        $jumlah = $data_bulanan[$bulan];
        $pdf->Cell(10, 8, $no++, 1, 0, 'C');
        $pdf->Cell(100, 8, $nama, 1, 0);
        $pdf->Cell(80, 8, $jumlah, 1, 1, 'C');
    }

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(110, 8, 'Total Pelanggan', 1, 0, 'C');
    $pdf->Cell(80, 8, $totalPelanggan, 1, 1, 'C');

    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(120);
    $pdf->Cell(70, 5, 'Jakarta, ' . date('d-m-Y'), 0, 1, 'C');
    $pdf->Ln(15);
    $pdf->Cell(120);
    $pdf->Cell(70, 5, '______________________', 0, 1, 'C');
    $pdf->Cell(120);
    $pdf->Cell(70, 5, $_SESSION['nama_karyawan'] ?? 'Administrator', 0, 1, 'C');

    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 6, 'Dicetak oleh sistem NSP', 0, 1, 'L');

    $pdf->Output();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Pelanggan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/nsp/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/nsp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="/nsp/dist/css/adminlte.min.css">
    <link rel="icon" href="/nsp/storage/nsp.jpg">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php include "/xampp/htdocs/nsp/layouts/header.php" ?>
        <?php include "/xampp/htdocs/nsp/layouts/sidebar.php" ?>

        <div class="content-wrapper bg-white">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Laporan Pelanggan Terpasang</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            <label for="tahun" class="mr-2">Tahun:</label>
                            <form method="GET" class="form-inline d-inline-block">
                                <select name="tahun" id="tahun" class="form-control form-control-sm">
                                    <?php
                                $tahun_sekarang = date('Y');
                                for ($i = $tahun_sekarang; $i >= ($tahun_sekarang - 5); $i--) {
                                    $selected = ($tahun == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary ml-2">Tampilkan</button>
                            </form>

                            <form action="" method="post" target="_blank"
                                class="d-inline-block">
                                <input type="hidden" name="tahun" value="<?= htmlspecialchars($tahun) ?>">
                                <button type="submit" name="cetak" class="btn btn-danger btn-sm"><i
                                        class="fas fa-file-pdf"></i> Cetak PDF</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Jumlah Pelanggan per Bulan - Tahun
                                <b><?= htmlspecialchars($tahun) ?></b></h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center mb-0">
                                    <thead class="bg-gradient-cyan">
                                        <tr>
                                            <th width="10%">No</th>
                                            <th>Bulan</th>
                                            <th>Jumlah Pelanggan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    $no = 1;
                                    foreach ($bulanIndo as $index => $namaBulan) {
                                        $jumlah = $data_bulanan[$index];
                                        echo "<tr>
                                                <td>{$no}</td>
                                                <td>{$namaBulan}</td>
                                                <td>{$jumlah}</td>
                                            </tr>";
                                        $no++;
                                    }
                                    ?>
                                        <tr class="bg-light">
                                            <th colspan="2">Total Pelanggan Tahun <?= htmlspecialchars($tahun) ?></th>
                                            <th><?= $totalPelanggan ?></th>
                                        </tr>
                                    </tbody>
                                </table>
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