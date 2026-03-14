<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$id = $_GET['id'];
$query_hapusData = "DELETE FROM supplier WHERE id_supplier = '$id'";
$result_hapusData = $conn->query($query_hapusData);

if ($result_hapusData) {
    echo "<script type= 'text/javascript'>
                alert('Data Berhasil di Hapus!');
                document.location.href = 'supplier.php';
            </script>";
}
