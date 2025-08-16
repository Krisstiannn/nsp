<?php
session_start();
if (!isset($_SESSION['id_users']) || ($_SESSION['peran'] ?? '') !== 'pelanggan') {
    header("Location: /nsp/login.php"); exit;
}
include "/xampp/htdocs/nsp/services/koneksi.php";
date_default_timezone_set('Asia/Makassar');

$idUser = (int)$_SESSION['id_users'];

$sql = $conn->prepare("
    SELECT 
      pb.tanggal_pembayaran,
      pb.id_langganan,
      pel.nama_pelanggan,
      psb.paket_internet AS jenis_layanan,
      pb.jumlah_tagihan,
      pb.status_pembayaran
    FROM pembayaran pb
    JOIN pelanggan pel ON pel.id_langganan = pb.id_langganan
    LEFT JOIN psb ON psb.id_langganan = pb.id_langganan
    WHERE pel.id_user = ? AND pb.status_pembayaran = 'SUDAH BAYAR'
    ORDER BY pb.tanggal_pembayaran DESC, pb.bulan_tagihan DESC
");
$sql->bind_param("i", $idUser);
$sql->execute();
$result = $sql->get_result();

function rupiah($n){ return 'Rp. ' . number_format((int)$n,0,',','.'); }
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
            <section class="content">
                <div class="container py-4">
                    <h3>History Pembayaran</h3>
                    <?php if (isset($_GET['ok'])): ?>
                    <div class="alert alert-success">Pembayaran berhasil. Status sudah tercatat sebagai <b>SUDAH
                            BAYAR</b>.</div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">History Tagihan Bulanan</div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0 text-center">
                                <thead>
                                    <tr>
                                        <th>Tanggal Pembayaran</th>
                                        <th>ID Langganan</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Jenis Layanan</th>
                                        <th>Tagihan Bulanan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= date('d - m - Y', strtotime($row['tanggal_pembayaran'])) ?></td>
                                        <td><?= htmlspecialchars($row['id_langganan']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                                        <td><?= htmlspecialchars($row['jenis_layanan'] ?? '-') ?></td>
                                        <td><?= rupiah($row['jumlah_tagihan']) ?></td>
                                        <td class="text-success font-weight-bold">SUDAH BAYAR</td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </section>
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