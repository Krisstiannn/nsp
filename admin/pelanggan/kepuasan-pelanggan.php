<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');

// ==========================
// DATA GRAFIK (JUMLAH BINTANG)
// ==========================
$data_rating = [1=>0,2=>0,3=>0,4=>0,5=>0];

$queryGrafik = "
    SELECT rating, COUNT(*) as jumlah
    FROM kepuasan_pelanggan
    WHERE YEAR(tanggal) = '$tahun'
    AND MONTH(tanggal) = '$bulan'
    GROUP BY rating
";

$resultGrafik = mysqli_query($conn, $queryGrafik);

while($row = mysqli_fetch_assoc($resultGrafik)){
    $data_rating[$row['rating']] = (int)$row['jumlah'];
}

// ==========================
// DATA KOMENTAR
// ==========================
$queryKomentar = "
    SELECT rating, komentar, tanggal
    FROM kepuasan_pelanggan
    WHERE YEAR(tanggal) = '$tahun'
    AND MONTH(tanggal) = '$bulan'
    ORDER BY id_kepuasan DESC
";

$resultKomentar = mysqli_query($conn, $queryKomentar);

if(!$resultKomentar){
    die("Query Error: " . mysqli_error($conn));
}
$no = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Kepuasan Pelanggan</title>

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

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php include "/xampp/htdocs/nsp/layouts/header.php" ?>
<?php include "/xampp/htdocs/nsp/layouts/sidebar.php" ?>

<div class="content-wrapper">

<section class="content-header">
    <div class="container-fluid">
        <h1>Analisis Kepuasan Pelanggan</h1>
    </div>
</section>

<section class="content">
<div class="container-fluid">

<!-- FILTER -->
<div class="card">
    <div class="card-header bg-gradient-info">
        <h3 class="card-title">Filter Tahun & Bulan</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="d-flex gap-2">
    
    <select name="tahun" class="form-control w-25" onchange="this.form.submit()">
        <?php
        for($i = date('Y'); $i >= 2020; $i--){
            $selected = ($tahun == $i) ? 'selected' : '';
            echo "<option value='$i' $selected>$i</option>";
        }
        ?>
    </select>

    <select name="bulan" class="form-control w-25" onchange="this.form.submit()">
        <?php
        $bulanList = [
                        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
                        5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
                        9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                    ];

        foreach($bulanList as $key=>$val){
            $selected = ($bulan == $key) ? 'selected' : '';
            echo "<option value='$key' $selected>$val</option>";
        }
        ?>
    </select>

</form>
    </div>
</div>

<!-- CHART -->
<div class="card">
    <div class="card-header bg-gradient-primary">
        <h3 class="card-title">Grafik Kepuasan Pelanggan</h3>
    </div>

    <div class="card-body">
        <canvas id="chartKepuasan" style="height:350px;"></canvas>
    </div>
</div>

<!-- TABEL KOMENTAR -->
<div class="card">
    <div class="card-header bg-warning">
        <h3 class="card-title">Komentar Pelanggan</h3>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>

            <?php while($row = mysqli_fetch_assoc($resultKomentar)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>⭐ <?= $row['rating'] ?></td>
                    <td><?= $row['komentar'] ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                </tr>
            <?php endwhile; ?>

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
var ctx = document.getElementById('chartKepuasan');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['⭐ 1','⭐ 2','⭐ 3','⭐ 4','⭐ 5'],
        datasets: [{
            label: 'Jumlah Pelanggan',
            data: <?= json_encode(array_values($data_rating)); ?>,
            backgroundColor: [
                '#dc3545',
                '#fd7e14',
                '#ffc107',
                '#17a2b8',
                '#28a745'
            ],

            categoryPercentage: 0.5,
            barPercentage: 0.6,
            maxBarThickness: 50
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        },

        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>

</body>
</html>