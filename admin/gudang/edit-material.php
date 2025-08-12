<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$id = $_GET['id'];
$query_tampilData = "SELECT * FROM material WHERE id = '$id'";
$result_tampilData = $conn->query($query_tampilData)->fetch_assoc();

if (isset($_POST['btn_submit'])) {
    // $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah_awal = $_POST['jumlah_awal'] + $_POST['jumlah_restok'];
    // $jumlah_sisa = $_POST['jumlah_sisa'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    if ($_FILES['gambar_barang']['name'] == "") {
        $gambar_barang = $result_tampilData['gambar_barang'];
    } else {
        $gambar_barang = $_FILES['gambar_barang']['name'];
        unlink("/xampp/htdocs/nsp/storage/img/" . $result_tampilData['gambar_barang']);
        move_uploaded_file($_FILES['gambar_barang']['tmp_name'], "/xampp/htdocs/nsp/storage/img/" . $_FILES['gambar_barang']['name']);
    }

    // die();
    // $dir_foto = "/xampp/htdocs/nsp/storage/img/";
    // $tmp_file = $_FILES['gambar_barang']['tmp_name'];
    // move_uploaded_file($tmp_file, $dir_foto.$gambar_barang);

    $query_editData = "UPDATE material SET gambar_barang = '$gambar_barang', nama_barang = '$nama_barang', 
                       jumlah_awal = '$jumlah_awal', tanggal_masuk = '$tanggal_masuk'
                       WHERE id = '$id'";
    $result_editData = $conn->query($query_editData);

    if ($result_editData) {
        echo "<script type= 'text/javascript'>
                alert('Data Berhasil dirubah!');
                document.location.href = 'material.php';
            </script>";
    } else {
        echo "<script type= 'text/javascript'>
                alert('Data Berhasil dirubah!');
                document.location.href = 'edit-material.php?id=$id';
            </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gudang | Material</title>
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
                            <h1>Edit Data Material</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="content-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Data Barang</h3>
                        </div>
                        <form method="POST" action="edit-material.php?id=<?= $result_tampilData['id'] ?>"
                            enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="kode">Kode Barang</label>
                                    <input type="text" class="form-control" name="kode_barang" placeholder="Kode Barang"
                                        value="<?= $result_tampilData['kode_barang'] ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="gambar">Gambar Barang</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="gambar_barang"
                                                accept="image/*" value="<?= $result_tampilData['gambar_barang'] ?>">
                                            <label class="custom-file-label"
                                                for="foto"><?= $result_tampilData['gambar_barang'] ?></label>
                                        </div>
                                        <!-- <div class="input-group-append">
                                            <span class="input-group-text">Upload</span>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Barang</label>
                                    <input type="text" class="form-control" name="nama_barang"
                                        placeholder="Masukkan Nama Barang"
                                        value="<?= $result_tampilData['nama_barang'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="jumlah">Jumlah Awal Barang</label>
                                    <input type="text" class="form-control" name="jumlah_awal"
                                        placeholder="Jumlah Awal Barang"
                                        value="<?= $result_tampilData['jumlah_awal'] ?>">
                                    <i for="jumlah" class="text-red" style="font-size: 11px;">*sertakan satuan
                                        barang</i>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah">Jumlah Restok Barang</label>
                                    <input type="text" class="form-control" name="jumlah_restok"
                                        placeholder="Jumlah Restok Barang">
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Masuk Barang</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="date" class="form-control datetimepicker-input"
                                            data-target="#reservationdate" name="tanggal_masuk"
                                            value="<?= $result_tampilData['tanggal_masuk'] ?>" />
                                    </div>
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