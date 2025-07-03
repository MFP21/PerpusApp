<?php
session_start();
require_once "koneksi.php";

// Cek apakah user adalah admin
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - PerpusApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            padding-top: 40px;
        }
        .section-title {
            margin-bottom: 20px;
            color: #4f46e5;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .table th {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center text-primary mb-5">📚 Dashboard Admin - PerpusApp</h1>

    <!-- Data Buku -->
    <div class="card mb-5">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📘 Data Buku</h5>
            <a href="tambah_buku.php" class="btn btn-light btn-sm">+ Tambah Buku</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Judul</th><th>Penulis</th><th>Penerbit</th><th>Tahun</th><th>Jumlah</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = sqlsrv_query($conn, "SELECT * FROM TbBuku");
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<tr>
                            <td>{$row['judul_buku']}</td>
                            <td>{$row['penulis']}</td>
                            <td>{$row['penerbit']}</td>
                            <td>{$row['tahun_terbit']}</td>
                            <td>{$row['jumlah']}</td>
                            <td>
                                <a class='btn btn-sm btn-warning' href='edit_buku.php?id={$row['id_buku']}'>Edit</a>
                                <a class='btn btn-sm btn-danger' href='hapus_buku.php?id={$row['id_buku']}' onclick=\"return confirm('Hapus buku ini?')\">Hapus</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Data Pengguna -->
    <div class="card mb-5">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">👥 Data Pengguna</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Nomor Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = sqlsrv_query($conn, "SELECT * FROM TbUser WHERE username != 'admin'");
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<tr>
                            <td>{$row['username']}</td>
                            <td>{$row['nama_lengkap']}</td>
                            <td>{$row['no_hp']}</td>
                            <td>
                                <a class='btn btn-sm btn-warning' href='edit_pengguna.php?id_user={$row['id_user']}'>Edit</a>
                                <a class='btn btn-sm btn-danger' href='hapus_pengguna.php?id_user={$row['id_user']}' onclick=\"return confirm('Yakin ingin menghapus pengguna ini?')\">Hapus</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Peminjaman -->
    <div class="card mb-5">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">📖 Riwayat Peminjaman</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Username</th><th>Buku</th><th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th><th>Tgl Dikembalikan</th><th>Denda</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT P.*, B.judul_buku, U.username
                        FROM TbPeminjaman P
                        JOIN TbBuku B ON P.id_buku = B.id_buku
                        JOIN TbUser U ON P.id_user = U.id_user";
                $stmt = sqlsrv_query($conn, $sql);
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>
                        <td>{$row['username']}</td>
                        <td>{$row['judul_buku']}</td>
                        <td>{$row['tanggal_pinjam']->format('Y-m-d')}</td>
                        <td>{$row['tanggal_kembali']->format('Y-m-d')}</td>
                        <td>" . ($row['tanggal_dikembalikan'] ? $row['tanggal_dikembalikan']->format('Y-m-d') : '-') . "</td>
                        <td>Rp " . number_format($row['denda']) . "</td>
                        <td>
                            <a class='btn btn-sm btn-info' href='kembalikan_buku.php?id={$row['id_peminjaman']}'>Kembalikan</a>
                            <a class='btn btn-sm btn-danger' href='hapus_peminjaman.php?id={$row['id_peminjaman']}' onclick=\"return confirm('Yakin ingin menghapus?')\">Hapus</a>
                        </td>
                    </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
