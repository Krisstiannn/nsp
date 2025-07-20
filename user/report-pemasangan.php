<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";

$id = $_GET['id'] ?? null;
$id_karyawan = $_SESSION['id_karyawan'] ?? null;
$tanggal = date('d-m-Y');

$query = "SELECT psb.id, psb.id_langganan, psb.nama_pelanggan, psb.alamat_pelanggan, psb.wa_pelanggan, wo.id_karyawan, wo.id_psb
FROM psb
LEFT JOIN wo ON wo.id_psb = psb.id
WHERE wo.id_psb = '$id';";
$result = $conn->query($query)->fetch_assoc();

$query_material = "SELECT * FROM material";
$result_material = $conn->query($query_material);

$cek = $conn->query("SELECT * FROM report_pemasangan WHERE no_wo = '$id'");

if (isset($_POST['btn_submit'])) {
    $no_wo = $_POST['no_wo'];
    // $nama_pelanggan = $_POST['nama_pelanggan'];
    // $alamat_pelanggan = $_POST['alamat_pelanggan'];
    // $wa_pelanggan = $_POST['wa_pelanggan'];
    $id_langganan = $_POST['id_langganan'];
    $status = $_POST['status_pekerjaan'];
    $keterangan = $_POST['keterangan'];
    $material1 = $_POST['material1'] ?? NULL;
    $material2 = $_POST['material2'] ?? NULL;
    $material3 = $_POST['material3'] ?? NULL;
    $jumlah1 = $_POST['jumlah1'] ?? NULL;
    $jumlah2 = $_POST['jumlah2'] ?? NULL;
    $jumlah3 = $_POST['jumlah3'] ?? NULL;
    $foto_odp = $_FILES['foto_odp']['name'];
    $foto_redaman = $_FILES['foto_redaman']['name'];
    $foto_modem = $_FILES['foto_modem']['name'];

    $dir_foto = "/xampp/htdocs/nsp/storage/img/";
    $tmp_odp = $_FILES['foto_odp']['tmp_name'];
    $tmp_redaman = $_FILES['foto_redaman']['tmp_name'];
    $tmp_modem = $_FILES['foto_modem']['tmp_name'];
    move_uploaded_file($tmp_odp, $dir_foto . $foto_odp);
    move_uploaded_file($tmp_redaman, $dir_foto . $foto_redaman);
    move_uploaded_file($tmp_modem, $dir_foto . $foto_modem);

    if ($cek->num_rows == 0) {
        //$ambil = $conn->query("SELECT * FROM report_pemasangan WHERE no_wo");

        $query_tambahData = "INSERT INTO report_pemasangan (id, no_wo, id_langganan, status) 
                 VALUES ('', '$no_wo', '$id_langganan', '$status')";
        $result_tambahData = $conn->query($query_tambahData);
        
        if ($result_tambahData) {
            // insertPelanggan($conn, $id_langganan);
            echo "<script type= 'text/javascript'>
                    alert('Data Berhasil disimpan!');
                    document.location.href = 'wo_pemasangan.php';
                </script>";
        } else {
            echo "<script type= 'text/javascript'>
                    alert('Data Gagal disimpan!');
                    document.location.href = 'report-pemasangan.php';
                </script>";
        }
    } elseif ($cek->num_rows == 1) {
        $edit = "UPDATE report_pemasangan SET 
                 status = '$status', 
                 keterangan = '$keterangan',
                 material1 = '$material1',
                 material2 = '$material2',
                 material3 = '$material3', 
                 jumlah1 = '$jumlah1', 
                 jumlah2 = '$jumlah2', 
                 jumlah3 = '$jumlah3',
                 foto_odp = '$foto_odp',
                 foto_redaman = '$foto_redaman',
                 foto_modem = '$foto_modem' 
                 WHERE no_wo = '$id'";
        $hasil_edit = $conn->query($edit);
        if ($hasil_edit) {
            insertPelanggan($conn, $id_langganan, $tanggal);
            echo "<script type= 'text/javascript'>
                    alert('Data Berhasil Diupdate');
                    document.location.href = 'wo_pemasangan.php';
                </script>";
        } else {
            echo "<script type= 'text/javascript'>
                    alert('Data Gagal diupdate!');
                    document.location.href = 'report-pemasangan.php';
                </script>";
        }
        
    }

}

function insertPelanggan($conn, $id_langganan, $tanggal) {
    $query = "SELECT 
                p.id_user,
                p.nama_pelanggan,
                p.alamat_pelanggan,
                p.wa_pelanggan,
                p.id_langganan,
                p.paket_internet
            FROM psb p
            JOIN report_pemasangan r ON r.id_langganan = p.id_langganan
            WHERE r.status = 'selesai' AND p.id_langganan = '$id_langganan'";
    $result=$conn->query($query);  
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $id_user = $data['id_user'];

        $cek = $conn->query("SELECT id FROM pelanggan WHERE id_user = '$id_user'");
        if ($cek->num_rows == 0) {
            $nama    = $data['nama_pelanggan'];
            $alamat  = $data['alamat_pelanggan'];
            $no_wa   = $data['wa_pelanggan'];
            $paket   = $data['paket_internet'];
            
            $date = date_create($tanggal);
            $tgl = date_format($date, 'd');
            $bln = date_format($date, 'm');

            $username = strtolower($data['nama_pelanggan']);
            $password = $tgl . $bln;

            $conn->query("INSERT INTO pelanggan 
                (id_user, id_langganan, nama_pelanggan, alamat_pelanggan, wa_pelanggan, jenis_layanan, status_pelanggan, username, password)
                VALUES 
                ('$id_user', '$id_langganan', '$nama', '$alamat', '$no_wa', '$paket', 'AKTIF', '$username', '$password')");
        }
    }
}

function generateIdLogin($conn, $id_langganan, $tanggal) {
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report Pemasangan Baru</title>
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
                            <h3 class="card-title">Report Pemasangan Baru</h3>
                        </div>
                        <form action="report-pemasangan.php?id=<?= $result['id'] ?>" method="POST"
                            enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="langganan">ID Berlangganan</label>
                                    <input type="text" class="form-control" name="id_langganan"
                                        value="<?= $result['id_langganan'] ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="no_wo">No Working Order</label>
                                    <input type="text" class="form-control" name="no_wo" value="<?= $result['id'] ?>"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label for="nama_pelanggan">Nama Pelanggan</label>
                                    <input type="text" class="form-control" value="<?= $result['nama_pelanggan'] ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat Pelanggan</label>
                                    <input type="text" class="form-control" value="<?= $result['alamat_pelanggan'] ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="wa_pelanggan">No Whatsapp Pelanggan</label>
                                    <input type="text" class="form-control" value="<?= $result['wa_pelanggan'] ?>" disabled>
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
                                    <label for="ktp">Foto ODP</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="foto_odp" accept="img/*">
                                            <label class="custom-file-label" for="exampleInputFile"></label>
                                        </div>
                                    </div>

                                    <label for="ktp">Foto Redaman</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="foto_redaman"
                                                accept="img/*">
                                            <label class="custom-file-label" for="exampleInputFile"></label>
                                        </div>
                                    </div>

                                    <label for="ktp">Foto SN Modem</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="foto_modem"
                                                accept="img/*">
                                            <label class="custom-file-label" for="exampleInputFile"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success" name="btn_submit">Submit</button>
                                <a href="wo_pemasangan.php" type="submit" class="btn btn-danger"
                                    name="btn_cancel">Cancel</a>
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
        $(function () {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>