<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";

$id = $_SESSION['id_users'];
$ambil_data = "SELECT * FROM pelanggan WHERE id_user = '$id'";
$hasil_data = $conn->query($ambil_data)->fetch_assoc();

if(isset($_POST['btn_submit'])) {
    $nama = $_POST['nama_pelanggan'];
    $id_langganan = $_POST['id_langganan'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $insertReq = "INSERT INTO request (id_request, nama_pelanggan, id_pelanggan, jenis_request, status) VALUES ('', '$nama', '$id_langganan', 'GANTI ID LOGIN', 'PROSES')";
    $hasilInsert = $conn->query($insertReq);

    $insertId = "INSERT INTO idLogin (id, id_langganan, username, password) VALUES ('', '$id_langganan', '$username', '$password')";
    $hasilInsertId = $conn->query($insertId);

        if($hasilInsert->num_rows == 0 && $hasilInsertId->num_rows == 0) {
            echo "<script type= 'text/javascript'>
                    alert('Data Berhasil disimpan!');
                    document.location.href = 'status_request.php';
                </script>";
        } else {
            echo "<script type= 'text/javascript'>
                alert('Data Gagal disimpan!');
                document.location.href = 'ganti_id.php';
            </script>";
        }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
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
                    <div class="content-header">
                        <div class="container-fluid text-black">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <h1 class="m-0">Selamat Datang ... di Website Resmi Net Sun Power</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="content-fluid">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Ganti ID Login</h3>
                                        </div>
                                        <form action="" method="POST">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="id_langganan">ID Berlangganan</label>
                                                    <input type="hidden" class="form-control" name="id_langganan" value="<?= $hasil_data['id_langganan']?>">
                                                    <input type="text" class="form-control" name="id_langganan"
                                                        placeholder="ID Berlangganan" value="<?= $hasil_data['id_langganan']?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama_pelanggan">Nama Pelanggan</label>
                                                    <input type="hidden" class="form-control" value="<?= $hasil_data['nama_pelanggan']?>" name="nama_pelanggan">
                                                    <input type="text" class="form-control" name="nama_pelanggan"
                                                        placeholder="Nama Pelanggan" value="<?= $hasil_data['nama_pelanggan']?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input type="text" class="form-control" name="username"
                                                        placeholder="Username">
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="text" class="form-control" name="password"
                                                        placeholder="Password">
                                                </div> 
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-success"
                                                    name="btn_submit">Submit</button>
                                                <a href="dashboard.php" type="submit" class="btn btn-danger"
                                                    name="btn_cancel">Cancel</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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