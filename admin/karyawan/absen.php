<?php
include "/xampp/htdocs/nsp/services/koneksi.php";
session_start();

date_default_timezone_set('Asia/Makassar');
$id = $_SESSION['id_karyawan'];
$nip_karyawan = $_SESSION['nip'];
$nama_karyawan = $_SESSION['nama_karyawan'];
$tanggal = date('Y-m-d');
$jam = date('H:i:s');

$query_id_karyawan = "SELECT id FROM karyawan WHERE nip_karyawan = '$nip_karyawan'";
$result_id = $conn->query($query_id_karyawan);

$query_tampilData = "SELECT * FROM absen";
$tampil_data = $conn->query($query_tampilData);

if (isset($_POST['btn_absen'])) {
    if ($result_id->num_rows > 0) {
        $row = $result_id->fetch_assoc();
        $id_karyawan = $row['id'];

        $validasi_absen = "SELECT * FROM absen WHERE nip_karyawan = '$nip_karyawan' AND tanggal = '$tanggal'";
        $validasi_result = $conn->query($validasi_absen);

        if($validasi_absen->num_rows>0) {
            echo "<script>alert('Anda sudah absen masuk hari ini!');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        $sql = "INSERT INTO absen (id, id_karyawan, nip_karyawan, nama_karyawan, tanggal, jam_masuk) 
                VALUES (NULL, '$id_karyawan', '$nip_karyawan', '$nama_karyawan', '$tanggal', '$jam')";
         $result = $conn->query($sql);
    
            if ($result === TRUE) {
                echo "<script type='text/javascript'>alert('Absen BERHASIL Dilakukan!');</script>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<script type='text/javascript'>alert('Absen GAGAL Di Lakukan!');</script>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
    } else {
        echo "<script type='text/javascript'>alert('Data Karyawan Tidak Ditemukan!');</script>";
    }
}

if (isset($_POST['absen_keluar'])) {
    $query_tampilabsen = "SELECT id, jam_keluar FROM absen 
                WHERE nip_karyawan = '$nip_karyawan' 
                AND tanggal = '$tanggal' 
                ORDER BY id DESC LIMIT 1";

    $tampil_absen = $conn->query($query_tampilabsen);
    if ($tampil_absen->num_rows > 0) {
        $absen = $tampil_absen->fetch_assoc();

        if (empty($absen['jam_keluar'])) {
            $update = "UPDATE absen 
            SET jam_keluar = '$jam' 
            WHERE nip_karyawan = '$nip_karyawan' 
            AND tanggal = '$tanggal' 
            AND jam_keluar IS NULL 
            ORDER BY id ASC LIMIT 1";
            if ($conn->query($update) === TRUE) {
                echo "<script>alert('Terima Kasih Sudah Berjuang Hari Ini :)');</script>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<script>alert('Gagal memperbarui jam keluar.');</script>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            echo "<script>alert('Anda sudah melakukan absen keluar hari ini!');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        echo "<script>alert('Anda belum absen masuk hari ini!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absen</title>
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

<body class="hold-transition  sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include "/xampp/htdocs/nsp/layouts/header.php" ?>
        <!-- Navbar -->

        <!-- Main Sidebar Container -->
        <?php include "/xampp/htdocs/nsp/layouts/sidebar.php" ?>
        <!-- END Main Sidebar -->

        <!-- Main Content -->
        <div class="content-wrapper bg-gradient-white">

            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Absen Karyawan</h1>
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
                                    <div class="card-tools">
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
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead class="bg-gradient-cyan">
                                                <tr>
                                                    <th>Nomor Induk Pegawai</th>
                                                    <th>Nama Karyawan</th>
                                                    <th>Tanggal</th>
                                                    <th>Absen Masuk</th>
                                                    <th>Absen Pulang</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($tampil_data as $absen) { ?>
                                                <tr>
                                                    <td><?= $absen['nip_karyawan'] ?></td>
                                                    <td><?= $absen['nama_karyawan'] ?></td>
                                                    <td><?= date('d-m-Y', strtotime($absen['tanggal'])) ?></td>
                                                    <td><?= $absen['jam_masuk'] ?></td>
                                                    <td><?= $absen['jam_keluar'] ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- <div class="card-footer clearfix">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-success float-right ">Tambah
                                        Data</a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- END Main Content -->

        <!-- Main Footer -->
        <?php include "/xampp/htdocs/nsp/layouts/footer.php" ?>
        <!-- End Footer -->
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