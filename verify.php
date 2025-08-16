<?php
include "./services/koneksi.php";

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? ($_GET['kode'] ?? ''); // <- terima token ATAU kode

if ($email === '' || $token === '') {
  die('Link verifikasi tidak valid.');
}

// pakai is_verified = 0 ATAU IS NULL
$stmt = $conn->prepare("
  SELECT id_users FROM users
  WHERE username=? 
    AND kode_verifikasi=? 
    AND (is_verified = 0 OR is_verified IS NULL)
  LIMIT 1
");
$stmt->bind_param("ss", $email, $token);
$stmt->execute();
$stmt->bind_result($uid);
$found = $stmt->fetch();
$stmt->close();

if ($found) {
  $upd = $conn->prepare("
    UPDATE users
       SET is_verified = 1,
           kode_verifikasi = NULL
     WHERE id_users = ?
    LIMIT 1
  ");
  $upd->bind_param("i", $uid);
  if ($upd->execute()) {
    echo "<script>alert('Verifikasi berhasil! Silakan login.');location.href='login.php';</script>";
    exit;
  }
  die('Gagal mengaktifkan akun. Coba lagi.');
} else {
  die('Link verifikasi tidak valid atau akun sudah aktif.');
}
