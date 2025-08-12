<?php
include "/xampp/htdocs/nsp/services/koneksi.php";
date_default_timezone_set('Asia/Makassar');

$bulanIni = date('Y-m');              // contoh: 2025-08
$tanggal01 = $bulanIni . '-01';       // 2025-08-01
$jatuhTempo = $bulanIni . '-15';      // 2025-08-15'

/*
 Ambil pelanggan aktif + paket.
 id_langganan ada di pelanggan dan psb—pakai pelanggan sbg sumber utama,
 join ke psb untuk dapat paket_internet.
*/
$sql = "
SELECT p.id_user, p.id_langganan, psb.paket_internet
FROM pelanggan p
LEFT JOIN psb ON psb.id_langganan = p.id_langganan
WHERE p.status_pelanggan = 'AKTIF'
";
$res = $conn->query($sql);

while ($row = $res->fetch_assoc()) {
    $idUser = (int)$row['id_user'];
    $idLang = $row['id_langganan'];
    $paket  = $row['paket_internet'] ?? '';
    $tarif  = getTarifByPaket($paket);

    // cek sudah ada?
    $cek = $conn->prepare("SELECT 1 FROM pembayaran WHERE id_langganan=? AND bulan_tagihan=?");
    $cek->bind_param("ss", $idLang, $tanggal01);
    $cek->execute(); $cek->store_result();

    if ($cek->num_rows === 0) {
        $ins = $conn->prepare("
            INSERT INTO pembayaran (id_user, id_langganan, bulan_tagihan, jatuh_tempo, jumlah_tagihan, status_pembayaran)
            VALUES (?,?,?,?,?, 'BELUM BAYAR')
        ");
        $jumlah = (string)$tarif; // kolom kamu varchar—isi angka sebagai string
        $ins->bind_param("issss", $idUser, $idLang, $tanggal01, $jatuhTempo, $jumlah);
        $ins->execute();
        $ins->close();
    }
    $cek->close();
}
echo "Generate OK\n";

// function getTarifByPaket(string $paket): int {
//     switch (strtoupper(trim($paket))) {
//         case 'PAKET A': return 200000;
//         case 'PAKET B': return 275000;
//         case 'PAKET C': return 350000;
//         default: return 275000;
//     }
// }
