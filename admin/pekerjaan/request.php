<?php
include "/xampp/htdocs/nsp/services/koneksi.php";
$data_pelanggan = $conn->query("SELECT * FROM pelanggan")->fetch_assoc();
$id_langganan = $data_pelanggan['id_langganan'];

$ambil = "SELECT * FROM request";
$hasil_data = $conn->query($ambil);

if(isset($_POST['setuju']) || isset($_POST['tolak'])) {
    $id_request = $_POST['id_request'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $aksi = isset($_POST['setuju']) ? 'setuju' : 'tolak';
    
    $data_request = $conn->query("SELECT * FROM request WHERE id_pelanggan = '$id_pelanggan' AND id_request = '$id_request'")->fetch_assoc();
    if ($data_request) {
        if($aksi == 'setuju') {
            if($data_request['jenis_request'] == 'GANTI ID LOGIN') {
                $login_pelanggan = $conn->query("SELECT * FROM idLogin WHERE id_langganan = '{$data_request['id_pelanggan']}' ORDER BY id DESC LIMIT 1")->fetch_assoc();
                if($login_pelanggan) {
                    $username = $login_pelanggan['username'];
                    $password = $login_pelanggan['password'];

                    $update_login = "UPDATE pelanggan SET username = '$username', password = '$password' WHERE id_langganan = '{$data_request['id_pelanggan']}'";
                    $update = $conn->query($update_login);

                    if($update) {
                        echo "<script type= 'text/javascript'>
                                alert('ID Login Berhasi Diupdate!');
                                document.location.href = 'request.php';
                            </script>";
                    } else {
                        echo "<script type= 'text/javascript'>
                                alert('ID Login Gagal Diupdate!');
                                document.location.href = 'request.php';
                            </script>";
                    }   
                }
            } elseif ($data_request['jenis_request'] == 'UP DOWN PAKET') {
                $paket_pelanggan = $conn->query("SELECT * FROM updown_paket WHERE id_pelanggan = '{$data_request['id_pelanggan']}' ORDER BY id DESC LIMIT 1")->fetch_assoc();
                if($paket_pelanggan) {
                    $paket_terbaru = $paket_pelanggan['paket_terbaru'];

                    $update_paket = "UPDATE pelanggan SET jenis_layanan = '$paket_terbaru' WHERE id_langganan = '{$data_request['id_pelanggan']}'";
                    $update = $conn->query($update_paket);

                    if($update) {
                        echo "<script type= 'text/javascript'>
                                alert('Paket Layanan Pelanggan Berhasi Diupdate!');
                                document.location.href = 'request.php';
                            </script>";
                    } else {
                        echo "<script type= 'text/javascript'>
                                alert('Paket Layanan Pelanggan Gagal Diupdate!');
                                document.location.href = 'request.php';
                            </script>";
                    }
                }
            }
            $conn->query("UPDATE request SET status = 'DITERIMA' WHERE id_request = '$id_request' AND id_pelanggan = '$id_pelanggan'");
        } else if ($aksi == 'tolak') {
            $conn->query("UPDATE request SET status = 'DITOLAK' WHERE id_request = '$id_request' AND id_pelanggan = '$id_pelanggan'");
            echo "<script>alert('Request Ditolak.'); window.location.href='request.php';</script>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request Pelanggan</title>
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
                            <h1>Request Pelanggan</h1>
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
                                        <!-- <div class="card-title">
                                            <a href="tambah-psb.php" class="btn btn-sm btn-success ">Tambah
                                                Data</a>
                                        </div> -->

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
                                                    <th>Nama Pelanggan</th>
                                                    <th>NO Layanan Internet</th>
                                                    <th>Jenis Request</th>
                                                    <th>Isi Request</th>
                                                    <th>Konfirmasi</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row_data = $hasil_data->fetch_assoc()) : ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row_data['nama_pelanggan'])?></td>
                                                    <td><?= htmlspecialchars($row_data['id_pelanggan']) ?></td>
                                                    <td><?= htmlspecialchars($row_data['jenis_request']) ?></td>
                                                    <td>
                                                        <?php if ($row_data['jenis_request'] == 'GANTI ID LOGIN'): ?>
                                                        <?php
                                                            $dataLogin = $conn->query("SELECT * FROM idLogin WHERE id_langganan = '{$row_data['id_pelanggan']}' ORDER BY id DESC LIMIT 1")->fetch_assoc();
                                                            if ($dataLogin):
                                                        ?>
                                                        USERNAME DAN PASSWORD TERBARU : <br>
                                                        Username = <?= htmlspecialchars($dataLogin['username']) ?><br>
                                                        Password = <?= htmlspecialchars($dataLogin['password']) ?>
                                                        <?php else: ?>
                                                        Data belum tersedia.
                                                        <?php endif; ?>
                                                        <?php elseif ($row_data['jenis_request'] == 'UP DOWN PAKET'): ?>
                                                        <?php
                                                            $dataPaket = $conn->query("SELECT * FROM updown_paket WHERE id_pelanggan = '{$row_data['id_pelanggan']}' ORDER BY id DESC LIMIT 1")->fetch_assoc();
                                                            if ($dataPaket):
                                                        ?>
                                                        Paket Terbaru =
                                                        <?= htmlspecialchars($dataPaket['paket_terbaru']) ?>
                                                        <?php else: ?>
                                                        Data belum tersedia.
                                                        <?php endif; ?>
                                                        <?php else: ?>
                                                        <?= htmlspecialchars($row_data['isi_request']) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <form action="" method="POST" style="display: inline-block;">
                                                            <input type="hidden" name="id_pelanggan"
                                                                value="<?= $row_data['id_pelanggan']?>">
                                                            <input type="hidden" name="id_request"
                                                                value="<?= $row_data['id_request']?>">
                                                            <button type="submit" name="setuju"
                                                                class="btn btn-success btn-sm">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="" method="POST" style="display: inline-block;">
                                                            <input type="hidden" name="id_pelanggan"
                                                                value="<?= $row_data['id_pelanggan']?>">
                                                            <input type="hidden" name="id_request"
                                                                value="<?= $row_data['id_request']?>">
                                                            <button type="submit" name="tolak"
                                                                class="btn btn-danger btn-sm">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <?php
                                                        $data = $conn->query("SELECT status FROM request WHERE id_request = '{$row_data['id_request']}' AND id_pelanggan = '{$row_data['id_pelanggan']}'")->fetch_assoc();
                                                        $status = "";
                                                        if($data['status'] == 'DITERIMA') {
                                                            $status = '<span class="badge badge-success">SETUJU</span>';
                                                        } elseif($data['status'] == 'DITOLAK') {
                                                            $status = '<span class="badge badge-danger">DI TOLAK</span>';
                                                        } else {
                                                            $status = '<span class="badge badge-warning">PROSES</span>';
                                                        }

                                                    ?>
                                                    <td><?= $status?></td>
                                                </tr>
                                                <?php endwhile; ?>

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