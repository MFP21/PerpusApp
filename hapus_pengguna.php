<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id_user'])) {
    die("ID pengguna tidak ditemukan.");
}

$id_user = $_GET['id_user'];

if ($_GET['id_user'] == $_SESSION['id_user']) {
    die("Anda tidak dapat menghapus akun Anda sendiri.");
}

$sql = "DELETE FROM TbUser WHERE id_user = ?";
$params = [$id_user];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt) {
    echo "<script>alert('Pengguna berhasil dihapus'); window.location.href='dashboard_admin.php';</script>";
} else {
    echo "Gagal menghapus pengguna: " . print_r(sqlsrv_errors(), true);
}
?>
