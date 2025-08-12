<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";

$id_user = $_SESSION['id_users'];
$nama_pelanggan = $_SESSION['nama_pelanggan'];

$ambilId = $conn->query("SELECT * FROM pelanggan WHERE id_user = '$id_user'")->fetch_assoc();
$id_langganan = $ambilId['id_langganan'];

$queryRequest = "SELECT * FROM request WHERE id_pelanggan = '$id_langganan' ORDER BY id_request DESC";
$result = $conn->query($queryRequest);

$gantiId = $conn->query("SELECT * FROM idLogin WHERE id_langganan = '$id_langganan'");
$upDown = $conn->query("SELECT* FROM updown_paket WHERE id_pelanggan = '$id_langganan'");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/nsp/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/nsp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="/nsp/dist/css/adminlte.min.css">
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php include "/xampp/htdocs/nsp/layouts/navbar.php" ?>
        <div class="content-wrapper">
            <div class="content">
                <div class="container">
                    <section class="content-header">
                        <h1 class="m-0">Status Tiket Request Anda</h1>
                    </section>

                    <section class="content">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Riwayat Tiket Request</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID Langganan</th>
                                            <th>Jenis Request</th>
                                            <th>Isi Request</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row['id_pelanggan'] ?></td>
                                            <td><?= $row['jenis_request'] ?></td>
                                            <td>
                                                <?php if ($row['jenis_request'] == 'GANTI ID LOGIN'): ?>
                                                    <?php
                                                        $dataLogin = $conn->query("SELECT * FROM idLogin WHERE id_langganan = '{$row['id_pelanggan']}' ORDER BY id  DESC LIMIT 1")->fetch_assoc();
                                                        if ($dataLogin):
                                                    ?>
                                                        USERNAME DAN PASSWORD TERBARU : <br>
                                                        Username = <?= htmlspecialchars($dataLogin['username']) ?><br>
                                                        Password = <?= htmlspecialchars($dataLogin['password']) ?>
                                                    <?php else: ?>
                                                        Data belum tersedia.
                                                    <?php endif; ?>
                                                <?php elseif ($row['jenis_request'] == 'UP DOWN PAKET'): ?>
                                                    <?php
                                                        $dataPaket = $conn->query("SELECT * FROM updown_paket WHERE id_pelanggan = '{$row['id_pelanggan']}' ORDER BY id DESC LIMIT 1")->fetch_assoc();
                                                        if ($dataPaket):
                                                    ?>
                                                        Paket Terbaru = <?= htmlspecialchars($dataPaket['paket_terbaru']) ?>
                                                    <?php else: ?>
                                                        Data belum tersedia.
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <?= htmlspecialchars($row['isi_request']) ?>
                                                <?php endif; ?>
                                            </td>
                                            <?php 
                                                $data = $conn->query("SELECT status FROM request WHERE id_request = '{$row['id_request']}' AND id_pelanggan = '$id_langganan'")->fetch_assoc();
                                                $status = "";

                                                if($data['status'] == 'DITERIMA') {
                                                    $status = '<span class="badge badge-success">SETUJU</span>';
                                                } elseif($data['status'] == 'DITOLAK') {
                                                    $status = '<span class="badge badge-danger">DI TOLAK</span>';
                                                } else {
                                                    $status = '<span class="badge badge-warning">PROSES</span>';
                                                }
                                            
                                            ?>
                                            <td><?= $status ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-danger">Belum ada request yang
                                                diajukan.</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div class="card bg-light p-3">
            <div class="text-center">
                <h5 class="font-weight-bold text-primary">For more information, please contact here</h5>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <i class="fas fa-phone-alt text-danger mr-2"></i>
                <span class="mr-1">WhatsApp:</span>
                <a href="https://wa.me/6281234567890" class="text-primary">0812-3456-7890</a>
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
    <script src="/nsp/plugins/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="/nsp/plugins/jquery-mapael/maps/usa_states.min.js"></script>
    <script src="/nsp/plugins/chart.js/Chart.min.js"></script>
    <script src="/nsp/dist/js/demo.js"></script>
    <script src="/nsp/dist/js/pages/dashboard2.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>