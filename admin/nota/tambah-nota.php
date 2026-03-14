<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

if(isset($_GET['generate_kode'])){
    echo generateKodeBarang($conn);
    exit;
}

// Ambil data supplier
$resultSupplier = mysqli_query($conn, "SELECT * FROM supplier");

// Ambil data material untuk dropdown
$resultMaterial = mysqli_query($conn, "SELECT kode_barang, nama_barang, satuan_barang FROM material ORDER BY nama_barang ASC");


// Function generate kode barang
function generateKodeBarang($conn)
{
    // ambil kode terbesar dari material
    $q1 = mysqli_query($conn,"SELECT MAX(kode_barang) AS max_code FROM material");
    $d1 = mysqli_fetch_assoc($q1);
    $kode_material = $d1['max_code'];

    // ambil kode terbesar dari detail_nota
    $q2 = mysqli_query($conn,"SELECT MAX(kode_barang) AS max_code FROM detail_nota");
    $d2 = mysqli_fetch_assoc($q2);
    $kode_detail = $d2['max_code'];

    // pilih yang paling besar
    $kode_terakhir = max($kode_material,$kode_detail);

    if($kode_terakhir){

        $urutan = (int) substr($kode_terakhir,8);
        $urutan++;

    }else{

        $urutan = 2001;

    }

    return "NSP-MTR-" . $urutan;
}

if (isset($_POST['btn_submit'])) {

    $conn->begin_transaction();

    try {

        $no_nota     = $_POST['no_nota'] ?? '';
        $id_supplier = $_POST['id_supplier'] ?? '';
        $tanggal     = $_POST['tanggal_masuk'] ?? '';

        if(empty($no_nota) || empty($id_supplier) || empty($tanggal)){
            throw new Exception("Data header tidak lengkap");
        }

        // simpan header nota
        $queryNota = "INSERT INTO nota (id_supplier) VALUES ('$id_supplier')";
        if(!mysqli_query($conn,$queryNota)){
            throw new Exception(mysqli_error($conn));
        }

        $id_nota = mysqli_insert_id($conn);

        if(!empty($_POST['nama_barang'])){

            foreach ($_POST['nama_barang'] as $key => $nama_barang) {

                $nama_barang   = trim($nama_barang);
                $jumlah_barang = $_POST['jumlah_barang'][$key] ?? 0;
                $kode_barang   = $_POST['kode_barang'][$key] ?? '';
                $satuan        = $_POST['satuan'][$key] ?? '';

                // skip jika kosong
                if($nama_barang == '' || $jumlah_barang == 0){
                    continue;
                }

                // jika barang baru generate kode
                if(empty($kode_barang)){
                    $kode_barang = generateKodeBarang($conn);
                }

                $queryDetail = "
                    INSERT INTO detail_nota
                    (id_nota,no_nota,nama_barang,jumlah_barang,tanggal_masuk,kode_barang)
                    VALUES
                    ('$id_nota','$no_nota','$nama_barang','$jumlah_barang','$tanggal','$kode_barang')
                ";

                if(!mysqli_query($conn,$queryDetail)){
                    throw new Exception(mysqli_error($conn));
                }
            }
        }

        $conn->commit();

        $notif = "<div class='alert alert-success'>Nota berhasil direkap</div>";
        header("Location: nota.php");

    } catch (Exception $e) {

        $conn->rollback();

        $notif = "<div class='alert alert-danger'>Error : ".$e->getMessage()."</div>";
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
                    <form method="POST" action="">
                        <div class="card-body">
                            <?php if (isset($notif)) echo $notif; ?>
                            <div class="form-group">
                                <label>Nomor Nota</label>
                                <input type="text" class="form-control" name="no_nota" placeholder="Nomor Nota" required>
                            </div>

                            <div class="form-group">
                                <label>Nama Supplier dan PIC</label>
                                <select class="custom-select" name="id_supplier" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    <?php while ($row = mysqli_fetch_assoc($resultSupplier)) { ?>
                                        <option value="<?= $row['id_supplier'] ?>"><?= $row['nama_pic'] ?> - <?= $row['nama_supplier'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Rekap</label>
                                <input type="date" class="form-control" name="tanggal_masuk" required>
                            </div>

                            <div class="form-group">
                                <label>Barang pada Nota</label>
                                <div id="list-barang"></div>
                                <button type="button" class="btn btn-success mt-2" onClick="tambahBarang()">Tambah Barang</button>
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
        let dataMaterial = [
        <?php
        mysqli_data_seek($resultMaterial,0);
        while($m = mysqli_fetch_assoc($resultMaterial)){
        ?>
        {
            kode:"<?= $m['kode_barang'] ?>",
            nama:"<?= $m['nama_barang'] ?>",
            satuan:"<?= $m['satuan_barang'] ?>"
        },
        <?php } ?>
        ];
    </script>


<script>

function tambahBarang() {

    let container = document.getElementById("list-barang");

    let optionMaterial = `<option value="">-- Barang Lama --</option>`;

    dataMaterial.forEach(function(item){
        optionMaterial += `<option value="${item.kode}" data-nama="${item.nama}" data-satuan="${item.satuan}">
        ${item.kode} - ${item.nama}
        </option>`;
    });


    let div = document.createElement("div");
    div.classList.add("row","mb-2");

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



function isiOtomatis(selectElement){

    let option = selectElement.options[selectElement.selectedIndex];

    let row = selectElement.closest(".row");

    let nama = row.querySelector(".nama-barang");
    let satuan = row.querySelector(".satuan-barang");

    nama.value = option.getAttribute("data-nama") || "";
    satuan.value = option.getAttribute("data-satuan") || "";

}


function barangBaruToggle(checkbox){

    let row = checkbox.closest(".row");

    let select = row.querySelector(".select-barang");
    let nama = row.querySelector(".nama-barang");
    let satuan = row.querySelector(".satuan-barang");

    if(checkbox.checked){

        select.disabled = true;

        nama.value="";
        satuan.value="";

        $.get('?generate_kode=1', function(kode){

            select.innerHTML = `<option value="${kode}" selected>${kode} - Barang Baru</option>`;

        });

    }else{

        select.disabled = false;

        let optionMaterial = `<option value="">-- Barang Lama --</option>`;

        dataMaterial.forEach(function(item){
            optionMaterial += `<option value="${item.kode}" data-nama="${item.nama}" data-satuan="${item.satuan}">
            ${item.kode} - ${item.nama}
            </option>`;
        });

        select.innerHTML = optionMaterial;

    }

}


function hapusBarang(button){

    button.closest(".row").remove();

}

</script>

</body>
</html>