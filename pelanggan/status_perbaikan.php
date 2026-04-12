<?php
session_start();
include "/xampp/htdocs/nsp/services/koneksi.php";
$id_user = $_SESSION['id_users'];
$nama_pelanggan = $_SESSION['nama_pelanggan'];

$queryPelanggan = "SELECT * FROM perbaikan WHERE id_user = '$id_user' ORDER BY id_perbaikan DESC";
$resultPelanggan = $conn->query($queryPelanggan);

$id_pelanggan = $resultPelanggan->fetch_assoc();
$id_pelanggan = $id_pelanggan['id_berlangganan'];

$showKepuasan = false;
$id_tiket_kepuasan = '';

if(isset($_GET['step']) && $_GET['step'] == 'kepuasan'){
    $showKepuasan = true;
    $id_tiket_kepuasan = $_GET['id'];
}

if(isset($_POST['submit_rating'])){

    $id_teknisi = $_POST['id_teknisi'];
    $id_tiket = $_POST['id_tiket'];
    $rating = $_POST['rating'];
    $komentar = $_POST['komentar'];

    $cek = $conn->query("
        SELECT id_rating
        FROM rating_teknisi 
        WHERE id_wo='$id_tiket'
        ");


    if($cek->num_rows > 0){
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }


    $insert = "
    INSERT INTO rating_teknisi 
    (id_wo,id_teknisi,id_langganan,rating,komentar)
    VALUES
    ('$id_tiket','$id_teknisi','$id_pelanggan','$rating','$komentar')";

    if($conn->query($insert)){
        header("Location: ".$_SERVER['PHP_SELF']."?step=kepuasan&id=".$id_tiket);
        exit;
    }else {

        echo "<script>alert('Gagal menyimpan rating');</script>";

    }

}

if(isset($_POST['submit_kepuasan'])){

    $id_tiket = $_POST['id_tiket'];
    $rating = $_POST['rating_kepuasan'];
    $komentar = $_POST['komentar_kepuasan'];

    $cek = $conn->query("
        SELECT id_wo FROM kepuasan_pelanggan WHERE id_wo='$id_tiket'
    ");

    if($cek->num_rows == 0){

        $insert = "
        INSERT INTO kepuasan_pelanggan
        (id_wo,id_pelanggan,rating,komentar)
        VALUES
        ('$id_tiket','$id_pelanggan','$rating','$komentar')
        ";

        $conn->query($insert);
    }

    header("Location: ".$_SERVER['PHP_SELF']."?done=1");
    exit;
}

if(isset($_GET['rating']) && $_GET['rating']=="success"){
echo "<script>alert('Terima kasih atas penilaian Anda');</script>";
}

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
                        <h1 class="m-0">Status Tiket Perbaikan Anda</h1>
                    </section>

                    <section class="content">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Riwayat Tiket Perbaikan</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Langganan</th>
                                            <th>Keluhan</th>
                                            <th>Teknisi</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no = 1;
                                            while ($dataPelanggan = $resultPelanggan->fetch_assoc()) {
                                                $id_langganan = $dataPelanggan['id_berlangganan'];
                                                $keluhan = $dataPelanggan['keluhan'];
                                                $id_perbaikan = $dataPelanggan['id_perbaikan'];

                                                $teknisi = '-';
                                                $id_teknisi = 0;
                                                $status = 'Tiket Sudah Diberikan Ke Teknisi';

                                                $queryWO = "SELECT * FROM wo WHERE id_perbaikan = '$id_perbaikan'";
                                                $resultWO = $conn->query($queryWO);

                                                if ($resultWO->num_rows > 0) {
                                                    $dataWO = $resultWO->fetch_assoc();
                                                    $id_teknisi = $dataWO['id_karyawan'];

                                                    $queryTeknisi = "SELECT nama_karyawan FROM karyawan WHERE id = '$id_teknisi'";
                                                    $resultTeknisi = $conn->query($queryTeknisi);
                                                    if ($resultTeknisi->num_rows > 0) {
                                                    $teknisi = $resultTeknisi->fetch_assoc()['nama_karyawan'];
                                                    }
                                                }

                                                $queryReport = "SELECT status FROM report_perbaikan WHERE no_wo = '$id_perbaikan' ORDER BY id DESC LIMIT 1";
                                                $resultReport = $conn->query($queryReport);
                                                if ($resultReport->num_rows > 0) {
                                                    $status = $resultReport->fetch_assoc()['status'];
                                                } 

                                                $ratingButton = "-";

                                                if ($status == "SELESAI") {

                                                    $cekRating = $conn->query("
                                                                SELECT *
                                                                FROM rating_teknisi 
                                                                WHERE id_wo='$id_perbaikan'
                                                                ");


                                                    if ($cekRating && $cekRating->num_rows == 0) {

                                                        $ratingButton = "
                                                        <button class='btn btn-sm btn-success'
                                                        data-toggle='modal'
                                                        data-target='#ratingModal$id_perbaikan'>
                                                        Beri Rating
                                                        </button>";

                                                    } else {

                                                        $ratingButton = "<span class='badge badge-success'>Sudah Dinilai</span>";

                                                    }
                                                }

                                                echo "<tr>
                                                        <td>$no</td>
                                                        <td>$id_langganan</td>
                                                        <td>$keluhan</td>
                                                        <td>$teknisi</td>
                                                        <td>$status</td>
                                                        <td>$ratingButton</td>
                                                    </tr>";

                                                if ($status == "SELESAI") {
                                                    echo "

                                                    <div class='modal fade' id='ratingModal$id_perbaikan'>
                                                    <div class='modal-dialog'>
                                                    <div class='modal-content'>

                                                    <form method='POST'>

                                                    <div class='modal-header'>
                                                    <h5 class='modal-title'>Rating Teknisi</h5>
                                                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                    </div>

                                                    <div class='modal-body'>

                                                    <input type='hidden' name='id_teknisi' value='$id_teknisi'>
                                                    <input type='hidden' name='id_tiket' value='$id_perbaikan'>

                                                    <label>Rating</label>
                                                    <select name='rating' class='form-control' required>
                                                    <option value=''>Pilih</option>
                                                    <option value='5'>⭐⭐⭐⭐⭐ Sangat Puas</option>
                                                    <option value='4'>⭐⭐⭐⭐ Puas</option>
                                                    <option value='3'>⭐⭐⭐ Cukup</option>
                                                    <option value='2'>⭐⭐ Kurang</option>
                                                    <option value='1'>⭐ Buruk</option>
                                                    </select>

                                                    <label class='mt-2'>Komentar</label>
                                                    <textarea name='komentar' class='form-control'></textarea>

                                                    </div>

                                                    <div class='modal-footer'>
                                                    <button type='submit' name='submit_rating' class='btn btn-primary'>
                                                    Kirim Rating
                                                    </button>
                                                    </div>

                                                    </form>

                                                    </div>
                                                    </div>
                                                    </div>

                                                    ";
                                                }

                                                $no++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="modal fade" id="kepuasanModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <form method="POST">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Kepuasan Layanan</h5>
                                            </div>

                                            <div class="modal-body">

                                                <input type="hidden" name="id_tiket"
                                                    value="<?php echo $id_tiket_kepuasan ?>">

                                                <label>Seberapa puas Anda?</label>
                                                <select name="rating_kepuasan" class="form-control" required>
                                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Puas</option>
                                                    <option value="4">⭐⭐⭐⭐ Puas</option>
                                                    <option value="3">⭐⭐⭐ Cukup</option>
                                                    <option value="2">⭐⭐ Kurang</option>
                                                    <option value="1">⭐ Buruk</option>
                                                </select>

                                                <label class="mt-2">Komentar</label>
                                                <textarea name="komentar_kepuasan" class="form-control"></textarea>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" name="submit_kepuasan" class="btn btn-primary">
                                                    Kirim
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
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
    <script>
        $(document).ready(function () {

            <
            ? php
            if ($showKepuasan): ? >
                $('#kepuasanModal').modal('show'); <
            ? php endif; ? >

        });
    </script>
</body>

</html>