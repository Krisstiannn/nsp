<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";
$id_karyawan = $_SESSION['id_karyawan'] ?? null;
$query_jumlah = "SELECT COUNT(*) AS total_pekerjaan FROM wo WHERE id_karyawan = '$id_karyawan'";
$result_tampilJumlah = $conn->query($query_jumlah)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/nsp/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/nsp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="/nsp/dist/css/adminlte.min.css">
    <link rel="icon" href="/nsp/storage/netsun.jpg">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include "/xampp/htdocs/nsp/layouts/header.php" ?>
        <!-- Navbar -->

        <!-- Main Sidebar Container -->
        <?php include "/xampp/htdocs/nsp/layouts/sidebar-user.php" ?>
        <!-- END Main Sidebar -->

        <!-- Main Content -->
        <div class="content-wrapper bg-gradient-white">
            <div class="content-header">
                <div class="container-fluid text-black">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Selamat Datang <?= $_SESSION['nama_karyawan'] ?> , Anda Login Sebagai
                                <?= $_SESSION['peran'] ?></h1>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <i class="m-2" style="font-size: 20px;">Silahkan Absen Terlebih Dahulu</i>
                        </div>
                        <form action="absen.php" method="POST">
                            <button type="submit" class="btn btn-sm btn-info text-bold" name="btn_absen">Absen
                                Masuk</button>

                            <button type="submit" class="btn btn-sm btn-info text-bold" name="absen_keluar">Absen
                                Keluar</button>
                        </form>

                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <a href="wo_pemasangan.php">
                                <div class="info-box bg-gradient-cyan shadow-lg text-lg-center">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-red text-bold" style="font-size: 20px">Working
                                            Order Pemasangan Baru</span>
                                        <span
                                            style="font-size: 30px"><?= $result_tampilJumlah['total_pekerjaan'] ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="wo_perbaikan.php">
                                <div class="info-box bg-gradient-cyan shadow-lg text-lg-center">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-red text-bold" style="font-size: 20px">Working
                                            Order Perbaikan</span>
                                        <span
                                            style="font-size: 30px"><?= $result_tampilJumlah['total_pekerjaan'] ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <img src="/nsp/storage/netsun.jpg" alt="No pict" width="1500px">
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