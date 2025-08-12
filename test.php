<?php
require('./library/fpdf.php');

// Inisialisasi FPDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Dummy Data
$nama_perusahaan = "PT. Net Sun Power";
$alamat_perusahaan = "Jl. Kemerdekaan No. 123, Jakarta";
$telepon_perusahaan = "021-555999";
$email_perusahaan = "info@netsunpower.co.id";
$nama_pelanggan = "Kristian Putra";
$id_langganan = "NSP123456";
$jenis_layanan = "Paket 12 Perangkat";
$tanggal_invoice = date('d-m-Y');
$jumlah_tagihan = 275000;
$rekening_pembayaran = "112299008 a.n PT. Net Sun Power (Mandiri)";
$tanggal_jatuh_tempo = "21-07-2025";

// Header Perusahaan
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, $nama_perusahaan, 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(190, 5, $alamat_perusahaan, 0, 1, 'C');
$pdf->Cell(190, 5, "Telp: $telepon_perusahaan | Email: $email_perusahaan", 0, 1, 'C');

// Garis pembatas horizontal
$pdf->SetDrawColor(0, 0, 0); // warna hitam
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());

$pdf->Ln(10);

// Info Pelanggan dan Invoice
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 6, "Invoice Untuk:", 0, 0);
$pdf->Cell(90, 6, "Tanggal: $tanggal_invoice", 0, 1, 'R');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(100, 6, "Nama Pelanggan: $nama_pelanggan", 0, 0);
$pdf->Cell(90, 6, "ID Langganan: $id_langganan", 0, 1, 'R');
$pdf->Cell(100, 6, "Layanan: $jenis_layanan", 0, 0);
$pdf->Cell(90, 6, "Jatuh Tempo: $tanggal_jatuh_tempo", 0, 1, 'R');

$pdf->Ln(10);

// Tabel Header Tanpa Border
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(20, 8, 'Qty', 0, 0, 'L');
$pdf->Cell(110, 8, 'Deskripsi', 0, 0, 'L');
$pdf->Cell(30, 8, 'Harga', 0, 0, 'R');
$pdf->Cell(30, 8, 'Subtotal', 0, 1, 'R');

// Tabel Isi Tanpa Border
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(20, 8, '1', 0, 0, 'L');
$pdf->Cell(110, 8, 'Pembayaran Layanan - ' . $jenis_layanan, 0, 0, 'L');
$pdf->Cell(30, 8, 'Rp. ' . number_format($jumlah_tagihan, 0, ',', '.'), 0, 0, 'R');
$pdf->Cell(30, 8, 'Rp. ' . number_format($jumlah_tagihan, 0, ',', '.'), 0, 1, 'R');

$pdf->Ln(5);

// Garis pembatas atas total pembayaran
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(4);

// Total Tanpa Border
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(160, 8, 'Total Pembayaran', 0, 0, 'R');
$pdf->Cell(30, 8, 'Rp. ' . number_format($jumlah_tagihan, 0, ',', '.'), 0, 1, 'R');

$pdf->Ln(10);

// Metode Pembayaran
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(190, 6, "Silakan lakukan pembayaran ke rekening berikut:\n$rekening_pembayaran");

$pdf->Ln(8);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(190, 6, "Harap simpan bukti pembayaran sebagai validasi resmi.", 0, 1, 'C');

// Output PDF
$pdf->Output('I', 'invoice-nsp.pdf');
?>
