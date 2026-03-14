<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$id_nota = $_GET['id_nota'] ?? 0;

if($id_nota == 0){
    header("Location: nota.php");
    exit;
}

$conn->begin_transaction();

try{

    // hapus detail barang
    $query_detail = "DELETE FROM detail_nota WHERE id_nota='$id_nota'";
    mysqli_query($conn,$query_detail);

    // hapus header nota
    $query_nota = "DELETE FROM nota WHERE id_nota='$id_nota'";
    mysqli_query($conn,$query_nota);

    $conn->commit();

    echo "<script type= 'text/javascript'>
                alert('Data Berhasil di Hapus!');
                document.location.href = 'nota.php';
            </script>";

}catch(Exception $e){

    $conn->rollback();

    echo "<script type= 'text/javascript'>
                alert('Gagal menghapus data: " . $e->getMessage() . "');
                document.location.href = 'nota.php';
            </script>";

}
?>
