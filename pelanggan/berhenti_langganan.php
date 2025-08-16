<?php
// /nsp/pelanggan/berhenti_langganan.php
session_start();
if (!isset($_SESSION['id_users']) || ($_SESSION['peran'] ?? '') !== 'pelanggan') {
  header("Location: /nsp/login.php"); exit;
}

include "/xampp/htdocs/nsp/services/koneksi.php";
date_default_timezone_set('Asia/Makassar');

if (!isset($_POST['stop'], $_POST['id_langganan'])) {
  header("Location: /nsp/pelanggan/dashboard.php"); exit;
}

$idUser       = (int)$_SESSION['id_users'];
$idLangganan  = trim($_POST['id_langganan']);

// 1) Pastikan langganan milik user ini & status masih AKTIF/ISOLIR
$cek = $conn->prepare("
  SELECT p.id_langganan, p.status_pelanggan
  FROM pelanggan p
  WHERE p.id_user = ? AND p.id_langganan = ?
  LIMIT 1
");
$cek->bind_param("is", $idUser, $idLangganan);
$cek->execute();
$res = $cek->get_result();
$data = $res->fetch_assoc();
$cek->close();

if (!$data) {
  $_SESSION['flash_error'] = "Data langganan tidak ditemukan.";
  header("Location: /nsp/pelanggan/dashboard.php"); exit;
}
if ($data['status_pelanggan'] === 'TIDAK AKTIF') {
  $_SESSION['flash_info'] = "Langganan ini sudah tidak aktif.";
  header("Location: /nsp/pelanggan/dashboard.php"); exit;
}

// 2) Update status jadi TIDAK AKTIF
$upd = $conn->prepare("
  UPDATE pelanggan
  SET status_pelanggan = 'TIDAK AKTIF'
  WHERE id_user = ? AND id_langganan = ?
  LIMIT 1
");
$upd->bind_param("is", $idUser, $idLangganan);
if ($upd->execute()) {
  // (opsional) Tandai tanggal nonaktif bila kamu punya kolomnya, mis: tanggal_nonaktif = CURDATE()

  // 3) Simpan pesan untuk ditampilkan di dashboard
  $_SESSION['flash_success'] =
    "Langganan Anda telah dihentikan. Anda tetap dapat login untuk melihat riwayat & menyelesaikan tagihan yang tertunda.";
} else {
  $_SESSION['flash_error'] = "Gagal menghentikan langganan. Coba lagi.";
}
$upd->close();

// 4) Redirect balik (profil/dashboard)
header("Location: /nsp/pelanggan/dashboard.php");
exit;
