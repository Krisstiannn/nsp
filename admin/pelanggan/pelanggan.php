<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$query_dataPelanggan = "SELECT * FROM pelanggan";
$result_dataPelanggan = $conn->query($query_dataPelanggan);

if (isset($_POST['cari'])) {
    $cari = $_POST['cari'];

    $query_cari = "SELECT * FROM pelanggan WHERE ";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pelanggan</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="/nsp/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/nsp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="/nsp/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/nsp/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/nsp/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/nsp/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
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
                            <h1>Data Pelanggan</h1>
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
                                        <!-- <div class="card-title">
                                            <a href="tambah-pelanggan.php" class="btn btn-sm btn-success ">Tambah
                                                Data</a>
                                        </div> -->

                                        <div class="card-title float-right">
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" name="cari" class="form-control float-right"
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
                                                    <th rowspan="2">ID Berlangganan</th>
                                                    <th rowspan="2">Nama Pelanggan</th>
                                                    <th rowspan="2">Alamat Rumah/Tikor</th>
                                                    <th rowspan="2">No WhatsApp</th>
                                                    <th rowspan="2">Jenis Layanan</th>
                                                    <th colspan="2">ID Login</th>
                                                    <th rowspan="2">Status</th>
                                                    <!-- <th rowspan="2">Action</th> -->
                                                </tr>
                                                <tr>
                                                    <th>Username</th>
                                                    <th>Password</th>
                                                </tr>
                                            </thead>
                                            <?php foreach ($result_dataPelanggan as $pelanggan) { ?>
                                                <tbody>
                                                    <tr>
                                                        <td><?= $pelanggan['id_langganan'] ?></td>
                                                        <td><?= $pelanggan['nama_pelanggan'] ?></td>
                                                        <td><?= $pelanggan['alamat_pelanggan'] ?></td>
                                                        <td><?= $pelanggan['wa_pelanggan'] ?></td>
                                                        <td><?= $pelanggan['jenis_layanan'] ?></td>
                                                        <td><?= $pelanggan['username']?></td>
                                                        <td><?= $pelanggan['password']?></td>
                                                        <td><?= $pelanggan['status_pelanggan'] ?></td>
                                                        <!-- <td>
                                                            <a class="btn btn-info btn-sm"
                                                                href="edit-pelanggan.php?id=<?= $pelanggan['id'] ?>">
                                                                <i class="fas fa-pencil-alt">
                                                                </i>
                                                                Edit
                                                            </a>
                                                            <a class="btn btn-danger btn-sm"
                                                                href="hapus-pelanggan.php?id=<?= $pelanggan['id'] ?>"
                                                                onClick="javascript: return confirm('Apakah yakin ingin menghapus data ini?');">
                                                                <i class="fas fa-trash">
                                                                </i>
                                                                Delete
                                                            </a>
                                                        </td> -->
                                                    </tr>
                                                </tbody>
                                            <?php } ?>
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
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
</body>

</html>