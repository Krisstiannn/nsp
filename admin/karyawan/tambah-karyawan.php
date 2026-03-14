<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$data_nip = mysqli_query($conn, "SELECT MAX(nip_karyawan) AS max_code FROM karyawan");
$data = mysqli_fetch_assoc($data_nip);
$nip_terakhir = $data['max_code'];

if($nip_terakhir) {
    $urutan = (int) substr($nip_terakhir, 4);
    $urutan++;
} else {
    $urutan = 1;
}

$nip_karyawan = "16300".$urutan;

if (isset($_POST['btn_submit'])) {

    $nama_karyawan = $_POST['nama_karyawan'];
    $jabatan_karyawan = $_POST['jabatan_karyawan'];
    $peran = ($jabatan_karyawan == 'admin') ? 'admin' : 'user';

    if (empty($nip_karyawan) || empty($nama_karyawan) || empty($jabatan_karyawan)) {
        echo "<script>
                alert('Tolong isi data dengan benar!');
                document.location.href = 'tambah-karyawan.php';
            </script>";
        die();
    }

    $query_nipKaryawan = "SELECT * FROM karyawan WHERE nip_karyawan = '$nip_karyawan'";
    $result_nipKaryawan = $conn->query($query_nipKaryawan);

    if ($result_nipKaryawan->num_rows > 0) {

        echo "<script>
            alert('NIP Karyawan Sudah Ada!');
            document.location.href = 'tambah-karyawan.php';
        </script>";
        exit;
    }

    // PASSWORD DEFAULT = nama karyawan (lalu di hash)
    $password_default = $nama_karyawan;
    $password_hash = password_hash($password_default, PASSWORD_DEFAULT);

    $query_tambahAkun = "INSERT INTO users (id_users, username, password, peran) 
                         VALUES ('', '$nip_karyawan', '$password_hash', '$jabatan_karyawan')";
    $result_tambahAkun = $conn->query($query_tambahAkun);

    if ($result_tambahAkun) {

        $query_tambahKaryawan = "INSERT INTO karyawan (id, nip_karyawan, nama_karyawan, posisi_karyawan) 
                                 VALUES ('','$nip_karyawan', '$nama_karyawan','$jabatan_karyawan')";
        $result_tambahKaryawan = $conn->query($query_tambahKaryawan);

        if ($result_tambahKaryawan) {

            echo "<script>
                alert('Data Berhasil Disimpan!');
                document.location.href = 'datakaryawan.php';
            </script>";

        } else {

            echo "<script>
                alert('Data Gagal Disimpan!');
                document.location.href = 'tambah-karyawan.php';
            </script>";
        }

    } else {

        echo "<script>
            alert('Gagal membuat user: {$conn->error}');
            window.location.href = 'tambah-karyawan.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karyawan</title>
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
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Karyawan</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="content-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Input Data Karayawan</h3>
                        </div>
                        <form action="tambah-karyawan.php" method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nip">NIP</label>
                                    <input type="text" class="form-control" name="nip_karyawan" value="<?= $nip_karyawan ?>" hidden>
                                    <input type="text" class="form-control" name="nip_karyawan"
                                        placeholder="Nomor Induk Pegawai" value="<?= $nip_karyawan ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Karyawan</label>
                                    <input type="text" class="form-control" name="nama_karyawan"
                                        placeholder="Nama Karyawan">
                                </div>
                                <div class="form-group">
                                    <label for="jabatan">Jabatan Karyawan</label>
                                    <select class="custom-select" name="jabatan_karyawan">
                                        <option>-- Pilih --</option>
                                        <option>admin</option>
                                        <option>teknisi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success" name="btn_submit">Submit</button>
                                <a href="datakaryawan.php" type="submit" class="btn btn-danger"
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