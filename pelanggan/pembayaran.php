<?php
session_start();
if (!isset($_SESSION['id_users']) || ($_SESSION['peran'] ?? '') !== 'pelanggan') {
    header("Location: /nsp/login.php"); exit;
}
include "/xampp/htdocs/nsp/services/koneksi.php";
date_default_timezone_set('Asia/Makassar');

function rupiah($n){ return 'Rp. ' . number_format((int)$n,0,',','.'); }

$tanggal = date('d-m-Y');
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$idUser = (int)$_SESSION['id_users'];

$stmt = $conn->prepare("
    SELECT 
      pb.id_pembayaran, pb.id_langganan, pb.bulan_tagihan, pb.jumlah_tagihan,
      pb.status_pembayaran, pb.tanggal_pembayaran, pb.jatuh_tempo, pb.bukti_pembayaran,
      pel.id_user, pel.nama_pelanggan,
      psb.paket_internet AS jenis_layanan,
      pk.harga AS harga_paket
    FROM pembayaran pb
    JOIN pelanggan pel ON pel.id_langganan = pb.id_langganan
    LEFT JOIN psb ON psb.id_langganan = pb.id_langganan
    LEFT JOIN jenis_paket pk ON pk.jenis_paket = psb.paket_internet
    WHERE pb.id_pembayaran = ? AND pel.id_user = ?
    LIMIT 1
");
$stmt->bind_param("ii", $id, $idUser);
$stmt->execute();
$tagihan = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$tagihan) {
    $_SESSION['notif_tagihan'] = "Tagihan tidak ditemukan.";
    header("Location: /nsp/pelanggan/portal_pembayaran.php"); exit;
}

$successMsg = $errorMsg = "";

