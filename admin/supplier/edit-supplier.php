<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$id = $_GET['id'];
$query_tampilData = "SELECT * FROM supplier WHERE id_supplier = '$id'";
$result_tampilData = $conn->query($query_tampilData)->fetch_assoc();

if (isset($_POST['btn_submit'])) {
    $nama_supplier = $_POST['nama_supplier'];
    $alamat_supplier = $_POST['alamat_supplier'];
    $kontak_supplier = $_POST['kontak_supplier'];
    $nama_pic = $_POST['nama_pic'];
    $kontak_pic = $_POST['kontak_pic'];
    
    $query_editData = "UPDATE supplier SET nama_supplier = '$nama_supplier', alamat_supplier = '$alamat_supplier', 
                       kontak_supplier = '$kontak_supplier', nama_pic = '$nama_pic', kontak_pic = '$kontak_pic'
                       WHERE id_supplier = '$id'";
    $result_editData = $conn->query($query_editData);

    if ($result_editData) {
        echo "<script type= 'text/javascript'>
                alert('Data Berhasil dirubah!');
                document.location.href = 'supplier.php';
            </script>";
    } else {
        echo "<script type= 'text/javascript'>
                alert('Data Gagal dirubah!');
                document.location.href = 'edit-supplier.php?id=$id';
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
        >

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
                            <h1>Edit Data Supplier</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="content-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Data Supplier</h3>
                        </div>
                        <form method="POST" action="edit-supplier.php?id=<?= $result_tampilData['id_supplier'] ?>"
                            enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nama">Nama Supplier</label>
                                    <input type="text" class="form-control" name="nama_supplier"
                                        placeholder="Masukkan Nama Supplier"
                                        value="<?= $result_tampilData['nama_supplier'] ?>">
                                </div>
                               <div class="form-group">
                                    <label for="alamat">Alamat Supplier</label>
                                    <input type="text" class="form-control" name="alamat_supplier"
                                        placeholder="Masukkan Alamat Supplier" value="<?= $result_tampilData['alamat_supplier'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="kontak">Kontak Supplier</label>
                                    <input type="text" class="form-control" name="kontak_supplier"
                                        placeholder="Masukkan Kontak Supplier" value="<?= $result_tampilData['kontak_supplier'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="nama_pic">Nama PIC</label>
                                    <input type="text" class="form-control" name="nama_pic"
                                        placeholder="Masukkan Nama PIC" value="<?= $result_tampilData['nama_pic'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="kontak_pic">Kontak PIC</label>
                                    <input type="text" class="form-control" name="kontak_pic"
                                        placeholder="Masukkan Kontak PIC" value="<?= $result_tampilData['kontak_pic'] ?>">
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