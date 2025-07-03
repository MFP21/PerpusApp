<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_SESSION['id_user'];
    $id_buku = $_POST['id_buku'] ?? null;
    $tanggal_pinjam = $_POST['tanggal_pinjam'] ?? date('Y-m-d');
    $tanggal_kembali = $_POST['tanggal_kembali'] ?? date('Y-m-d', strtotime('+2 days'));

    // Validasi sederhana
    if (!$id_user || !$id_buku) {
        die("Data tidak lengkap!");
    }

    // Simpan ke TbPeminjaman
    $sql = "INSERT INTO TbPeminjaman (id_user, id_buku, tanggal_pinjam, tanggal_kembali) 
            VALUES (?, ?, ?, ?)";
    $params = [$id_user, $id_buku, $tanggal_pinjam, $tanggal_kembali];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        echo "<script>alert('Peminjaman berhasil!'); window.location='beranda.php';</script>";
    } else {
        echo "<pre>";
        print_r(sqlsrv_errors(), true);
        echo "</pre>";
    }
}
?>
