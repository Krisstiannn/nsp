<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

if (isset($_POST['btn_submit'])) {
    $nama_supplier = $_POST['nama_supplier'];
    $alamat_supplier = $_POST['alamat_supplier'];
    $kontak_supplier = $_POST['kontak_supplier'];
    $nama_pic = $_POST['nama_pic'];
    $kontak_pic = $_POST['kontak_pic'];

        $query_tambahData = "INSERT INTO supplier (id_supplier, nama_supplier, alamat_supplier, kontak_supplier, nama_pic, kontak_pic) 
        VALUES ('', '$nama_supplier', '$alamat_supplier', '$kontak_supplier', '$nama_pic', '$kontak_pic')";
        $result_tambahData = $conn->query($query_tambahData);

        if ($result_tambahData) {
            echo "<script type= 'text/javascript'>
                alert('Data Berhasil disimpan!');
                document.location.href = 'supplier.php';
            </script>";
            die();
        } else {
            echo "<script type= 'text/javascript'>
                alert('Data Gagal disimpan!');
                document.location.href = 'tambah-supplier.php';
            </script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Supplier</title>
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

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php include "/xampp/htdocs/nsp/layouts/header.php" ?>
        <?php include "/xampp/htdocs/nsp/layouts/sidebar.php" ?>
        
        <div class="content-wrapper bg-gradient-white">
            <section class="content-header">
                <div class="container-fluid text-dark">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Tambah Data Supplier</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="content-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Input Data Supplier</h3>
                        </div>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama">Nama Supplier</label>
                                    <input type="text" class="form-control" name="nama_supplier"
                                        placeholder="Masukkan Nama Supplier">
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat Supplier</label>
                                    <input type="text" class="form-control" name="alamat_supplier"
                                        placeholder="Masukkan Alamat Supplier">
                                </div>
                                <div class="form-group">
                                    <label for="kontak">Kontak Supplier</label>
                                    <input type="text" class="form-control" name="kontak_supplier"
                                        placeholder="Masukkan Kontak Supplier">
                                </div>
                                <div class="form-group">
                                    <label for="nama_pic">Nama PIC</label>
                                    <input type="text" class="form-control" name="nama_pic"
                                        placeholder="Masukkan Nama PIC">
                                </div>
                                <div class="form-group">
                                    <label for="kontak_pic">Kontak PIC</label>
                                    <input type="text" class="form-control" name="kontak_pic"
                                        placeholder="Masukkan Kontak PIC">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success" name="btn_submit">Submit</button>
                                <a href="material.php" type="submit" class="btn btn-danger" name="btn_cancel">Cancel</a>
                            </div>
                        </form>
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
    <script src="/nsp/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>