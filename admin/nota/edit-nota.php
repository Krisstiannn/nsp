<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$id_nota = $_GET['id_nota'] ?? 0;

/* GENERATE KODE BARANG */
function generateKodeBarang($conn){

    $q1=mysqli_query($conn,"SELECT MAX(kode_barang) AS max_code FROM material");
    $d1=mysqli_fetch_assoc($q1);

    $q2=mysqli_query($conn,"SELECT MAX(kode_barang) AS max_code FROM detail_nota");
    $d2=mysqli_fetch_assoc($q2);

    $kode_terakhir=max($d1['max_code'],$d2['max_code']);

    if($kode_terakhir){
        $urutan=(int)substr($kode_terakhir,8);
        $urutan++;
    }else{
        $urutan=2001;
    }

    return "NSP-MTR-".$urutan;
}

/* ENDPOINT AJAX GENERATE */
if(isset($_GET['generate_kode'])){
echo generateKodeBarang($conn);
exit;
}

/* AMBIL DATA NOTA */
$qNota="
SELECT n.*,dn.no_nota,dn.tanggal_masuk
FROM nota n
JOIN detail_nota dn ON dn.id_nota=n.id_nota
WHERE n.id_nota='$id_nota'
LIMIT 1
";

$dataNota=mysqli_fetch_assoc(mysqli_query($conn,$qNota));

/* AMBIL DETAIL BARANG */
$qDetail="SELECT * FROM detail_nota WHERE id_nota='$id_nota'";
$resultDetail=mysqli_query($conn,$qDetail);

/* SUPPLIER */
$resultSupplier=mysqli_query($conn,"SELECT * FROM supplier");

/* MATERIAL */
$resultMaterial=mysqli_query($conn,"SELECT kode_barang,nama_barang,satuan_barang FROM material ORDER BY nama_barang ASC");

