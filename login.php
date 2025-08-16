<?php
session_start();
include "./services/koneksi.php";
include "./helpers/billing_helper.php";
date_default_timezone_set('Asia/Makassar');

$notifikasi_login = "";
if (isset($_POST['btn_login'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    $query_users = "SELECT users.id_users, users.username, users.password, users.peran, 
                           karyawan.nip_karyawan, karyawan.nama_karyawan, karyawan.id, 
                           pelanggan.nama_pelanggan, pelanggan.id_langganan
                FROM users 
                LEFT JOIN karyawan ON users.username = karyawan.nip_karyawan
                LEFT JOIN pelanggan ON users.id_users = pelanggan.id_user
                WHERE users.username = '$username' AND users.password = '$password'";
    $result_users = $conn->query($query_users);

    if ($result_users->num_rows > 0) {
        $data_login = $result_users->fetch_assoc();
        $_SESSION['id_users'] = $data_login['id_users'];
        $_SESSION['username'] = $data_login['username'];
        $_SESSION["peran"] = $data_login["peran"];
        if ($_SESSION["peran"] === "admin") {
            $_SESSION['id_karyawan'] = $data_login['id'];
            $_SESSION['nip'] = $data_login['nip_karyawan'];
            $_SESSION['nama_karyawan'] = $data_login['nama_karyawan'];
            header("location: ./admin/index.php");
            exit();
        } else if ($_SESSION["peran"] === "teknisi") {
            $_SESSION['id_karyawan'] = $data_login['id'];
            $_SESSION['nip'] = $data_login['nip_karyawan'];
            $_SESSION['nama_karyawan'] = $data_login['nama_karyawan'];
            header("location: ./user/index.php");
            exit();
        } else if ($_SESSION["peran"] === "pelanggan") {
            $_SESSION['nama_pelanggan'] = $data_login['nama_pelanggan'] ?? '';
            $_SESSION['id_langganan']   = $data_login['id_langganan'] ?? null;   // <-- WAJIB

            $idUserLogin = (int)$_SESSION['id_users'];
            $idLangLogin = (string)($_SESSION['id_langganan'] ?? '');

            if (!empty($idLangLogin)) {
                // 1) buat tagihan bulan ini jika belum ada
                $resultBilling = handleBillingOnLogin($conn, $idUserLogin, $idLangLogin);
                if (!empty($resultBilling['notif'])) {
                    $_SESSION['notif_tagihan'] = $resultBilling['notif'];
                } else {
                    unset($_SESSION['notif_tagihan']);
                }

                // 2) cek & terapkan isolir
                $iso = applyIsolationStatus($conn, $idUserLogin, $idLangLogin);
                $_SESSION['isolir'] = $iso['isolir'];
                if (!empty($iso['notif'])) {
                    $_SESSION['notif_isolir'] = $iso['notif'];
                } else {
                    unset($_SESSION['notif_isolir']);
                }
            }

            // redirect ke dashboard pelanggan
            header("Location: /nsp/pelanggan/dashboard.php");
            exit;
        } else {
            $notifikasi_login = "USERNAME ATAU PASSWORD SALAH!!!";
        }
    } else {
        $notifikasi_login = "USERNAME ATAU PASSWORD SALAH!!!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NET SUN POWER | Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="./dist/css/adminlte.min.css">
    <link rel="icon" href="/nsp/storage/nsp.jpg">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline shadow-lg">
            <div class="card-header text-center">
                <i class="h1">Net Sun Power</i>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Silahkan Login Terlebih Dahulu!</p>

                <span class="text-center login-box-msg text-red mb-10"><?= $notifikasi_login ?></span>

                <form action="login.php" method="POST">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Email" name="username">
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                    </div>
                    <div class="row">
                        <span><a href="cek-email.php">Forget Password?</a></span>
                    </div>
                    <div class="row mt-2">
                        <button type="submit" class="btn btn-block btn-primary" name="btn_login">
                            <i class="fab mr-2"></i> LOGIN
                        </button>
                        <span class="mt-2 login-box-msg">Belum Memiliki Akun?<a href="register.php"> Klik
                                Disini Untuk Membuat</a></span>
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