if (isset($_POST['btn_submit'])) {
    if ($tagihan['status_pembayaran'] === 'SUDAH BAYAR') {
        $errorMsg = "Tagihan ini sudah dibayar.";
    } else {
        if (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_NO_FILE) {
            $errorMsg = "Silakan unggah bukti pembayaran.";
        } else {
            $f = $_FILES['bukti_pembayaran'];
            if ($f['error'] !== UPLOAD_ERR_OK) {
                $errorMsg = "Gagal upload (error {$f['error']}).";
            } else {
                $allowed = ['jpg','jpeg','png','pdf'];
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) {
                    $errorMsg = "Format harus JPG/PNG/PDF.";
                } elseif ($f['size'] > 3 * 1024 * 1024) {
                    $errorMsg = "Ukuran maksimal 3MB.";
                } else {
                    $dirAbs = "/xampp/htdocs/nsp/storage/bukti/";
                    if (!is_dir($dirAbs)) { @mkdir($dirAbs, 0775, true); }

                    $fname   = "bukti_{$tagihan['id_pembayaran']}_" . time() . "." . $ext;
                    $pathAbs = $dirAbs . $fname;
                    $pathRel = "/nsp/storage/bukti/" . $fname;

                    if (move_uploaded_file($f['tmp_name'], $pathAbs)) {
                        // 1) UPDATE: langsung SUDAH BAYAR + tanggal hari ini
                        $upd = $conn->prepare("
                            UPDATE pembayaran
                               SET bukti_pembayaran = ?,
                                   tanggal_pembayaran = CURDATE(),
                                   status_pembayaran  = 'SUDAH BAYAR'
                             WHERE id_pembayaran    = ?
                        ");
                        $upd->bind_param("si", $pathRel, $id);
                        if (!$upd->execute()) {
                            $errorMsg = "Gagal menyimpan ke database.";
                        }
                        $upd->close();

                        if (empty($errorMsg)) {
                            // 2) CEK sisa tunggakan SETELAH update
                            $cek = $conn->prepare("
                                SELECT COUNT(*)
                                  FROM pembayaran
                                 WHERE id_langganan = ?
                                   AND status_pembayaran = 'BELUM BAYAR'
                                   AND jatuh_tempo < CURDATE()
                            ");
                            $cek->bind_param("s", $tagihan['id_langganan']);
                            $cek->execute();
                            $cek->bind_result($sisa);
                            $cek->fetch();
                            $cek->close();

                            if ((int)$sisa === 0) {
                                // 3) Tidak ada tunggakan -> aktifkan jika sebelumnya ISOLIR
                                $up = $conn->prepare("
                                    UPDATE pelanggan
                                       SET status_pelanggan = 'AKTIF'
                                     WHERE id_langganan = ?
                                       AND status_pelanggan = 'ISOLIR'
                                ");
                                $up->bind_param("s", $tagihan['id_langganan']);
                                $up->execute();
                                $up->close();
                            }

                            // 4) Redirect ke history
                            unset($_SESSION['notif_tagihan'], $_SESSION['notif_isolir'], $_SESSION['isolir']);
                            header("Location: /nsp/pelanggan/pembayaran.php?ok=1");
                            exit;
                        }
                    } else {
                        $errorMsg = "Gagal memindahkan file upload.";
                    }
                }
            }
        }
    }
}



if (isset($_GET['ok'])) {
    $stmt = $conn->prepare("
        SELECT 
          pb.id_pembayaran, pb.id_langganan, pb.bulan_tagihan, pb.jumlah_tagihan,
          pb.status_pembayaran, pb.tanggal_pembayaran, pb.jatuh_tempo, pb.bukti_pembayaran,
          pel.id_user, pel.nama_pelanggan,
          psb.paket_internet AS jenis_layanan,
          pk.harga AS harga_paket
        FROM pembayaran pb
        JOIN pelanggan pel ON pel.id_langganan = pb.id_langganan
        LEFT JOIN psb ON psb.id_langganan = pb.id_langganan
        LEFT JOIN jenis_paket pk ON pk.jenis_paket = psb.paket_internet
        WHERE pb.id_pembayaran = ? AND pel.id_user = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $id, $idUser);
    $stmt->execute();
    $tagihan = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@300;400;500;700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/nsp/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="/nsp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="/nsp/dist/css/adminlte.min.css">
  <link rel="icon" href="/nsp/storage/netsun.jpg">
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">
    <?php include "/xampp/htdocs/nsp/layouts/navbar.php" ?>

    <div class="content-wrapper">
      <div class="content">
        <div class="container">
          <div class="content-header">
            <div class="container-fluid text-black">
              <div class="row mb-2">
                <div class="col-sm-12">
                  <h1 class="m-0">Selamat Datang ... di Website Resmi Net Sun Power</h1>
                </div>
              </div>
            </div>
          </div>

          <div class="invoice p-3 mb-3">
            <div class="row">
              <div class="col-12">
                <h4><i></i>Pembayaran Tagihan Internet Bulan...
                  <small class="float-right"><?= htmlspecialchars($tanggal) ?></small>
                </h4>
              </div>
            </div>

            <div class="row invoice-info">
              <div class="col-sm-12 invoice-col">
                <span>Untuk Pembayaran Tagihan Bulanan Silahkan Transfer ke
                  <strong>Nomor Rekening 112299008 AN PT.Net Sun Power/Mandiri</strong></span>
              </div>
            </div>

            <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger mt-3"><?= htmlspecialchars($errorMsg) ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['ok'])): ?>
            <div class="alert alert-success mt-3">Pembayaran berhasil. Status tercatat <b>SUDAH BAYAR</b>.</div>
            <?php endif; ?>


            <div class="row mt-3">
              <div class="col-12">
                <form action="pembayaran.php?id=<?= (int)$tagihan['id_pembayaran'] ?>" method="POST"
                  enctype="multipart/form-data">
                  <div class="form-group">
                    <label>ID Berlangganan</label>
                    <input type="hidden" name="id_langganan" value="<?= htmlspecialchars($tagihan['id_langganan']) ?>">
                    <input type="text" class="form-control" value="<?= htmlspecialchars($tagihan['id_langganan']) ?>"
                      disabled>
                  </div>

                  <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($tagihan['nama_pelanggan']) ?>"
                      disabled>
                  </div>

                  <div class="form-group">
                    <label>Jenis Layanan</label>
                    <input type="text" class="form-control"
                      value="<?= htmlspecialchars($tagihan['jenis_layanan'] ?? '-') ?>" disabled>
                  </div>

                  <div class="form-group">
                    <label>Jumlah Tagihan</label>
                    <input type="text" class="form-control" value="<?= rupiah($tagihan['jumlah_tagihan']) ?>" disabled>
                  </div>

                  <div class="form-group">
                    <label>Tanggal Pembayaran</label>
                    <input type="hidden" name="tanggal_pembayaran" value="<?= date('Y-m-d') ?>">
                    <input type="text" class="form-control" value="<?= date('d-m-Y') ?>" disabled>
                  </div>

                  <?php if (!empty($tagihan['bukti_pembayaran'])): ?>
                  <div class="form-group">
                    <label>Bukti Pembayaran Sebelumnya</label><br>
                    <?php if (preg_match('/\.(jpg|jpeg|png)$/i', $tagihan['bukti_pembayaran'])): ?>
                    <img src="<?= htmlspecialchars($tagihan['bukti_pembayaran']) ?>" style="max-width:240px;">
                    <?php else: ?>
                    <a href="<?= htmlspecialchars($tagihan['bukti_pembayaran']) ?>" target="_blank">Lihat Bukti</a>
                    <?php endif; ?>
                  </div>
                  <?php endif; ?>

                  <div class="form-group">
                    <label>Upload Bukti Pembayaran</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="bukti_pembayaran"
                          accept=".jpg,.jpeg,.png,.pdf"
                          <?= ($tagihan['status_pembayaran']==='SUDAH BAYAR')?'disabled':''; ?>>
                        <label class="custom-file-label">Pilih file...</label>
                      </div>
                    </div>
                  </div>

                  <div class="card-footer">
                    <button type="submit" class="btn btn-success" name="btn_submit"
                      <?= ($tagihan['status_pembayaran']==='SUDAH BAYAR')?'disabled':''; ?>>Submit</button>
                    <a href="/nsp/pelanggan/portal_pembayaran.php" class="btn btn-danger">Cancel</a>
                  </div>

                  <div class="mt-3">
                    <small>Status saat ini:
                      <b><?= htmlspecialchars($tagihan['status_pembayaran']) ?></b>
                      <?= $tagihan['jatuh_tempo'] ? '(Jatuh tempo: '.date('d-m-Y', strtotime($tagihan['jatuh_tempo'])).')' : '' ?>
                    </small>
                  </div>
                </form>
              </div>
            </div>

          </div><!-- /.invoice -->
        </div>
      </div>
    </div>

    <div class="card bg-light p-3">
      <div class="text-center">
        <h5 class="font-weight-bold text-primary">For more information, please contact here</h5>
      </div>
      <div class="d-flex justify-content-center align-items-center">
        <i class="fas fa-phone-alt text-danger mr-2"></i>
        <span class="mr-1">WhatsApp:</span>
        <a href="https://wa.me/6281234567890" class="text-primary">0812-3456-7890</a>
      </div>
    </div>

    <footer class="main-footer bg-blue" style="text-align:center;">
      <strong>Copyright &copy; <?= date('Y') ?> Net Sun Power.</strong> All rights reserved.
    </footer>
  </div>

  <script src="/nsp/plugins/jquery/jquery.min.js"></script>
  <script src="/nsp/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/nsp/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <script src="/nsp/dist/js/adminlte.min.js"></script>

  <!-- Tambahkan plugin bs-custom-file-input jika belum ada -->
  <script src="/nsp/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
  <script>
    $(function () {
      if (window.bsCustomFileInput) bsCustomFileInput.init();
    });
  </script>
</body>

</html>