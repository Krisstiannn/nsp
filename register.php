<?php
include "./services/koneksi.php";
include "./helpers/send_mail.php";   // helper email
$notifikasi = "";

if (isset($_POST['btn_register'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $retype   = $_POST['re_type'];

    if ($password !== $retype) {
        $notifikasi = "PASSWORD TIDAK SAMA";
    } else {
        // cek email unik
        $cek = $conn->prepare("SELECT 1 FROM users WHERE username=?");
        $cek->bind_param("s", $email);
        $cek->execute(); $cek->store_result();
        if ($cek->num_rows > 0) {
            $notifikasi = "Email sudah terdaftar.";
        } else {
            // hash password (AMAN)
            $hash = password_hash($password, PASSWORD_DEFAULT);
            // token verifikasi aman (random bytes -> hex)
            $token = bin2hex(random_bytes(16)); // 32 char

            $ins = $conn->prepare("
                INSERT INTO users (username, password, peran, is_verified, kode_verifikasi)
                VALUES (?, ?, 'pelanggan', 0, ?)
            ");
            $ins->bind_param("sss", $email, $hash, $token);
            if ($ins->execute()) {
                list($ok, $err) = sendVerificationEmail($email, $token);
                if ($ok) {
                    echo "<script>alert('Registrasi berhasil. Cek email untuk verifikasi.');location.href='login.php';</script>";
                    exit;
                } else {
                    // fallback dev: tampilkan link langsung agar bisa verifikasi manual saat lokal
                    include "./config_mail.php";
                    $link = $APP_URL . "/verify.php?email=" . urlencode($email) . "&token=" . urlencode($token);
                    echo "<div style='max-width:560px;margin:40px auto;font-family:sans-serif'>
                            <h3>Registrasi berhasil, tapi gagal kirim email</h3>
                            <p>Error SMTP: ".htmlspecialchars($err)."</p>
                            <p>Mode lokal: klik tautan ini untuk verifikasi:</p>
                            <p><a href='{$link}'>{$link}</a></p>
                            <p><a href='login.php'>Kembali ke Login</a></p>
                          </div>";
                    exit;
                }
            } else {
                $notifikasi = "Pendaftaran gagal. Coba lagi.";
            }
            $ins->close();
        }
        $cek->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NET SUN POWER | REGISTER</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="./dist/css/adminlte.min.css">
  <link rel="icon" href="/nsp/storage/nsp.jpg">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline shadow-lg">
    <div class="card-header text-center"><i class="h1">Net Sun Power</i></div>
    <div class="card-body">
      <p class="login-box-msg">Silahkan Buat Akun Terlebih Dahulu</p>
      <span class="text-center login-box-msg text-red mb-10"><?= htmlspecialchars($notifikasi) ?></span>

      <form action="" method="POST">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email" required>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" required>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Konfirmasi Password" name="re_type" required>
        </div>
        <div class="row mt-2 mb-3">
          <button type="submit" class="btn btn-block btn-primary" name="btn_register">REGISTER</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="./plugins/jquery/jquery.min.js"></script>
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="./dist/js/adminlte.min.js"></script>
</body>
</html>
