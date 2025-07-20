<?php
include "/xampp/htdocs/nsp/services/koneksi.php";
session_start();

$id = $_GET['id'];
$id_karyawan = $_SESSION['id_karyawan'] ?? null;

$query = "SELECT 
            perbaikan.id_perbaikan, 
            perbaikan.id_berlangganan, 
            perbaikan.nama_pelanggan, 
            perbaikan.alamat, 
            perbaikan.keluhan, 
            perbaikan.no_telp, 
            wo.id_karyawan
        FROM perbaikan 
        JOIN wo ON wo.id_perbaikan = perbaikan.id_perbaikan
        WHERE perbaikan.id_perbaikan = '$id';";
$result = $conn->query($query)->fetch_assoc();

$query_material = "SELECT * FROM material";
$result_material = $conn->query($query_material);

$cek = $conn->query("SELECT * FROM report_perbaikan WHERE no_wo = '$id'");

if (isset($_POST['btn_submit'])) {
    $id_langganan = $_POST['id_langganan'];
    $no_wo = $_POST['no_wo'];
    // $nama_pelanggan = $_POST['nama_pelanggan'];
    // $alamat_pelanggan = $_POST['alamat'];
    // $no_telp = $_POST['no_telp'];
    // $keluhan = $_POST['keluhan'];
    $status = $_POST['status_pekerjaan'];
    $keterangan = $_POST['keterangan'];
    $material1 = $_POST['material1'] ?? NULL;
    $material2 = $_POST['material2'] ?? NULL;
    $material3 = $_POST['material3'] ?? NULL;
    $jumlah1 = $_POST['jumlah1'] ?? NULL;
    $jumlah2 = $_POST['jumlah2'] ?? NULL;
    $jumlah3 = $_POST['jumlah3'] ?? NULL;
    $foto_awal = $_FILES['foto_awal']['name'];
    $foto_akhir = $_FILES['foto_akhir']['name'];

    $dir_foto = "/xampp/htdocs/nsp/storage/img/";
    $tmp_awal = $_FILES['foto_awal']['tmp_name'];
    $tmp_akhir = $_FILES['foto_akhir']['tmp_name'];
    move_uploaded_file($tmp_awal, $dir_foto . $foto_awal);
    move_uploaded_file($tmp_akhir, $dir_foto . $foto_akhir);

    if ($cek->num_rows == 0) {
        $query_tambahData = "INSERT INTO report_perbaikan (id, no_wo ,id_langganan, status, keterangan, material1, material2, material3, jumlah1, jumlah2, jumlah3, kondisi_awal, kondisi_akhir) 
            VALUES ('', '$no_wo','$id_langganan', '$status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
        $result_tambahData = $conn->query($query_tambahData);
    
        if ($result_tambahData) {
            echo "<script type= 'text/javascript'>
                    alert('Status Berhasil diudapte!');
                    document.location.href = 'wo_perbaikan.php';
                </script>";
        } else {
            echo "<script type= 'text/javascript'>
                    alert('Status Gagal diupdate!');
                    document.location.href = 'report-perbaikan.php';
                </script>";
        }
    } elseif ($cek->num_rows == 1) {
        $edit = "UPDATE report_perbaikan SET 
                        status = '$status',
                        keterangan = '$keterangan',
                        material1 = '$material1',
                        material2 = '$material2',
                        material3 = '$material3',
                        jumlah1 = '$jumlah1',
                        jumlah2 = '$jumlah2',
                        jumlah3 = '$jumlah3',
                        kondisi_awal = '$foto_awal',
                        kondisi_akhir = '$foto_akhir'
                WHERE no_wo = '$id'";
        $hasil = $conn->query($edit);

        if ($hasil) {
            echo "<script type= 'text/javascript'>
                    alert('Status Berhasil diudapte!');
                    document.location.href = 'wo_perbaikan.php';
                </script>";
        } else {
            echo "<script type= 'text/javascript'>
                    alert('Status Gagal diupdate!');
                    document.location.href = 'report-perbaikan.php';
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
    <title>Report Perbaikan Kerusakan</title>
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
        <?php include "/xampp/htdocs/nsp/layouts/sidebar-user.php" ?>

        <!-- Main Content -->
        <div class="content-wrapper bg-gradient-white">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Report</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="content-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Report Perbaikan Kerusakan</h3>
                        </div>
                        <form action="report-perbaikan.php?id=<?= $result['id_perbaikan'] ?>" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="langganan">ID Berlangganan</label>
                                    <input type="hidden" name="id_langganan" value="<?= $result['id_berlangganan'] ?>">
                                    <input type="text" class="form-control" name="id_langganan"
                                        value="<?= $result['id_berlangganan'] ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="no_wo">No Working Order</label>
                                    <input type="hidden" name="no_wo" value="<?= $result['id_perbaikan'] ?>">
                                    <input type="text" class="form-control" name="no_wo" value="<?= $result['id_perbaikan'] ?>"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label for="nama_pelanggan">Nama Pelanggan</label>
                                    <input type="text" class="form-control" name="nama_pelanggan"
                                        value="<?= $result['nama_pelanggan'] ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="no_telp">NO Telepon</label>
                                    <input type="text" class="form-control" name="no_telp"
                                        value="<?= $result['no_telp'] ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat Lengkap</label>
                                    <input type="text" class="form-control" name="alamat"
                                        value="<?= $result['alamat'] ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="keluhan">Keluhan Kerusakan</label>
                                    <input type="text" class="form-control" name="keluhan"
                                        value="<?= $result['keluhan'] ?>" disabled>
                                </div>
                                <?php if ($cek->num_rows > 0): ?>
                                <?php 
                                    $data = $cek->fetch_assoc(); 
                                    $status = $data['status'];
                                ?>
                                <div class="form-group">
                                    <label for="status_pekerjaan">Status Pengerjaan</label>
                                    <select class="custom-select" name="status_pekerjaan" id="status_pekerjaan">
                                        <option <?= $status == 'DALAM PERJALANAN' ? 'selected' : '' ?>>DALAM PERJALANAN</option>
                                        <option <?= $status == 'SAMPAI DILOKASI' ? 'selected' : '' ?>>SAMPAI DILOKASI</option>
                                        <option <?= $status == 'ON GOING PROGRES' ? 'selected' : ''?>>ON GOING PROGRES</option>
                                        <option <?= $status == 'SELESAI' ? 'selected' : '' ?>>SELESAI</option>
                                        <option <?= $status == 'KENDALAL' ? 'selected' : '' ?>>KENDALAL</option>
                                    </select>
                                </div>
                                <?php else: ?>
                                <div class="form-group">
                                    <label for="status_pekerjaan">Status Pengerjaan</label>
                                    <select class="custom-select" name="status_pekerjaan" id="status_pekerjaan">
                                        <option>-- Pilih --</option>
                                        <option>DALAM PERJALANAN</option>
                                        <option>SAMPAI DILOKASI</option>
                                        <option>ON GOING PROGRES</option>
                                        <option>SELESAI</option>
                                        <option>KENDALA</option>
                                    </select>
                                </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <input type="text" class="form-control" name="keterangan" placeholder="keterangan">
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label for="nama_barang">Material Yang digunakan</label>
                                        <select class="custom-select" name="material1">
                                            <?php foreach ($result_material as $material) { ?>
                                            <option><?= $material['nama_barang'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="jumlah">Jumlah material yang digunakan</label>
                                        <input type="text" class="form-control" name="jumlah1" placeholder="jumlah">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label for="nama_barang">Material Yang digunakan</label>
                                        <select class="custom-select" name="material2">
                                            <?php foreach ($result_material as $material) { ?>
                                            <option><?= $material['nama_barang'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="jumlah">Jumlah material yang digunakan</label>
                                        <input type="text" class="form-control" name="jumlah2" placeholder="jumlah">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label for="nama_barang">Material Yang digunakan</label>
                                        <select class="custom-select" name="material3">
                                            <?php foreach ($result_material as $material) { ?>
                                            <option><?= $material['nama_barang'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="jumlah">Jumlah material yang digunakan</label>
                                        <input type="text" class="form-control" name="jumlah3" placeholder="jumlah">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="awal">Foto Kondisi Awal</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="foto_awal" accept="img/*">
                                            <label class="custom-file-label" for="exampleInputFile"></label>
                                        </div>
                                    </div>

                                    <label for="akhir">Foto Kondisi Akhir</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="foto_akhir"
                                                accept="img/*">
                                            <label class="custom-file-label" for="exampleInputFile"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success" name="btn_submit">Submit</button>
                                <a href="wo_perbaikan.php" type="submit" class="btn btn-danger" name="btn_cancel">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <!-- END Main Content -->

        <?php include "/xampp/htdocs/nsp/layouts/footer.php" ?>
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