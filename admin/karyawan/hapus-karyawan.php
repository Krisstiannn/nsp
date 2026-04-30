<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "/xampp/htdocs/nsp/services/koneksi.php";

if (!isset($_GET['nip_karyawan'])) {
    die("NIP tidak ditemukan");
}

$id = $_GET['nip_karyawan'];

$query = "DELETE FROM karyawan WHERE nip_karyawan = '$id'";
$result = $conn->query($query);

if (!$result) {
    die("Gagal hapus: " . $conn->error);
}

echo "<script>
    alert('Data Berhasil di Hapus!');
    window.location.href = 'datakaryawan.php';
</script>";