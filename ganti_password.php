<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$error = "";
$success = "";

// Ambil data user saat ini
$sql = "SELECT * FROM TbUser WHERE id_user = ?";
$params = [$id_user];
$stmt = sqlsrv_query($conn, $sql, $params);
$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Jika form dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($old !== $user['password']) {
        $error = "Password lama salah.";
    } elseif (strlen($new) < 6) {
        $error = "Password baru minimal 6 karakter.";
    } elseif ($new !== $confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        $update = "UPDATE TbUser SET password = ? WHERE id_user = ?";
        $params = [$new, $id_user];
        $stmt = sqlsrv_query($conn, $update, $params);

        if ($stmt) {
            $success = "Password berhasil diganti.";
        } else {
            $error = "Gagal mengganti password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ganti Password</title>
    <style>
        body {
            background-color: #e6f2ff;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 450px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 25px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
        }

        .message.error {
            color: red;
        }

        .message.success {
            color: green;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Ganti Password</h2>

    <?php if ($error): ?>
        <p class="message error"><?php echo $error; ?></p>
    <?php elseif ($success): ?>
        <p class="message success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="password" name="old_password" placeholder="Password Lama" required>
        <input type="password" name="new_password" placeholder="Password Baru" required>
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password Baru" required>
        <button type="submit">Ganti Password</button>
    </form>

    <a href="profil.php" class="back-link">← Kembali ke Profil</a>
</div>

</body>
</html>
