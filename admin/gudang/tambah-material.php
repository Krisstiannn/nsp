<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$data_kode = mysqli_query($conn, "SELECT MAX(kode_barang) AS max_code FROM material");
$data = mysqli_fetch_assoc($data_kode);
$kode_terakhir = $data['max_code'];

if($kode_terakhir) {
    $urutan = (int) substr($kode_terakhir, 3);
    $urutan++;
} else {
    $urutan = 2001;
}

$kode_barang = "NSP".$urutan;

if (isset($_POST['btn_submit'])) {
    // $kode_barang = $_POST['kode_barang'];
    $gambar_barang = $_FILES['gambar_barang']['name'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah_awal = $_POST['jumlah_awal'];
    // $jumlah_sisa = $_POST['jumlah_sisa'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    $dir_foto = "/xampp/htdocs/nsp/storage/img/";
    $tmp_file = $_FILES['gambar_barang']['tmp_name'];
    move_uploaded_file($tmp_file, $dir_foto . $gambar_barang);

    if (empty($gambar_barang) || empty($nama_barang)) {
        echo "<script type= 'text/javascript'>
                alert('Tolong isi data dengan benar!');
                document.location.href = 'tambah-material.php';
            </script>";
        die();
    } else {
        $query_tambahData = "INSERT INTO material (id, kode_barang, gambar_barang, nama_barang, jumlah_awal, tanggal_masuk) 
        VALUES ('','$kode_barang', '$gambar_barang', '$nama_barang', '$jumlah_awal',  '$tanggal_masuk')";
        $result_tambahData = $conn->query($query_tambahData);

        if ($result_tambahData) {
            echo "<script type= 'text/javascript'>
                alert('Data Berhasil disimpan!');
                document.location.href = 'material.php';
            </script>";
            die();
        } else {
            echo "<script type= 'text/javascript'>
                alert('Data Gagal disimpan!');
                document.location.href = 'tambah-material.php';
            </script>";
        }
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

        <!-- Navbar -->
        <?php include "/xampp/htdocs/nsp/layouts/header.php" ?>
        <!-- Navbar -->

        <!-- Main Sidebar Container -->
        <?php include "/xampp/htdocs/nsp/layouts/sidebar.php" ?>
        <!-- END Main Sidebar -->

        <!-- Main Content -->
        <div class="content-wrapper bg-gradient-white">
            <section class="content-header">
                <div class="container-fluid text-dark">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Tambah Data Material</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="content-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Input Data Material</h3>
                        </div>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="kode">Kode Barang</label>
                                    <input type="text" class="form-control" name="kode_barang" value="<?= $kode_barang?>" hidden>
                                    <input type="text" class="form-control" name="kode_barang"
                                        placeholder="Kode Barang" value="<?= $kode_barang?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="gambar">Gambar Barang</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="gambar_barang"
                                                accept="image/*">
                                            <label class="custom-file-label" for="foto"></label>
                                        </div>
                                        <!-- <div class="input-group-append">
                                            <span class="input-group-text">Upload</span>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Barang</label>
                                    <input type="text" class="form-control" name="nama_barang"
                                        placeholder="Masukkan Nama Barang">
                                </div>
                                <div class="form-group">
                                    <label for="jumlah">Jumlah Awal Barang</label>
                                    <input type="text" class="form-control" name="jumlah_awal"
                                        placeholder="Jumlah Awal Barang">
                                    <i for="jumlah" class="text-red" style="font-size: 11px;">*sertakan satuan
                                        barang</i>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="jumlah">Jumlah Sisa Barang</label>
                                    <input type="text" class="form-control" name="jumlah_sisa"
                                        placeholder="Jumlah Sisa Barang">
                                    <i for="jumlah" class="text-red" style="font-size: 11px;">*sertakan satuan
                                        barang</i>
                                </div> -->
                                <div class="form-group">
                                    <label>Tanggal Masuk Barang</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="date" class="form-control datetimepicker-input"
                                            data-target="#reservationdate" name="tanggal_masuk" />
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