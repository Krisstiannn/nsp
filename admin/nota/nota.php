<?php 
include "/xampp/htdocs/nsp/services/koneksi.php"; 
$keyword = $_GET['table_search'] ?? ''; 
if ($keyword != '') { 
    $stmt = $conn->prepare("SELECT * FROM detail_nota WHERE no_nota LIKE ?"); 
    $like = "%$keyword%"; $stmt->bind_param("s", $like); 
    $stmt->execute(); 
    $result_tampilData = $stmt->get_result();
    } else { 
        $query_tampilData = "SELECT 
    n.id_nota,
    dn.no_nota,
    s.nama_supplier,
    s.nama_pic,
    GROUP_CONCAT(dn.nama_barang SEPARATOR '<br>') AS daftar_barang,
    GROUP_CONCAT(dn.jumlah_barang SEPARATOR '<br>') AS daftar_jumlah
FROM nota n
JOIN supplier s ON s.id_supplier = n.id_supplier
JOIN detail_nota dn ON dn.id_nota = n.id_nota
GROUP BY n.id_nota, dn.no_nota, s.nama_supplier, s.nama_pic
"; 
        $result_tampilData = $conn->query($query_tampilData); } ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota | Data Nota</title>
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
        <div class="content-wrapper bg-gradient-white">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Nota</h1>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Left col -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header border-transparent">
                                    <div class="card-header">
                                        <div class="card-title"> <a href="tambah-nota.php"
                                                class="btn btn-sm btn-success">Tambah Data</a> </div>
                                        <div class="card-title float-right">
                                            <form method="get" class="input-group input-group-sm" style="width: 200px;">
                                                <input type="text" name="table_search" class="form-control float-right"
                                                    placeholder="Cari No Nota"
                                                    value="<?= htmlspecialchars($keyword) ?>">
                                                <div class="input-group-append"> <button type="submit"
                                                        class="btn btn-default"> <i class="fas fa-search"></i> </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead class="bg-gradient-cyan">
                                                <tr>
                                                    <th>Nomor Nota</th>
                                                    <th>Nama Supplier</th>
                                                    <th>Nama Person in Charge(PIC)</th>
                                                    <th>Nama Barang</th>
                                                    <th>Jumlah Barang</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead> <?php foreach ($result_tampilData as $nota) { ?>
                                            <tbody>
                                                <tr>
                                                    <td><?= $nota['no_nota']?></td>
                                                    <td><?= $nota['nama_supplier'] ?></td>
                                                    <td><?= $nota['nama_pic'] ?></td>
                                                    <td><?= $nota['daftar_barang']?></td>
                                                    <td><?= $nota['daftar_jumlah']?></td>
                                                    <td> 
                                                        <a class="btn btn-info btn-xs"
                                                            href="edit-nota.php?id_nota=<?= $nota['id_nota'] ?>"> <i
                                                                class="fas fa-pencil-alt"> </i> Edit </a> 
                                                        <a class="btn btn-danger btn-xs"
                                                            href="hapus-nota.php?id_nota=<?= $nota['id_nota'] ?>"
                                                            onclick="return confirm('Yakin ingin menghapus nota ini?')">

                                                            <i class="fas fa-trash"></i> Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody> <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div> <!-- END Main Content -->
        <!-- Main Footer --> <?php include "/xampp/htdocs/nsp/layouts/footer.php" ?>
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