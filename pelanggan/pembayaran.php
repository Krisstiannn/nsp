<?php
session_start();
include  "/xampp/htdocs/nsp/services/koneksi.php";
date_default_timezone_set('Asia/Makassar');

$id = $_GET['id'];
$bulan_ini = date('Y-m-01');
$tanggal = date('d-m-Y');
$id_user = $_SESSION['id_users'];
$nama_pelanggan = $_SESSION['nama_pelanggan'];

$data_pelanggan = "SELECT
                        p.id_user,
                        p.id_langganan,
                        p.nama_pelanggan,
                        p.jenis_layanan,
                        p.status_pelanggan,
                        j.jenis_paket,
                        j.harga
                   FROM pelanggan p 
                   LEFT JOIN jenis_paket j ON j.jenis_paket = p.jenis_layanan
                   WHERE id_user = '$id_user' AND nama_pelanggan = '$nama_pelanggan'";
$result = $conn->query($data_pelanggan);


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
                                    <h1 class="m-0">Selamat Datang ... di Website Resmi Net Sun Power</h1>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="invoice p-3 mb-3">

                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i></i>Pembayaran Tagihan Internet Bulan...
                                    <small class="float-right"><?= $tanggal?></small>
                                </h4>
                            </div>
                        </div>
                        <div class="row invoice-info">
                            <div class="col-sm-12 invoice-col">
                                <span>Untuk Pembayaran Tagihan Bulanan Silahkan Transfer ke <strong>Nomor Rekening
                                        112299008 AN PT.Net Sun Power/Mandiri</strong></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <form action="pembayaran.php?id=<?= $id?>" method="POST">
                                    <?php while($row_data = $result->fetch_assoc()) :?>
                                    <div class="form-group">
                                        <label for="langganan">ID Berlangganan</label>
                                        <input type="hidden" name="id_langganan"
                                            value="<?= $row_data['id_langganan'] ?>">
                                        <input type="text" class="form-control" value="<?= $row_data['id_langganan'] ?>"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Nama Pelanggan</label>
                                        <input type="text" class="form-control" name="nama_pelanggan"
                                            placeholder="Nama Pelanggan" value="<?= $row_data['nama_pelanggan']?>"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="layanan">Jenis Layanan</label>
                                        <input type="text" class="form-control" name="jenis_layanan"
                                            placeholder="Nama Pelanggan" value="<?= $row_data['jenis_layanan']?>"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="harga">Jumlah Tagihan</label>
                                        <input type="text" class="form-control" name="nama_pelanggan"
                                            placeholder="Nama Pelanggan"
                                            value="<?= "Rp. " . number_format($row_data['harga'], 0, ',', '.');?>"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="harga">Tanggal Pembayaran</label>
                                        <input type="hidden" class="form-control" name="tanggal_pembayaran"
                                            placeholder="Tanggal Pembayaran" value="<?= $tanggal?>">
                                        <input type="text" class="form-control" name="tanggal_pembayaran"
                                            placeholder="Tanggal Pembayaran" value="<?= $tanggal?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="bukti">Upload Bukti Pembayaran</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="bukti_pembayaran"
                                                    accept="img/*">
                                                <label class="custom-file-label" for="exampleInputFile"
                                                    required></label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile;?>
                                </form>
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
    <script>
        $(function () {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>