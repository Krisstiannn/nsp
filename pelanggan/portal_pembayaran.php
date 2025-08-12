<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";

$id_user = $_SESSION['id_users'];
$nama_pelanggan = $_SESSION['nama_pelanggan'];
$idLang = $_SESSION['id_langganan'];

$q = $conn->prepare("
  SELECT id_pembayaran, id_langganan, bulan_tagihan, jumlah_tagihan, status_pembayaran
  FROM pembayaran
  WHERE id_langganan=? AND status_pembayaran = 'BELUM BAYAR'
  ORDER BY bulan_tagihan DESC
");
$q->bind_param("s", $idLang);
$q->execute();
$r = $q->get_result();

$namaBulan = [ '01'=>'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL','05'=>'MEI','06'=>'JUNI',
               '07'=>'JULI','08'=>'AGUSTUS','09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER' ];
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
                        <h1 class="m-0">Portal Pembayaran</h1>
                    </section>

                    <section class="content">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"></h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>ID Langganan</th>
                                            <th>Pembayaran Bulan -</th>
                                            <th>Tagihan Bulanan</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = $r->fetch_assoc()):
                                            $ymd = $row['bulan_tagihan']; // YYYY-MM-01
                                            $m   = date('m', strtotime($ymd));
                                            $blnText = $namaBulan[$m] ?? $ymd;
                                            $paid = ($row['status_pembayaran']==='SUDAH BAYAR');
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['id_langganan']) ?></td>
                                            <td><?= $blnText ?></td>
                                            <td>Rp. <?= number_format((int)$row['jumlah_tagihan'],0,',','.') ?></td>
                                            <td style="font-weight:bold; color:<?= $paid?'green':'red' ?>;">
                                                <?= $paid ? 'SUDAH BAYAR' : 'BELUM BAYAR' ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-success btn-sm"
                                                    href="pembayaran.php?id=<?= (int)$row['id_pembayaran'] ?>">DETAIL</a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
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