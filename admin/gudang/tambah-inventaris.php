<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$data_kode = mysqli_query($conn, "SELECT MAX(kode_barang) AS max_code FROM inventaris");
$data = mysqli_fetch_assoc($data_kode);
$kode_terakhir = $data['max_code'];

if($kode_terakhir) {
    $urutan = (int) substr($kode_terakhir, 3);
    $urutan++;
} else {
    $urutan = 1001;
}

$kode_barang = "NSP".$urutan;

if (isset($_POST['btn_submit'])) {
    //$kode_barang = $_POST['kode_barang'];
    $serial_number = $_POST['serial_number'];
    $nama_barang = $_POST['nama_barang'];
    $merk = $_POST['merk_barang'];
    $kondisi_barang = $_POST['kondisi_barang'];
    $spesifikasi = $_POST['spesifikasi'];
    $jenis_barang = $_POST['jenis_barang'];
    // $jumlah_barang = $_POST['jumlah_barang'];
    $gambar_barang = $_FILES['gambar_barang']['name'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    $dir_foto = "/xampp/htdocs/nsp/storage/img/";
    $tmp_file = $_FILES['gambar_barang']['tmp_name'];
    move_uploaded_file($tmp_file, $dir_foto . $gambar_barang);

    if (empty($nama_barang) || empty($kondisi_barang) || empty($jenis_barang) || empty($merk) || empty($serial_number) || empty($spesifikasi) || empty($gambar_barang) || empty($tanggal_masuk)) {
        echo "<script type= 'text/javascript'>
                alert('Tolong isi data dengan benar!');
                document.location.href = 'tambah-inventaris.php';
            </script>";
        die();
    } else {
        $query_tambahData = "INSERT INTO inventaris (id, kode_barang, nama_barang, kondisi_barang, jenis_barang, gambar_barang, tanggal_masuk, merk_barang, spesifikasi, serial_number) 
        VALUES ('', '$kode_barang', '$nama_barang', '$kondisi_barang','$jenis_barang', '$gambar_barang', '$tanggal_masuk', '$merk', '$spesifikasi', '$serial_number')";
        $result_tambahData = $conn->query($query_tambahData);

        if ($result_tambahData) {
            echo "<script type= 'text/javascript'>
                alert('Data Berhasil disimpan!');
                document.location.href = 'inventaris.php';
            </script>";
        } else {
            echo "<script type= 'text/javascript'>
                alert('Data Gagal disimpan!');
                document.location.href = 'tambah-inventaris.php';
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
    <title>Gudang | Inventaris</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/nsp/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/nsp/plugins/daterangepicker/daterangepicker.css">
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
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Tambah Data Inventaris</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="content-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Input Inventaris</h3>
                        </div>
                        <form method="POST" action="tambah-inventaris.php" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="kode">Kode Barang</label>
                                    <input type="text" class="form-control" name="kode_barang" value="<?= $kode_barang?>" hidden>
                                    <input type="text" class="form-control" name="kode_barang"
                                        placeholder="Kode Barang" value="<?= $kode_barang?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="sn">Serial Number Barang</label>
                                    <input type="text" class="form-control" name="serial_number"
                                        placeholder="Serial Number Barang">
                                </div>
                                <div class="form-group">
                                    <label for="gambar">Gambar Barang</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="gambar_barang"
                                                accept="image/*">
                                            <label class="custom-file-label" for="foto"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Barang</label>
                                    <input type="text" class="form-control" name="nama_barang"
                                        placeholder="Masukkan Nama Barang">
                                </div>
                                <div class="form-group">
                                    <label for="merk">Merk Barang</label>
                                    <input type="text" class="form-control" name="merk_barang"
                                        placeholder="Masukkan Merk Barang">
                                </div>
                                <div class="form-group">
                                    <label for="Spesifikasi">Spesifikasi Barang</label>
                                    <textarea type="text" class="form-control" name="spesifikasi"
                                        placeholder="Masukkan Spesifikasi Barang" rows="5"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="Jenis">Jenis Barang</label>
                                    <input type="text" class="form-control" name="jenis_barang"
                                        placeholder="Masukkan Jenis Barang">
                                </div>
                                <div class="form-group">
                                    <label for="kondisi">Kondisi Barang</label>
                                    <select class="custom-select" name="kondisi_barang">
                                        <option>-- Pilih --</option>
                                        <option>Baru</option>
                                        <option>Bekas</option>
                                        <option>Rusak</option>
                                    </select>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="jumlah">Jumlah</label>
                                    <input type="text" class="form-control" name="jumlah_barang" placeholder="Jumlah">
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
                                <a href="inventaris.php" type="submit" class="btn btn-danger"
                                    name="btn_cancel">Cancel</a>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap-datetimepicker@4.17.47/build/js/bootstrap-datetimepicker.min.js">
    </script>

    <script src="/nsp/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="/nsp/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="/nsp/plugins/inputmask/jquery.inputmask.min.js"></script>
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