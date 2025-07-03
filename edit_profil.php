<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Ambil data user saat ini
$sql = "SELECT * FROM TbUser WHERE id_user = ?";
$params = [$id_user];
$stmt = sqlsrv_query($conn, $sql, $params);
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Simpan perubahan jika form dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_hp = $_POST['no_hp'];

    // Validasi awalan nomor HP
    if (!preg_match('/^08\d{8,}$/', $no_hp)) {
        $error = "Nomor HP harus diawali dengan 08 dan minimal 10 digit.";
    } else {
        // Upload foto jika ada
        $fotoBaru = $data['foto'] ?? null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $fotoBaru = "foto_user_" . $id_user . "_" . time() . "." . $ext;
                move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $fotoBaru);
            } else {
                $error = "Format foto tidak didukung. Gunakan JPG, JPEG, atau PNG.";
            }
        }

        // Update data ke database
        if (!isset($error)) {
            $update_sql = "UPDATE TbUser SET nama_lengkap = ?, no_hp = ?, foto = ? WHERE id_user = ?";
            $update_params = [$nama_lengkap, $no_hp, $fotoBaru, $id_user];
            $update_stmt = sqlsrv_query($conn, $update_sql, $update_params);

            if ($update_stmt) {
                $_SESSION['nama_lengkap'] = $nama_lengkap;
                header("Location: profil.php");
                exit();
            } else {
                $error = "Gagal menyimpan perubahan.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <style>
        body {
            background-color: #e6f2ff;
            font-family: 'Poppins', sans-serif;
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 500px;
            margin: 80px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        input[type="text"], input[type="file"] {
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

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            text-align: center;
        }

        .preview {
            text-align: center;
        }

        .preview img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 10px;
            border: 2px solid #007bff;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Profil</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Nama Lengkap</label>
        <input type="text" name="nama_lengkap" required value="<?php echo htmlspecialchars($data['nama_lengkap'] ?? ''); ?>">

        <label>No. HP</label>
        <input type="text" name="no_hp" required value="<?php echo htmlspecialchars($data['no_hp'] ?? ''); ?>">

        <label>Foto Profil</label>
        <input type="file" name="foto" accept="image/*">

        <div class="preview">
            <p>Foto Saat Ini:</p>
            <img src="uploads/<?php echo $data['foto'] ?? 'default.png'; ?>" alt="Foto Profil">
        </div>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <a class="back-link" href="profil.php">← Kembali ke Profil</a>
</div>

</body>
</html>
