<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$sql = "SELECT * FROM TbUser WHERE id_user = ?";
$params = [$id_user];
$stmt = sqlsrv_query($conn, $sql, $params);
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

$foto = $data['foto'] ?? '';
$foto_path = !empty($foto) && file_exists("uploads/$foto") ? "uploads/$foto" : "uploads/default.png";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya - PerpusApp</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        body {
            background-color: #e6f2ff;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 80px auto;
            padding: 25px 15px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 50px;
            max-width: 100%;
        }

        .profile-info {
            flex: 1;
            max-width: 65%;
        }

        .profile-info p {
            margin-left: 7em;
            font-size: 15px;
        }

        .profile-info label {
            font-weight: bold;
            color: #333;
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
            flex-shrink: 0;
            margin-right: 90px;
        }

        .actions {
            margin-top: 30px;
            text-align: center;
        }

        .actions a {
            text-decoration: none;
            padding: 10px 18px;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            margin: 0 10px;
            transition: 0.3s;
        }

        .actions a:hover {
            background-color: #0056b3;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 600px) {
            .profile-card {
                flex-direction: column-reverse;
                align-items: center;
                text-align: center;
            }

            .profile-info {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Profil Saya</h2>

    <div class="profile-card">
        <!-- Data pengguna -->
        <div class="profile-info">
            <p><label>Nama Lengkap:</label><br><?php echo htmlspecialchars($data['nama_lengkap'] ?? '-'); ?></p>
            <p><label>Username:</label><br><?php echo htmlspecialchars($data['username'] ?? '-'); ?></p>
            <p><label>No. HP:</label><br><?php echo htmlspecialchars($data['no_hp'] ?? '-'); ?></p>
        </div>

        <!-- Foto profil -->
        <img class="profile-photo" src="<?php echo $foto_path; ?>" alt="Foto Profil">
    </div>

    <div class="actions">
        <a href="edit_profil.php">Edit Profil</a>
        <a href="ganti_password.php">Ganti Password</a>
    </div>

    <a href="beranda.php" class="back-link">← Kembali ke Beranda</a>
</div>

</body>
</html>