/* PROSES UPDATE */
if(isset($_POST['btn_submit'])){

$conn->begin_transaction();

try{

$no_nota=$_POST['no_nota'];
$id_supplier=$_POST['id_supplier'];
$tanggal=$_POST['tanggal_masuk'];

/* UPDATE HEADER */
mysqli_query($conn,"
UPDATE nota
SET id_supplier='$id_supplier'
WHERE id_nota='$id_nota'
");

/* HAPUS DETAIL LAMA */
mysqli_query($conn,"DELETE FROM detail_nota WHERE id_nota='$id_nota'");

/* INSERT ULANG DETAIL */
foreach($_POST['nama_barang'] as $key=>$nama){

$nama=trim($nama);
$jumlah=$_POST['jumlah_barang'][$key] ?? 0;
$kode=$_POST['kode_barang'][$key] ?? '';

if($nama=='' || $jumlah==0){
continue;
}

if($kode==''){
$kode=generateKodeBarang($conn);
}

mysqli_query($conn,"
INSERT INTO detail_nota
(id_nota,no_nota,nama_barang,jumlah_barang,tanggal_masuk,kode_barang)
VALUES
('$id_nota','$no_nota','$nama','$jumlah','$tanggal','$kode')
");

}

$conn->commit();

$notif="<div class='alert alert-success'>Nota berhasil diupdate</div>";
header("Location: nota.php");

}catch(Exception $e){

$conn->rollback();

$notif="<div class='alert alert-danger'>Gagal update</div>";

}

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota | Tambah Data Nota Belanja</title>
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
                <div class="container-fluid text-dark">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Tambah Data Nota Belanja</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Input Data Nota Belanja</h3>
                        </div>
                        <form method="POST">
                            <div class="card-body">
                            <?php if(isset($notif)) echo $notif; ?>

                            <div class="form-group">
                                <label>Nomor Nota</label>
                                <input type="text" class="form-control" name="no_nota"
                                    value="<?= $dataNota['no_nota'] ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Supplier</label>
                                <select class="form-control" name="id_supplier" required>

                                    <?php while($s=mysqli_fetch_assoc($resultSupplier)){ ?>

                                    <option value="<?= $s['id_supplier']?>"
                                        <?= $s['id_supplier']==$dataNota['id_supplier']?'selected':'' ?>>

                                        <?= $s['nama_pic']?> - <?= $s['nama_supplier']?>

                                    </option>

                                    <?php } ?>

                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" name="tanggal_masuk"
                                    value="<?= $dataNota['tanggal_masuk'] ?>" required>
                            </div>

                            <div class="form-group">

                                <label>Barang Nota</label>

                                <div id="list-barang">

                                    <?php while($d=mysqli_fetch_assoc($resultDetail)){ ?>

                                    <div class="row mb-2">

                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="kode_barang[]"
                                                value="<?= $d['kode_barang'] ?>">
                                        </div>

                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="nama_barang[]"
                                                value="<?= $d['nama_barang'] ?>">
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="jumlah_barang[]"
                                                value="<?= $d['jumlah_barang'] ?>">
                                        </div>

                                        <div class="col-md-2">
                                            <select class="form-control" name="satuan[]">
                                                <option value="pcs">Pcs</option>
                                                <option value="meter">Meter</option>
                                                <option value="box">Box</option>
                                                <option value="roll">Roll</option>
                                            </select>
                                        </div>

                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger" onclick="hapusBarang(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                    </div>

                                    <?php } ?>

                                </div>

                                <button type="button" class="btn btn-success mt-2" onclick="tambahBarang()">

                                    Tambah Barang

                                </button>

                            </div>
                        </div>
                           <div class="card-footer">
                                <button type="submit" class="btn btn-success" name="btn_submit">Simpan</button>
                                <a href="nota.php" class="btn btn-danger">Batal</a>
                            </div>
                        </form>

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
    <script src="/nsp/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        let dataMaterial = [ <
            ? php
            mysqli_data_seek($resultMaterial, 0);
            while ($m = mysqli_fetch_assoc($resultMaterial)) {
                ?
                > {
                    kode: "<?= $m['kode_barang'] ?>",
                    nama: "<?= $m['nama_barang'] ?>",
                    satuan: "<?= $m['satuan_barang'] ?>"
                }, <
                ? php
            } ? >
        ];
    </script>


    <script>
        function tambahBarang() {

            let container = document.getElementById("list-barang");

            let optionMaterial = `<option value="">-- Barang Lama --</option>`;

            dataMaterial.forEach(function (item) {
                optionMaterial += `<option value="${item.kode}" data-nama="${item.nama}" data-satuan="${item.satuan}">
        ${item.kode} - ${item.nama}
        </option>`;
            });


            let div = document.createElement("div");
            div.classList.add("row", "mb-2");

            div.innerHTML = `

    <div class="col-md-3">

        <select class="form-control select-barang" name="kode_barang[]" onchange="isiOtomatis(this)">
        ${optionMaterial}
        </select>

        <div class="mt-1">
        <input type="checkbox" onclick="barangBaruToggle(this)">
        Barang Baru
        </div>

    </div>

    <div class="col-md-3">
        <input type="text" class="form-control nama-barang" name="nama_barang[]" placeholder="Nama Barang" required>
    </div>

    <div class="col-md-2">
        <input type="number" class="form-control" name="jumlah_barang[]" placeholder="Jumlah" required>
    </div>

    <div class="col-md-2">
        <select class="form-control satuan-barang" name="satuan[]" required>
            <option value="">-- Satuan --</option>
            <option value="pcs">Pcs</option>
            <option value="meter">Meter</option>
            <option value="box">Box</option>
            <option value="roll">Roll</option>
        </select>
    </div>

    <div class="col-md-1">
        <button type="button" class="btn btn-danger" onclick="hapusBarang(this)">
        <i class="fas fa-trash"></i>
        </button>
    </div>

    `;

            container.appendChild(div);
        }



        function isiOtomatis(selectElement) {

            let option = selectElement.options[selectElement.selectedIndex];

            let row = selectElement.closest(".row");

            let nama = row.querySelector(".nama-barang");
            let satuan = row.querySelector(".satuan-barang");

            nama.value = option.getAttribute("data-nama") || "";
            satuan.value = option.getAttribute("data-satuan") || "";

        }


        function barangBaruToggle(checkbox) {

            let row = checkbox.closest(".row");

            let select = row.querySelector(".select-barang");
            let nama = row.querySelector(".nama-barang");
            let satuan = row.querySelector(".satuan-barang");

            if (checkbox.checked) {

                select.disabled = true;

                nama.value = "";
                satuan.value = "";

                $.get('?generate_kode=1', function (kode) {

                    select.innerHTML = `<option value="${kode}" selected>${kode} - Barang Baru</option>`;

                });

            } else {

                select.disabled = false;

                let optionMaterial = `<option value="">-- Barang Lama --</option>`;

                dataMaterial.forEach(function (item) {
                    optionMaterial += `<option value="${item.kode}" data-nama="${item.nama}" data-satuan="${item.satuan}">
            ${item.kode} - ${item.nama}
            </option>`;
                });

                select.innerHTML = optionMaterial;

            }

        }


        function hapusBarang(button) {

            button.closest(".row").remove();

        }
    </script>

</body>

</html>