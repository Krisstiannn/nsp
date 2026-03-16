<section class="content">

                <div class="container-fluid">

                    <div class="card card-primary">

                        

                        <form method="POST">

                            <div class="card-body">

                                <div class="form-group">

                                    <label>Pilih Nomor Nota</label>

                                    <select name="id_nota" class="form-control" required>

                                        <option value="">-- Pilih Nota --</option>

                                        <?php while($n=mysqli_fetch_assoc($queryNota)){ ?>

                                        <option value="<?= $n['id_nota'] ?>"
                                            <?= $selectedNota==$n['id_nota']?'selected':'' ?>>

                                            <?= $n['no_nota'] ?> | <?= $n['tanggal_masuk'] ?>

                                        </option>

                                        <?php } ?>

                                    </select>

                                </div>

                                <button type="submit" name="btn_preview" class="btn btn-info">
                                    <i class="fas fa-search"></i> Preview Barang
                                </button>

                            </div>

                        </form>

                    </div>

                    <?php if(!empty($previewData)){ ?>

                    <div class="card card-success">

                        <div class="card-header">
                            <h3 class="card-title">Preview Barang Nota</h3>
                        </div>

                        <div class="card-body">

                            <form method="POST">

                                <input type="hidden" name="id_nota" value="<?= $selectedNota ?>">

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

                                            <?php foreach($previewData as $p){ ?>

                                            <tr>

                                                <td><?= $p['kode_barang'] ?></td>
                                                <td><?= $p['nama_barang'] ?></td>
                                                <td><?= $p['jumlah_barang'] ?></td>

                                            </tr>

                                            <?php } ?>

                                        </tbody>

                                    </table>

                                </div>

                                <button type="submit" name="btn_submit" class="btn btn-success">

                                    <i class="fas fa-box"></i> Submit Restok

                                </button>

                            </form>

                        </div>

                    </div>

                    <?php } ?>

                </div>

            </section>
            
<?php            
$previewData = [];
$selectedNota = "";

/* AMBIL LIST NOTA YANG BELUM DIRESTOK */
$queryNota = mysqli_query($conn,"
SELECT id_nota,no_nota,tanggal_masuk
FROM nota
WHERE status_restok='belum'
ORDER BY id_nota DESC
");

/* PREVIEW BARANG */
if(isset($_POST['btn_preview'])){

$selectedNota = $_POST['id_nota'];

$qPreview = mysqli_query($conn,"
SELECT *
FROM detail_nota
WHERE id_nota='$selectedNota'
");

while($row = mysqli_fetch_assoc($qPreview)){
$previewData[] = $row;
}

}

/* PROSES RESTOK */
if(isset($_POST['btn_submit'])){

$id_nota = $_POST['id_nota'];

$conn->begin_transaction();

try{

$qDetail = mysqli_query($conn,"
SELECT * 
FROM detail_nota
WHERE id_nota='$id_nota'
");

while($row = mysqli_fetch_assoc($qDetail)){

$kode = $row['kode_barang'];
$nama = $row['nama_barang'];
$jumlah = $row['jumlah_barang'];

/* CEK MATERIAL */
$cek = mysqli_query($conn,"
SELECT * 
FROM material
WHERE kode_barang='$kode'
");

if(mysqli_num_rows($cek) > 0){

mysqli_query($conn,"
UPDATE material
SET stok = stok + '$jumlah'
WHERE kode_barang='$kode'
");

}else{

mysqli_query($conn,"
INSERT INTO material(kode_barang,nama_barang,stok)
VALUES('$kode','$nama','$jumlah')
");

}

}

/* UPDATE STATUS NOTA */
mysqli_query($conn,"
UPDATE nota
SET status_restok='sudah',
tanggal_restok=NOW()
WHERE id_nota='$id_nota'
");

$conn->commit();

echo "<script>
alert('Restok berhasil dilakukan');
window.location='restok-massal.php';
</script>";

}catch(Exception $e){

$conn->rollback();

echo "<script>
alert('Terjadi kesalahan saat restok');
window.location='restok-massal.php';
</script>";

}

}