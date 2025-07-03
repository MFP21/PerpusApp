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

$sql = "SELECT * FROM TbUser WHERE id_user = ?";
$params = [$id_user];
$stmt = sqlsrv_query($conn, $sql, $params);
$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$user) {
    die("Pengguna tidak ditemukan.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $no_hp = $_POST['no_hp'];

    $update = "UPDATE TbUser SET nama_lengkap = ?, username = ?, no_hp = ? WHERE id_user = ?";
    $paramsUpdate = [$nama, $username, $no_hp, $id_user];
    $stmtUpdate = sqlsrv_query($conn, $update, $paramsUpdate);

    if ($stmtUpdate) {
        echo "<script>alert('Data pengguna berhasil diperbarui'); window.location.href='dashboard_admin.php';</script>";
    } else {
        echo "Gagal update: " . print_r(sqlsrv_errors(), true);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            font-family: 'Segoe UI', sans-serif;
        }
        .edit-form {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #4f46e5;
            border: none;
        }
        .btn-primary:hover {
            background-color: #4338ca;
        }
        .form-title {
            font-weight: 600;
            color: #4f46e5;
        }
    </style>
</head>
<body>
    <div class="container py-5 d-flex justify-content-center">
        <div class="col-md-6 edit-form">
            <h3 class="text-center mb-4 form-title">✏️ Edit Data Pengguna</h3>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap:</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username:</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. HP:</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($user['no_hp']) ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="dashboard_admin.php" class="btn btn-secondary">← Kembali</a>
                    <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
