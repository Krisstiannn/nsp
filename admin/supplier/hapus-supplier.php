<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "/xampp/htdocs/nsp/services/koneksi.php";

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = $_GET['id'];

$query = "DELETE FROM supplier WHERE id_supplier = '$id'";
$result = $conn->query($query);

if (!$result) {
    die("Gagal hapus: " . $conn->error);
}

echo "<script>
    alert('Data Berhasil di Hapus!');
    window.location.href = 'supplier.php';
</script>";