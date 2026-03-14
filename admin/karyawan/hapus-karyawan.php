<?php
include "/xampp/htdocs/nsp/services/koneksi.php";

$nip_karyawan = $_GET['nip_karyawan'];
$query_hapus = "DELETE FROM `users` WHERE username = '$nip_karyawan'";
$result_hapus = $conn->query($query_hapus);

if ($result_hapus) {
    echo "<script type= 'text/javascript'>
                alert('Data Berhasil Dihapus!');
                document.location.href = 'datakaryawan.php';
            </script>";
}
