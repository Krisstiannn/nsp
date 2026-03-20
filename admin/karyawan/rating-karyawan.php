<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$query = "
    SELECT 
        k.id,
        k.nama_karyawan,
        COALESCE(AVG(r.rating),0) AS rata_rating,
        COUNT(r.id_rating) AS jumlah_penilaian
    FROM karyawan k
    LEFT JOIN wo w ON k.id = w.id_karyawan
    LEFT JOIN rating_teknisi r 
        ON r.id_wo = w.id 
        AND YEAR(r.tanggal_rating) = '$tahun'
    WHERE k.posisi_karyawan = 'teknisi'
    GROUP BY k.id
";

$result = mysqli_query($conn, $query);

$data_nama = [];
$data_rating = [];
$data_jumlah = [];
$data_tabel = [];

while($row = mysqli_fetch_assoc($result)){
    $data_nama[] = $row['nama_karyawan'];
    $data_rating[] = (float)$row['rata_rating'];
    $data_jumlah[] = (int)$row['jumlah_penilaian'];

    $data_tabel[] = $row; // simpan untuk tabel
}
$no = 1;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Rating Teknisi</title>

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

<style>
#chartTeknisi {
    min-height: 300px;
}
</style>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php include "/xampp/htdocs/nsp/layouts/header.php" ?>
<?php include "/xampp/htdocs/nsp/layouts/sidebar.php" ?>

<div class="content-wrapper">

<section class="content-header">
    <div class="container-fluid">
        <h1>Penilaian Teknisi</h1>
    </div>
</section>

<section class="content">
<div class="container-fluid">

<!-- FILTER -->
<div class="card">
    <div class="card-header bg-gradient-info">
        <h3 class="card-title">Filter Tahun</h3>
    </div>
    <div class="card-body">
        <form method="GET">
            <select name="tahun" class="form-control w-25" onchange="this.form.submit()">
                <?php
                for($i = date('Y'); $i >= 2020; $i--){
                    $selected = ($tahun == $i) ? 'selected' : '';
                    echo "<option value='$i' $selected>$i</option>";
                }
                ?>
            </select>
        </form>
    </div>
</div>

<!-- CHART -->
<div class="card">
    <div class="card-header bg-gradient-primary">
        <h3 class="card-title">Grafik Rating Teknisi</h3>
    </div>
    <div style="height:300px;">
        <canvas id="chartTeknisi"></canvas>
    </div>
</div>

<!-- TABEL -->
<div class="card">
    <div class="card-header bg-gradient-success">
        <h3 class="card-title">Tabel Penilaian Teknisi</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered text-center">
            <thead class="bg-gradient-cyan">
                <tr>
                    <th>No</th>
                    <th>Nama Teknisi</th>
                    <th>Rata-rata Rating</th>
                    <th>Jumlah Penilaian</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach($data_tabel as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama_karyawan'] ?></td>
                    <td>
                        <span class="badge bg-success">
                            <?= round($row['rata_rating'],1) ?: 0 ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-info">
                            <?= $row['jumlah_penilaian'] ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>

</div>
</section>

</div>

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

<script>
var ctx = document.getElementById('chartTeknisi');

if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($data_nama); ?>,
            datasets: [
                {
                    label: 'Rata-rata Rating',
                    data: <?= json_encode($data_rating); ?>,
                    backgroundColor: 'rgba(0,123,255,0.7)'
                },
                {
                    label: 'Jumlah Penilaian',
                    data: <?= json_encode($data_jumlah); ?>,
                    backgroundColor: 'rgba(40,167,69,0.7)'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

console.log(<?= json_encode($data_nama); ?>);
console.log(<?= json_encode($data_rating); ?>);
console.log(<?= json_encode($data_jumlah); ?>);
</script>

</body>
</html>