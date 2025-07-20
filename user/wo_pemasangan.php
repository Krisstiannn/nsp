<?php
include "/xampp/htdocs/nsp/services/koneksi.php";
session_start();

$id_karyawan = $_SESSION['id_karyawan'] ?? null;

$query = "SELECT 
            psb.nama_pelanggan, 
            psb.alamat_pelanggan, 
            psb.id, 
            psb.id_langganan, 
            psb.paket_internet, 
            psb.wa_pelanggan,
            psb.rumah_pelanggan,
            psb.ktp_pelanggan, 
            wo.id_karyawan, 
            wo.id_psb
          FROM psb 
          JOIN wo ON wo.id_psb = psb.id 
          WHERE wo.id_karyawan = '$id_karyawan'";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Working Order | Pemasangan Baru</title>
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
        <?php include "/xampp/htdocs/nsp/layouts/sidebar-user.php" ?>
        <!-- END Main Sidebar -->

        <!-- Main Content -->
        <div class="content-wrapper bg-gradient-white">

            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Working Order Pemasangan Baru</h1>
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
                                        <a href="tambah-karyawan.php" class="btn btn-sm btn-warning ">On Progres</a>
                                        <a href="tambah-karyawan.php" class="btn btn-sm btn-success ">Closed</a>

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
                                        <table class="table table-bordered text-center">
                                            <thead class="bg-gradient-cyan">
                                                <tr>
                                                    <th>Nomor Working Order</th>
                                                    <th>ID Langganan</th>
                                                    <th>Nama Pelanggan</th>
                                                    <th>No Telpon Pelanggan</th>
                                                    <th>Alamat Rumah/Tikor</th>
                                                    <th>KTP Pelanggan</th>
                                                    <th>Foto Rumah Pelanggan</th>
                                                    <th>Paket Internet</th>
                                                    <th>Status Pekerjaan</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($result as $pekerjaan) {
                                                    $id_pekerjaan = $pekerjaan['id'];

                                                    $query_status = "SELECT status FROM report_pemasangan WHERE no_wo = '$id_pekerjaan'";
                                                    $result_status = $conn->query($query_status);

                                                    $total = 0;
                                                    $perjalanan = 0;
                                                    $dilokasi = 0;
                                                    $selesai = 0;
                                                    $kendala = 0;
                                                    $ogp = 0;

                                                    while ($row_status = $result_status->fetch_assoc()) {
                                                        $total++;
                                                        if ($row_status['status'] === 'SELESAI') {
                                                            $selesai++;
                                                        } elseif ($row_status['status'] === 'KENDALA') {
                                                            $kendala++;
                                                        } elseif ($row_status['status'] === 'DALAM PERJALANAN') {
                                                            $perjalanan++;
                                                        } elseif ($row_status['status'] === 'SAMPAI DILOKASI') {
                                                            $dilokasi++;
                                                        } elseif ($row_status['status'] === 'ON GOING PROGRES') {
                                                            $ogp++;
                                                        }
                                                    }

                                                    if ($perjalanan > 0) {
                                                        $status = '<span class="badge bg-info">SEDANG DALAM PERJALANAN</span>';
                                                    } elseif ($dilokasi >0) {
                                                        $status = '<span class="badge bg-primary">SAMPAI DILOKASI</span>';
                                                    } elseif ($kendala > 0) {
                                                        $status = '<span class="badge bg-danger">KENDALA</span>';
                                                    } elseif ($ogp > 0) {
                                                        $status = '<span class="badge bg-warning">ON GOING PROGRES</span>';
                                                    } elseif ($total > 0 && $selesai === $total) {
                                                        $status = '<span class="badge bg-success">SELESAI</span>';
                                                    } else {
                                                        $status = '<span class="badge bg-secondary">TIKET DITERIMA</span>';
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?= $pekerjaan['id'] ?></td>
                                                    <td><?= $pekerjaan['id_langganan']?></td>
                                                    <td><?= $pekerjaan['nama_pelanggan'] ?></td>
                                                    <td><?= $pekerjaan['wa_pelanggan']?></td>
                                                    <td><?= $pekerjaan['alamat_pelanggan']?></td>
                                                    <td><img src="/nsp/storage/img/<?= $pekerjaan['rumah_pelanggan'] ?>"
                                                            alt="<?= $pekerjaan['rumah_pelanggan'] ?>" style="width: 100px;">
                                                    </td>
                                                    <td><img src="/nsp/storage/img/<?= $pekerjaan['ktp_pelanggan'] ?>"
                                                            alt="<?= $pekerjaan['ktp_pelanggan'] ?>" style="width: 100px;">
                                                    </td>
                                                    <td><?= $pekerjaan['paket_internet']?></td>
                                                    <td><?= $status ?></td>
                                                    <td>
                                                        <a class="btn btn-warning btn-sm"
                                                            href="report-pemasangan.php?id=<?= $pekerjaan['id'] ?>">
                                                            <i class="fas fa-pencil-alt"></i> Laporkan
                                                        </a>
                                                    </td>
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