<?php
// /xampp/htdocs/nsp/helpers/billing_helper.php

// Ambil tarif dari tabel paket via PSB (cocokkan teks psb.paket_internet == paket.jenis_paket)
function getTarifByIdLangganan(mysqli $conn, string $idLangganan): int {
    $sql = "
        SELECT pk.harga
        FROM psb
        JOIN jenis_paket pk ON pk.jenis_paket = psb.paket_internet
        WHERE psb.id_langganan = ?
        LIMIT 1
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $idLangganan);
    $stmt->execute();
    $stmt->bind_result($harga);
    if ($stmt->fetch()) {
        $stmt->close();
        return (int)$harga;
    }
    $stmt->close();
    return 0; // fallback kalau tidak ketemu
}

/**
 * Dipanggil saat pelanggan login.
 * - Membuat tagihan bulan berjalan jika belum ada (jatuh tempo tanggal 15).
 * - Set notifikasi jika ada tagihan lewat jatuh tempo dan belum dibayar.
 * Mengembalikan array ['dibuat' => bool, 'notif' => string|null]
 */
function handleBillingOnLogin(mysqli $conn, int $idUser, string $idLangganan): array {
    date_default_timezone_set('Asia/Makassar');

    $bulanIni   = date('Y-m');        // mis. 2025-08
    $tanggal01  = $bulanIni . '-01';  // 2025-08-01
    $jatuhTempo = $bulanIni . '-15';  // 2025-08-15
    $today      = date('Y-m-d');

    // 1) cek: sudah ada tagihan bulan ini?
    $cek = $conn->prepare("SELECT 1 FROM pembayaran WHERE id_langganan=? AND bulan_tagihan=?");
    $cek->bind_param("ss", $idLangganan, $tanggal01);
    $cek->execute();
    $cek->store_result();
    $sudahAda = ($cek->num_rows > 0);
    $cek->close();

    $dibuatBaru = false;

    // 2) kalau belum ada → buat
    if (!$sudahAda) {
        $tarif = getTarifByIdLangganan($conn, $idLangganan);
        if ($tarif > 0) {
            // kolom jumlah_tagihan di kamu masih VARCHAR → cast ke string
            $jumlah = (string)$tarif;

            $ins = $conn->prepare("
                INSERT INTO pembayaran (id_user, id_langganan, bulan_tagihan, jatuh_tempo, jumlah_tagihan, status_pembayaran)
                VALUES (?,?,?,?,?, 'BELUM BAYAR')
            ");
            $ins->bind_param("issss", $idUser, $idLangganan, $tanggal01, $jatuhTempo, $jumlah);
            if ($ins->execute()) {
                $dibuatBaru = true;
            }
            $ins->close();
        }
        // kalau tarif 0 (paket tak ketemu) → skip, supaya tidak bikin tagihan 0
    }

    // 3) hitung notifikasi tunggakan (lewat jatuh tempo)
    $q = $conn->prepare("
        SELECT COUNT(*)
        FROM pembayaran
        WHERE id_langganan=? AND status_pembayaran='BELUM BAYAR' AND jatuh_tempo < ?
    ");
    $q->bind_param("ss", $idLangganan, $today);
    $q->execute();
    $q->bind_result($jml);
    $q->fetch();
    $q->close();

    $notif = null;
    if ($jml > 0) {
        $notif = "Anda memiliki {$jml} tagihan yang melewati jatuh tempo (tanggal 15).";
    }
    return ['dibuat' => $dibuatBaru, 'notif' => $notif];
}
