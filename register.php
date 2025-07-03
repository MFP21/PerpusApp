<?php
require_once "koneksi.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password != $confirm) {
        $message = "Konfirmasi password tidak cocok!";
    } elseif (!preg_match('/^08[0-9]{7,11}$/', $no_hp)) {
        $message = "Nomor telepon harus diawali dengan 08 dan hanya angka!";
    // proses registrasi...
    } else {
        $sql = "SELECT * FROM TbUser WHERE username = ?";
        $params = array($username);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt && sqlsrv_has_rows($stmt)) {
            $message = "Username sudah terdaftar!";
        } else {
            $insert = "INSERT INTO TbUser (username, password, nama_lengkap, no_hp)
                       VALUES (?, ?, ?, ?)";
            $params = [$username, $password, $nama, $no_hp];
            $result = sqlsrv_query($conn, $insert, $params);

            if ($result) {
                $message = "Registrasi berhasil! Silakan login.";
            } else {
                $errors = sqlsrv_errors();
                $message = "Registrasi gagal: " . ($errors ? $errors[0]['message'] : '');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #acb6e5);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card {
            width: 100%;
            max-width: 500px;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 600;
        }
        .btn-primary {
            width: 100%;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="card">
    <h3 class="text-center mb-4">Form Registrasi Pengguna</h3>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nomor HP</label>
            <input type="text" class="form-control" name="no_hp" pattern="08[0-9]{7,11}" title="Nomor HP harus diawali 08 dan hanya angka" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" name="confirm" required>
        </div>
        <button type="submit" class="btn btn-primary">Daftar</button>
        <div class="mt-3 text-center">
            Sudah punya akun? <a href="login.php" class="text-decoration-none">Login di sini</a>
        </div>
    </form>
</div>

</body>
</html>
