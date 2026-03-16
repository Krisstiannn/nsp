<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$previewData = [];
$selectedNota = "";

$queryNota = mysqli_query($conn,"
SELECT 
    n.id_nota,
    d.no_nota,
    d.tanggal_masuk
FROM nota n
JOIN detail_nota d ON n.id_nota = d.id_nota
WHERE n.status_restok = 'belum'
GROUP BY n.id_nota
ORDER BY n.id_nota DESC
");


if(isset($_POST['btn_preview'])) {
    $selectedNota = $_POST['id_nota'];

    $qPreview = mysqli_query($conn, "SELECT * FROM detail_nota WHERE id_nota = '$selectedNota'");

    while($row = mysqli_fetch_assoc($qPreview)) {
        $previewData[] = $row;
    }
}

if(isset($_POST['btn_submit'])) {
    $id_nota = $_POST['id_nota'];
    $conn->begin_transaction();

    try {
        $qDetail = mysqli_query($conn, "SELECT * FROM detail_nota WHERE id_nota = '$id_nota'");

        while ($row = mysqli_fetch_assoc($qDetail)) {
            $kode  = $row['kode_barang'];
            $nama = $row['nama_barang'];
            $jumlah = $row['jumlah_barang'];

            $cek = mysqli_query($conn, "SELECT * FROM material WHERE kode_barang = '$kode'");

            if(mysqli_num_rows($cek) > 0) {
                mysqli_query($conn, "UPDATE material SET stok_barang = stok_barang + $jumlah WHERE kode_barang = '$kode'");
            } else {
                mysqli_query($conn, "INSERT INTO material (kode_barang, nama_barang, stok_barang) VALUES ('$kode', '$nama', '$jumlah')");
            }
        }

        mysqli_query($conn, "UPDATE nota SET status_restok = 'sudah', tanggal_restok = NOW() WHERE id_nota = '$id_nota'");


        $conn->commit();

        echo "<script>
                alert('Restok berhasil dilakukan');
                window.location='material.php';
            </script>";
    } catch (Exception $e) {
        $conn->rollback();

        echo "<script>
                alert('Terjadi kesalahan saat restok');
                window.location='restok-massal.php';
            </script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restok Massal</title>
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
                            <h1>Restok Data Massal</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Restok Massal dari Nota</h3>
                        </div>
                        <form action="restok-massal.php" method="POST">
                            <div class="form-group">
                                <label for="no_nota">Pilih Nomor Nota</label>
                                <select name="id_nota" class="form-control" required>
                                    <option value="">-- Pilih Nota --</option>
                                    <?php while($n = mysqli_fetch_assoc($queryNota)) { ?>
                                        <option value="<?= $n['id_nota']?>" <?= $selectedNota == $n['id_nota'] ? 'selected' : '' ?>>
                                            <?= $n['no_nota']?> | <?= date('d-m-Y', strtotime($n['tanggal_masuk']))?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="submit" name="btn_preview" class="btn btn-info">
                                <i class="fas fa-search"></i>Preview Barang
                            </button>
                        </form>
                    </div>

                    <?php if(!empty($previewData)) {?>
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Preview Barang Nota</h3>
                            </div>

                            <div class="card-body">
                                <form action="" method="POST">
                                    <input type="hidden" name="id_nota" value="<?= $selectedNota?>">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Kode Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach($previewData as $p) {?> 
                                                    <tr>
                                                        <td><?= $p['kode_barang']?></td>
                                                        <td><?= $p['nama_barang']?></td>
                                                        <td><?= $p['jumlah_barang']?></td>
                                                    </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="submit" name="btn_submit" class="btn btn-success">
                                        <i class="fas fa-box"></i>Submit Restok
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php }?>
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