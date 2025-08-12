<?php
$tarif_bulanan = 150000; // Paket langganan per bulan
$tanggal_pemasangan = '2025-07-25'; // contoh pelanggan pasang tanggal 25 Juli
$tanggal_jatuh_tempo = '2025-08-15'; // jatuh tempo pertama kali setelah pemasangan

$tarif_bulanan = 150000;

// Tanggal pemasangan dari pelanggan
$tanggal_pemasangan = '2025-07-25'; // contoh
$tanggal_pasang = new DateTime($tanggal_pemasangan);

// Tentukan jatuh tempo pertama (15 bulan berikutnya jika lewat tanggal 15)
$jatuh_tempo = new DateTime($tanggal_pasang->format('Y-m') . '-15');
if ((int)$tanggal_pasang->format('d') > 15) {
    $jatuh_tempo->modify('+1 month');
}

// Hitung selisih hari dari pemasangan hingga jatuh tempo
$interval = $tanggal_pasang->diff($jatuh_tempo);
$jumlah_hari_prorata = $interval->days;

// Total hari dalam bulan pemasangan
$total_hari_bulan = (int)$tanggal_pasang->format('t');

// Hitung tagihan prorata
$tagihan_prorata = round(($jumlah_hari_prorata / $total_hari_bulan) * $tarif_bulanan);

// Output
echo "Tanggal Pemasangan: " . $tanggal_pasang->format('d-m-Y') . "<br>";
echo "Jatuh Tempo Pertama: " . $jatuh_tempo->format('d-m-Y') . "<br>";
echo "Jumlah Hari Aktif Sebelum Jatuh Tempo: $jumlah_hari_prorata hari<br>";
echo "Tagihan Prorata: Rp. " . number_format($tagihan_prorata, 0, ',', '.') . "<br>";
?>