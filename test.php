<?php

/* ===============================
DUMMY DATA
================================ */

$rata_rating = 4.2;
$total_feedback = 128;

$bulan = [
"Jan","Feb","Mar","Apr","Mei","Jun",
"Jul","Agu","Sep","Okt","Nov","Des"
];

$tren_rating = [4.1,4.0,4.3,4.2,4.5,4.4,4.3,4.6,4.4,4.2,4.3,4.5];

$distribusi = [
1 => 5,
2 => 8,
3 => 20,
4 => 55,
5 => 40
];

$teknisi = [
["nama"=>"Andi","rating"=>4.5,"jumlah"=>40],
["nama"=>"Budi","rating"=>4.2,"jumlah"=>35],
["nama"=>"Rudi","rating"=>3.9,"jumlah"=>28],
["nama"=>"Joko","rating"=>4.6,"jumlah"=>50]
];

$feedback = [
["rating"=>5,"komentar"=>"Teknisi sangat cepat","tanggal"=>"2026-03-01"],
["rating"=>4,"komentar"=>"Pelayanan bagus","tanggal"=>"2026-03-02"],
["rating"=>3,"komentar"=>"Lumayan tapi agak lambat","tanggal"=>"2026-03-03"],
["rating"=>5,"komentar"=>"Internet stabil","tanggal"=>"2026-03-04"],
["rating"=>2,"komentar"=>"Perbaikan lama","tanggal"=>"2026-03-05"]
];

?>

<!DOCTYPE html>
<html>
<head>

<title>Dashboard Analisis Kepuasan</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
font-family:Arial;
background:#f4f6f9;
margin:20px;
}

.card{
background:white;
padding:20px;
margin-bottom:20px;
border-radius:10px;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

.grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
}

.summary{
display:flex;
gap:20px;
}

.box{
flex:1;
background:#ffffff;
padding:20px;
border-radius:10px;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
}

table th,td{
padding:10px;
border:1px solid #ddd;
text-align:left;
}

</style>

</head>

<body>

<h2>📊 Dashboard Analisis Kepuasan Pelanggan</h2>

<!-- SUMMARY -->
<div class="summary">

<div class="box">
<h3>⭐ Rata-rata Rating</h3>
<h1><?php echo $rata_rating ?></h1>
</div>

<div class="box">
<h3>💬 Total Feedback</h3>
<h1><?php echo $total_feedback ?></h1>
</div>

</div>

<!-- GRAFIK -->
<div class="grid">

<div class="card">
<h3>📈 Tren Kepuasan Bulanan</h3>
<canvas id="trendChart"></canvas>
</div>

<div class="card">
<h3>📊 Distribusi Rating</h3>
<canvas id="distChart"></canvas>
</div>

</div>

<!-- TEKNISI -->
<div class="card">

<h3>👨‍🔧 Performa Teknisi</h3>

<table>

<tr>
<th>Nama Teknisi</th>
<th>Rata-rata Rating</th>
<th>Jumlah Penilaian</th>
</tr>

<?php foreach($teknisi as $t){ ?>

<tr>
<td><?php echo $t['nama'] ?></td>
<td><?php echo $t['rating'] ?></td>
<td><?php echo $t['jumlah'] ?></td>
</tr>

<?php } ?>

</table>

</div>

<!-- FEEDBACK -->
<div class="card">

<h3>💬 Feedback Pelanggan</h3>

<table>

<tr>
<th>Rating</th>
<th>Komentar</th>
<th>Tanggal</th>
</tr>

<?php foreach($feedback as $f){ ?>

<tr>
<td><?php echo $f['rating'] ?></td>
<td><?php echo $f['komentar'] ?></td>
<td><?php echo $f['tanggal'] ?></td>
</tr>

<?php } ?>

</table>

</div>

<script>

/* TREN BULANAN */

new Chart(document.getElementById('trendChart'),{
type:'bar',
data:{
labels:<?php echo json_encode($bulan) ?>,
datasets:[{
label:'Rating',
data:<?php echo json_encode($tren_rating) ?>,
backgroundColor:'blue'
}]
}
});


/* DISTRIBUSI */

new Chart(document.getElementById('distChart'),{
type:'bar',
data:{
labels:['1','2','3','4','5'],
datasets:[{
label:'Jumlah',
data:[
<?php echo $distribusi[1] ?>,
<?php echo $distribusi[2] ?>,
<?php echo $distribusi[3] ?>,
<?php echo $distribusi[4] ?>,
<?php echo $distribusi[5] ?>
],
backgroundColor:'green'
}]
}
});

</script>

</body>
</html>