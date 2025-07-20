<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$query_tampil = "SELECT * FROM psb";
$result_tampil = $conn->query($query_tampil);
$result_tampilkaryawan = $conn->query("SELECT * FROM karyawan WHERE posisi_karyawan = 'teknisi'");

if (isset($_POST['btn_kirim'])) {
    $id_psb = $_POST['id_psb'];
    $id_karyawan = $_POST['id_karyawan'];


    $cek_karyawan = $conn->query("SELECT * FROM karyawan WHERE id = '$id_karyawan'");
    $cek_pekerjaan = $conn->query("SELECT * FROM psb WHERE id = '$id_psb'");
    $cek = $conn->query("SELECT * FROM wo WHERE id_psb = '$id_psb'")->fetch_assoc();

    if ($cek > 0) {
        echo "<script>alert('Pekerjaan Sudah Di Kirimkan Ke Karyawan!'); window.location.href='psb.php';</script>";
        die();
    } else if ($cek_karyawan->num_rows > 0 && $cek_pekerjaan->num_rows > 0) {
        $query_insert = "INSERT INTO wo (id_karyawan, id_psb) VALUES ('$id_karyawan', '$id_psb')";
        if ($conn->query($query_insert)) {
            echo "<script>alert('Pekerjaan berhasil dikirim ke karyawan!'); window.location.href='psb.php';</script>";
        } else {
            echo "<script>alert('Gagal mengirim pekerjaan!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Data tidak valid!'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemasangan Baru</title>
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

        <!-- Main Content -->
        <div class="content-wrapper bg-gradient-white">

            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Pemasangan Baru</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Left col -->
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header border-transparent">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <a href="tambah-psb.php" class="btn btn-sm btn-success ">Tambah
                                                Data</a>
                                        </div>

                                        <div class="card-title float-right">
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" name="table_search" class="form-control float-right"
                                                    placeholder="Search">

                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-default">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead class="bg-gradient-cyan">
                                                <tr>
                                                    <th>ID Langganan</th>
                                                    <th>Nama Pelanggan</th>
                                                    <th>NO WA</th>
                                                    <th>Alamat atau Titik Kordinat</th>
                                                    <th>Foto Rumah</th>
                                                    <th>Foto KTP</th>
                                                    <th>Paket Internet</th>
                                                    <th>Teknisi Yang Mengerjakan</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($result_tampil as $psb) { ?>
                                                <tr>
                                                    <td><?= $psb['id_langganan']?></td>
                                                    <td><?= $psb['nama_pelanggan'] ?></td>
                                                    <td><?= $psb['wa_pelanggan'] ?></td>
                                                    <td><?= $psb['alamat_pelanggan'] ?></td>
                                                    <td><img src="/nsp/storage/img/<?= $psb['rumah_pelanggan'] ?>"
                                                            alt="<?= $psb['rumah_pelanggan'] ?>" style="width: 100px;">
                                                    </td>
                                                    <td><img src="/nsp/storage/img/<?= $psb['ktp_pelanggan'] ?>"
                                                            alt="<?= $psb['ktp_pelanggan'] ?>" style="width: 100px;">
                                                    </td>
                                                    <td><?= $psb['paket_internet'] ?></td>
                                                    <td>
                                                        <form action="psb.php" method="POST">
                                                            <input type="hidden" name="id_psb"
                                                                value="<?= $psb['id'] ?>">
                                                            <select name="id_karyawan">
                                                                <?php foreach ($result_tampilkaryawan as $karyawan) { ?>
                                                                <option value="<?= $karyawan['id'] ?>">
                                                                    <?= $karyawan['nama_karyawan'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <button type="submit" name="btn_kirim"
                                                                class="btn btn-warning btn-sm">Kirim</button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-info btn-sm"
                                                            href="edit-psb.php?id=<?= $psb['id'] ?>">
                                                            <i class="fas fa-pencil-alt">
                                                            </i>
                                                            Edit
                                                        </a>
                                                        <a class="btn btn-danger btn-sm"
                                                            href="hapus-psb.php?id=<?= $psb['id'] ?>">
                                                            <i class="fas fa-trash">
                                                            </i>
                                                            Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
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
</body>

</html>