<?php
require_once "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM TbBuku WHERE id_buku = ?";
    $stmt = sqlsrv_query($conn, $sql, [$id]);

    if ($stmt) {
        echo "<script>alert('buku berhasil dihapus.'); window.location.href='dashboard_admin.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus buku.'); window.location.href='dashboard_admin.php';</script>";
    }
} else {
    header("Location: dashboard_admin.php");
}

