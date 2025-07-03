<?php
require_once "koneksi.php";
session_start();

$id = $_GET['id'];
$tgl_dikembalikan = date('Y-m-d');

// Ambil data tanggal_kembali
$sql = "SELECT tanggal_kembali FROM TbPeminjaman WHERE id_peminjaman = ?";
$stmt = sqlsrv_query($conn, $sql, [$id]);
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

$tgl_kembali = $data['tanggal_kembali']->format('Y-m-d');
$selisih = (strtotime($tgl_dikembalikan) - strtotime($tgl_kembali)) / (60 * 60 * 24);

// Hitung denda jika telat
$denda = $selisih > 0 ? $selisih * 1000 : 0;

// Update peminjaman
$update = "UPDATE TbPeminjaman SET tanggal_dikembalikan = ?, denda = ? WHERE id_peminjaman = ?";
sqlsrv_query($conn, $update, [$tgl_dikembalikan, $denda, $id]);

echo "<script>alert('Buku berhasil dikembalikan.'); window.location='dashboard_admin.php';</script>";
?>
