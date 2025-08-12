<?php
include "./services/koneksi.php";
$notifikasi = "";
$email = $_GET['email'] ?? '';

if (isset($_POST['verifikasi'])) {
    $email = $_POST['email'];
    $kode_input = $_POST['kode_verifikasi'];

    // Cek apakah kode cocok
    $query = "SELECT * FROM users WHERE username = '$email' AND kode_verifikasi = '$kode_input'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        // Update status verifikasi
        $update = "UPDATE users SET is_verified = 1 WHERE username = '$email'";
        $conn->query($update);

        echo "<script>
            alert('Verifikasi berhasil! Silakan login.');
            window.location.href = 'login.php';
        </script>";
    } else {
        $notifikasi = "Kode verifikasi salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
    <link rel="stylesheet" href="./dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline shadow-lg">
            <div class="card-header text-center">
                <h2>Verifikasi Email</h2>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Masukkan kode verifikasi yang telah dikirim ke email Anda</p>
                <span class="text-danger"><?= $notifikasi ?></span>

                <form action="" method="POST">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                    <div class="input-group mb-3">
                        <input type="text" name="kode_verifikasi" class="form-control" placeholder="Kode Verifikasi" required>
                    </div>
                    <div class="row mt-2 mb-3">
                        <button type="submit" name="verifikasi" class="btn btn-success btn-block">
                            Verifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
