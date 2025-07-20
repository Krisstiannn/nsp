<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";
$id_langganan = "";
$tanggal = date('d-m-Y');
$id_user = $_SESSION['id_users'] ?? null;

$cekLangganan = $conn->query("SELECT id_langganan FROM pelanggan WHERE id_user = '$id_user'");
if ($cekLangganan->num_rows > 0) {
    echo "<script>alert('Anda sudah memiliki langganan. Tidak dapat mengajukan pemasangan lagi.'); window.location.href='status_pemasangan.php';</script>";
    exit;
}

function generateIdLangganan ($tanggal, $conn) {
    $date = date_create($tanggal);
    $tgl = date_format($date, 'd');
    $bln = date_format($date, 'm');
    $thn = date_format($date, 'y');

    do{
        $acak = str_pad(mt_rand(0, 999), 4, '0', STR_PAD_LEFT);
        $id = "21" . $tgl . $bln . $thn . $acak;

        $cek =$conn->query("SELECT * FROM pelanggan WHERE id_langganan = '$id'");
    } while ($cek->num_rows>0);

    return $id;
}

$id_langganan = generateIdLangganan($tanggal, $conn);

if (isset($_POST['btn_submit'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $wa_pelanggan = $_POST['no_wa'];
    $alamat = $_POST['alamat'];
    $foto_rumah = $_FILES['foto_rumah']['name'];
    $foto_ktp = $_FILES['foto_ktp']['name'];
    $paket = $_POST['paket'];

    $dir_foto = "/xampp/htdocs/nsp/storage/img/";
    $tmp_fileRumah = $_FILES['foto_rumah']['tmp_name'];
    $tmp_fileKtp = $_FILES['foto_ktp']['tmp_name'];
    move_uploaded_file($tmp_fileRumah, $dir_foto . $foto_rumah);
    move_uploaded_file($tmp_fileKtp, $dir_foto . $foto_ktp);


    if (empty($nama_pelanggan) || empty($wa_pelanggan) || empty($alamat) || empty($foto_rumah) || empty($foto_ktp) || empty($paket)) {
        echo "<script type= 'text/javascript'>
                alert('Tolong isi data dengan benar!');
                document.location.href = 'form_pemasangan.php';
            </script>";
        die();
    } else {
        $query_tambahData = "INSERT INTO psb (id, id_langganan, nama_pelanggan, wa_pelanggan, alamat_pelanggan, rumah_pelanggan, ktp_pelanggan, paket_internet, id_user)
                             VALUES ('', '$id_langganan', '$nama_pelanggan', '$wa_pelanggan', '$alamat','$foto_rumah', '$foto_ktp', '$paket', '$id_user')";
        $result_tambahData = $conn->query($query_tambahData);

        if ($result_tambahData) {
            echo "<script type= 'text/javascript'>
                alert('Pendaftaran Pemasangan Berhasil, Silahkan Tunggu!');
                document.location.href = 'status_pemasangan.php';
            </script>";
        } else {
            echo "<script type= 'text/javascript'>
                alert('Data Gagal disimpan!');
                document.location.href = 'form_pemasangan.php';
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
    <title>Dashboard Pemasangan</title>
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
                                    <h1>Pemasangan Baru</h1>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="content">
                        <div class="content-fluid">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Input Data Pemasangan Baru</h3>
                                </div>
                                <form action="form_pemasangan.php" method="POST" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="langganan">ID Berlangganan</label>
                                            <input type="hidden" name="id_langganan" value="<?= $id_langganan ?>">
                                            <input type="text" class="form-control" value="<?= $id_langganan ?>"
                                                disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama">Nama Pelanggan</label>
                                            <input type="text" class="form-control" name="nama_pelanggan"
                                                placeholder="Nama Pelanggan" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="whatsapp">No WA</label>
                                            <input type="text" class="form-control" name="no_wa" placeholder="NO WA"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">Alamat atau Titik Kordinat</label>
                                            <textarea type="text" class="form-control" name="alamat"
                                                placeholder="Alamat Lengkap Rumah" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="rumah">Foto Rumah</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="foto_rumah"
                                                        accept="img/*">
                                                    <label class="custom-file-label" for="exampleInputFile"
                                                        required></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="ktp">Foto KTP</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="foto_ktp"
                                                        accept="img/*">
                                                    <label class="custom-file-label" for="exampleInputFile"
                                                        required></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="paket">Pilihan Paket Internet</label>
                                            <select class="custom-select" name="paket" required>
                                                <option>-- Pilih --</option>
                                                <option>Paket 3 Perangkat</option>
                                                <option>Paket 6 Perangkat</option>
                                                <option>Paket 12 Perangkat</option>
                                            </select>
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
    <script src="/nsp/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="/nsp/plugins/raphael/raphael.min.js"></script>
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