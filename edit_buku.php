<?php
session_start();
require_once "koneksi.php";

$id_buku = $_GET['id'] ?? null;
if (!$id_buku) {
    header("Location: dashboard_admin.php");
    exit();
}

$sql = "SELECT * FROM TbBuku WHERE id_buku = ?";
$stmt = sqlsrv_query($conn, $sql, [$id_buku]);
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$data) {
    echo "Buku tidak ditemukan.";
    exit();
}

// Proses update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul_buku'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun_terbit'];
    $jumlah = $_POST['jumlah'];
    $gambar = $data['gambar']; // default

    // Jika upload gambar baru
    if (!empty($_FILES['gambar']['name'])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["gambar"]["name"]);
        $targetFile = $targetDir . time() . "_" . $fileName;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
            $gambar = basename($targetFile);
        }
    }

    $update = "UPDATE TbBuku SET judul_buku=?, penulis=?, penerbit=?, tahun_terbit=?, jumlah=?, gambar=? WHERE id_buku=?";
    $params = [$judul, $penulis, $penerbit, $tahun, $jumlah, $gambar, $id_buku];
    $stmt = sqlsrv_query($conn, $update, $params);

    if ($stmt) {
        header("Location: dashboard_admin.php");
        exit();
    } else {
        echo "Gagal memperbarui buku.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        form { max-width: 500px; margin: auto; background: #f4f4f4; padding: 20px; border-radius: 8px; }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%; padding: 10px; margin-bottom: 12px;
        }
        button { padding: 10px 20px; background-color: #007bff; color: white; border: none; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h2>Edit Buku</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Judul Buku:</label>
        <input type="text" name="judul_buku" value="<?= htmlspecialchars($data['judul_buku']) ?>" required>

        <label>Penulis:</label>
        <input type="text" name="penulis" value="<?= htmlspecialchars($data['penulis']) ?>" required>

        <label>Penerbit:</label>
        <input type="text" name="penerbit" value="<?= htmlspecialchars($data['penerbit']) ?>" required>

        <label>Tahun Terbit:</label>
        <input type="number" name="tahun_terbit" value="<?= htmlspecialchars($data['tahun_terbit']) ?>" required>

        <label>Jumlah:</label>
        <input type="number" name="jumlah" value="<?= htmlspecialchars($data['jumlah']) ?>" required>

        <label>Ganti Gambar (opsional):</label>
        <input type="file" name="gambar">

        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
