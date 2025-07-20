<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";

// Validasi akses
// if (!isset($_SESSION['id_user']) || $_SESSION['peran'] !== 'pelanggan') {
//     echo "<script>alert('Akses ditolak!'); location.href='/nsp/login.php';</script>";
//     exit;
// }

$id_user = $_SESSION['id_users'];
$nama_pelanggan = $_SESSION['nama_pelanggan'];

$queryPelanggan = "SELECT * FROM psb WHERE id_user = '$id_user' ORDER BY id DESC LIMIT 1";
$resultPelanggan = $conn->query($queryPelanggan);
if ($resultPelanggan->num_rows === 0) {
    $id_langganan = $paket = $teknisi = $status = '-';
} else {
    $dataPelanggan = $resultPelanggan->fetch_assoc();
    $id_langganan = $dataPelanggan['id_langganan'];
    $paket = $dataPelanggan['paket_internet'];

    $id_pelanggan = $dataPelanggan['id'];
    $queryWO = "SELECT * FROM wo WHERE id_psb = '$id_pelanggan'";
    $resultWO = $conn->query($queryWO);

    if ($resultWO->num_rows > 0) {
        $dataWO = $resultWO->fetch_assoc();
        $id_teknisi = $dataWO['id_karyawan'];

        $queryTeknisi = "SELECT nama_karyawan FROM karyawan WHERE id = '$id_teknisi'";
        $resultTeknisi = $conn->query($queryTeknisi);
        $teknisi = $resultTeknisi->num_rows > 0 ? $resultTeknisi->fetch_assoc()['nama_karyawan'] : '-';
    } else {
        $teknisi = '-';
    }

    $queryReport = "SELECT status FROM report_pemasangan WHERE id_langganan = '$id_langganan'";
    $resultReport = $conn->query($queryReport);
    $status = $resultReport->num_rows > 0 ? $resultReport->fetch_assoc()['status'] : 'Tiket Sudah Diberikan Ke Teknisi';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/nsp/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/nsp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="/nsp/dist/css/adminlte.min.css">
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php include "/xampp/htdocs/nsp/layouts/navbar.php" ?>
        <div class="content-wrapper">
            <div class="content">
                <div class="container">
                    <div class="content-header">
                        <div class="container-fluid text-black">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <h1 class="m-0">Selamat Datang <?= htmlspecialchars($nama_pelanggan) ?> di Website
                                        Resmi Net Sun Power</h1>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Status Pemasangan -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title">Status Pekerjaan Pemasangan Baru</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>ID Langganan</b> <span class="float-right"><?= $id_langganan ?></span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Nama Pelanggan</b> <span
                                                class="float-right"><?= $nama_pelanggan ?></span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Paket Internet</b> <span class="float-right"><?= $paket ?></span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Teknisi Yang Mengerjakan</b> <span class="float-right"><?= $teknisi ?></span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Status Pemasangan</b> <span class="float-right"><?= $status ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-light p-3">
            <div class="text-center">
                <h5 class="font-weight-bold text-primary">For more information, please contact here</h5>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <i class="fas fa-phone-alt text-danger mr-2"></i>
                <span class="mr-1">WhatsApp:</span>
                <a href="https://wa.me/6281234567890" class="text-primary">0812-3456-7890</a>
            </div>
        </div>


        <footer class="main-footer bg-blue" style="text-align: center;">
            <strong>Copyright &copy; 2025 Net Sun Power.</strong> All rights
            reserved.
        </footer>
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