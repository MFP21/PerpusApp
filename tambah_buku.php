<?php
session_start();
require_once "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul_buku'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun_terbit'];
    $jumlah = $_POST['jumlah'];

    // Upload gambar
    $gambar_nama = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $gambar_folder = "uploads/" . basename($gambar_nama);

    if (move_uploaded_file($gambar_tmp, $gambar_folder)) {
        $sql = "INSERT INTO TbBuku (judul_buku, penulis, penerbit, tahun_terbit, jumlah, gambar)
                VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$judul, $penulis, $penerbit, $tahun, $jumlah, $gambar_nama];
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt) {
            echo "<script>alert('Buku berhasil ditambahkan.'); window.location.href='dashboard_admin.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan buku.');</script>";
        }
    } else {
        echo "<script>alert('Upload gambar gagal. Pastikan folder uploads/ bisa ditulisi.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .form-title {
            color: #4f46e5;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h3 class="text-center form-title mb-4">📚 Tambah Buku Baru</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="judul_buku" class="form-label">Judul Buku</label>
                <input type="text" class="form-control" name="judul_buku" required>
            </div>

            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis</label>
                <input type="text" class="form-control" name="penulis" required>
            </div>

            <div class="mb-3">
                <label for="penerbit" class="form-label">Penerbit</label>
                <input type="text" class="form-control" name="penerbit" required>
            </div>

            <div class="mb-3">
                <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                <input type="number" class="form-control" name="tahun_terbit" required>
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" name="jumlah" required>
            </div>

            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Buku</label>
                <input type="file" class="form-control" name="gambar" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Tambah Buku</button>
        </form>
    </div>
</div>

</body>
</html>
