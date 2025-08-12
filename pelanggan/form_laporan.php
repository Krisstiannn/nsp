<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";
$id_user = $_SESSION['id_users'] ?? null;
$data = "SELECT * FROM pelanggan WHERE id_user = '$id_user'";
$hasil = $conn->query($data)->fetch_assoc();
$tanggal = date('Y-m-d');

if (isset($_POST['btn_submit'])) {
    $nama = $_POST['nama_pelanggan'];
    $no_inet = $_POST['id_langganan'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $keluhan = $_POST['keluhan'];

    $insert_laporan = "INSERT INTO perbaikan (id_perbaikan, id_user, id_berlangganan, nama_pelanggan, no_telp, alamat, keluhan, tanggal_melapor) 
               VALUES ('', '$id_user', '$no_inet', '$nama', '$no_telp', '$alamat', '$keluhan', '$tanggal')";
    $result = $conn->query($insert_laporan);

    if($result) {
        echo "<script type= 'text/javascript'>
                alert('Data Berhasil disimpan!');
                document.location.href = 'status_perbaikan.php';
            </script>";
    } else {
        echo "<script type= 'text/javascript'>
                alert('Data Berhasil disimpan!');
                document.location.href = 'form_laporan.php';
            </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/nsp/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/nsp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="/nsp/dist/css/adminlte.min.css">
    <link rel="icon" href="/nsp/storage/netsun.jpg">
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php include "/xampp/htdocs/nsp/layouts/navbar.php" ?>
        <div class="content-wrapper">
            <div class="content">
                <div class="container">
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>Lapor Kerusakan</h1>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="content">
                        <div class="content-fluid">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Input Data Perbaikan</h3>
                                </div>
                                <form action="" method="POST">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="langganan">ID Berlangganan</label>
                                            <input type="hidden" name="id_langganan"
                                                value="<?= $hasil['id_langganan'] ?>">
                                            <input type="text" class="form-control" name="id_langganan"
                                                value="<?= $hasil['id_langganan'] ?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_pelanggan">Nama Pelanggan</label>
                                            <input type="hidden" name="nama_pelanggan"
                                                value="<?= $hasil['nama_pelanggan'] ?>">
                                            <input type="text" class="form-control" name="nama_pelanggan"
                                                value="<?= $hasil['nama_pelanggan'] ?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="no_telp">No Telepon/No WhatsApp</label>
                                            <input type="hidden" name="no_telp" value="<?= $hasil['wa_pelanggan'] ?>">
                                            <input type="text" class="form-control" name="no_telp"
                                                value="<?= $hasil['wa_pelanggan'] ?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">Alamat atau Titik Kordinat</label>
                                            <textarea type="text" class="form-control" name="alamat"
                                                placeholder="Alamat Lengkap Rumah" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="keluhan">Keluhan</label>
                                            <textarea type="text" class="form-control" name="keluhan"
                                                placeholder="Tuliskan detail keluhan" required></textarea>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-success" name="btn_submit">Submit</button>
                                        <a href="dashboard.php" type="submit" class="btn btn-danger"
                                            name="btn_cancel">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
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
    <script src="/nsp/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="/nsp/plugins/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="/nsp/plugins/jquery-mapael/maps/usa_states.min.js"></script>
    <script src="/nsp/plugins/chart.js/Chart.min.js"></script>
    <script src="/nsp/dist/js/demo.js"></script>
    <script src="/nsp/dist/js/pages/dashboard2.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        $(function () {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>