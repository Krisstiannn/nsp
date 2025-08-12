<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";
$nama = $_SESSION['nama_pelanggan'] ?? null;
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
                                    <h1 class="m-0">Selamat Datang <?= $nama?> di Website Resmi Net Sun Power</h1>
                                </div>
                            </div>

                            <?php if (!empty($_SESSION['notif_tagihan'])): ?>
                            <div class="alert alert-warning">
                                <?= htmlspecialchars($_SESSION['notif_tagihan']) ?>
                            </div>
                            <?php endif; ?>

                        </div>
                        <!-- <div class="container-fluid ">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <h1 class="m-0">PT Net Sun Power adalah perusahaan yang bergerak dibidang penyedia layanan internet atau Internet
                                                    Service Provider (ISP), perusahaan kami hanya bergerak di daerah lokal saja
                                                    yang artinya hanya bergerak di kalimantan selatan khususnya barito kuala.
                                    </h1>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Daftar Harga Paket Wifi</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Jenis Paket</th>
                                                <th>Kecepatan</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1.</td>
                                                <td>Paket 3 Perangkat</td>
                                                <td>3 Mbps</td>
                                                <td>Rp. 130.000</td>
                                            </tr>
                                            <tr>
                                                <td>2.</td>
                                                <td>Paket 6 Perangkat</td>
                                                <td>5 Mbps</td>
                                                <td>Rp. 160.000</td>
                                            </tr>
                                            <tr>
                                                <td>4.</td>
                                                <td>Paket 12 Perangkat</td>
                                                <td>10 Mbps</td>
                                                <td>Rp. 210.000</td>
                                            </tr>
                                        </tbody>
                                    </table>
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