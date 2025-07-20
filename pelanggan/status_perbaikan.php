<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";

$id_user = $_SESSION['id_users'];
$nama_pelanggan = $_SESSION['nama_pelanggan'];

$queryPelanggan = "SELECT * FROM perbaikan WHERE id_user = '$id_user' ORDER BY id_perbaikan DESC";
$resultPelanggan = $conn->query($queryPelanggan);
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
                    <section class="content-header">
                        <h1 class="m-0">Status Tiket Perbaikan Anda</h1>
                    </section>

                    <section class="content">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Riwayat Tiket Perbaikan</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Langganan</th>
                                            <th>Keluhan</th>
                                            <th>Teknisi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                    $no = 1;
                    while ($dataPelanggan = $resultPelanggan->fetch_assoc()) {
                      $id_langganan = $dataPelanggan['id_berlangganan'];
                      $keluhan = $dataPelanggan['keluhan'];
                      $id_perbaikan = $dataPelanggan['id_perbaikan'];

                      $teknisi = '-';
                      $status = 'Tiket Sudah Diberikan Ke Teknisi';

                      $queryWO = "SELECT * FROM wo WHERE id_perbaikan = '$id_perbaikan'";
                      $resultWO = $conn->query($queryWO);

                      if ($resultWO->num_rows > 0) {
                        $dataWO = $resultWO->fetch_assoc();
                        $id_teknisi = $dataWO['id_karyawan'];

                        $queryTeknisi = "SELECT nama_karyawan FROM karyawan WHERE id = '$id_teknisi'";
                        $resultTeknisi = $conn->query($queryTeknisi);
                        if ($resultTeknisi->num_rows > 0) {
                          $teknisi = $resultTeknisi->fetch_assoc()['nama_karyawan'];
                        }
                      }

                      $queryReport = "SELECT status FROM report_perbaikan WHERE no_wo = '$id_perbaikan' ORDER BY id DESC LIMIT 1";
                      $resultReport = $conn->query($queryReport);
                      if ($resultReport->num_rows > 0) {
                        $status = $resultReport->fetch_assoc()['status'];
                      } 

                      echo "<tr>
                              <td>$no</td>
                              <td>$id_langganan</td>
                              <td>$keluhan</td>
                              <td>$teknisi</td>
                              <td>$status</td>
                            </tr>";
                      $no++;
                    }
                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
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