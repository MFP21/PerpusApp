<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - PerpusApp</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #cfeafd;
            font-family: 'Poppins', sans-serif;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .logo-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-title img {
            width: 40px;
        }

        .logo-title h1 {
            font-size: 18px;
            line-height: 1.1;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background 0.3s;
            white-space: nowrap;
        }

        .nav-links a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .logout-button {
            background-color: #ff4d4d;
            color: white;
            font-weight: bold;
        }

        .logout-button:hover {
            background-color: #e60000;
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-name {
            cursor: pointer;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background 0.3s;
            white-space: nowrap;
        }

        .profile-name:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 40px;
            background-color: white;
            min-width: 180px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1001;
            border-radius: 8px;
            overflow: hidden;
        }

        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .show {
            display: block;
        }

        .content {
            padding: 130px 40px 30px 40px;
        }

        .Table {
            padding: 10px 40px 40px 40px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            padding: 60px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            max-width: 600px;
            margin: auto;
            text-align: center;
        }

        .card h2 {
            color: #007bff;
        }

        .fade-in {
            animation: fadeIn 1s ease forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table th, table td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="logo-title">
        <img src="./asset/online-library.png" alt="Perpustakaan">
        <h1>Perpustakaan<br>DigitalKu</h1>
    </div>
    <div class="nav-links">
        <a href="daftar_buku.php">📚 Lihat Daftar Buku & Pinjam</a>

        <div class="profile-dropdown">
            <span onclick="toggleProfile()" class="profile-name">
                👤 <?php echo $_SESSION['nama_lengkap'] ?? $_SESSION['username']; ?> ▼
            </span>
            <div class="dropdown-content" id="profileDropdown">
                <a href="profil.php">Lihat Profil</a>
                <a href="edit_profil.php">Edit Profil</a>
                <a href="ganti_password.php">Ganti Password</a>
                <a class="logout-button" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="fade-in">
        <div class="card">
            <h2>Halo, <?php echo $_SESSION['nama_lengkap'] ?? $_SESSION['username']; ?>!</h2>
            <p>Selamat datang di aplikasi <strong>Perpustakaan</strong>.</p>
            <img src="./asset/read.gif" alt="Perpustakaan" width="150">
            <p>Silakan gunakan menu di atas untuk menemukan buku yang ingin kamu pinjam.</p>
        </div>
    </div>
</div>

<div class="Table">
    <h2>Riwayat Peminjaman</h2>
    <table>
        <tr>
            <th>Judul Buku</th>
            <th>Tgl Pinjam</th>
            <th>Tgl Kembali</th>
            <th>Tgl Dikembalikan</th>
            <th>Denda</th>
            <th>Aksi</th>
        </tr>
        <?php
        $id_user = $_SESSION['id_user'];
        $sql = "SELECT P.*, B.judul_buku
                FROM TbPeminjaman P
                JOIN TbBuku B ON P.id_buku = B.id_buku
                WHERE P.id_user = ?";
        $params = [$id_user];
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt && sqlsrv_has_rows($stmt)) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $tgl_pinjam = $row['tanggal_pinjam'] instanceof DateTime ? $row['tanggal_pinjam']->format('Y-m-d') : '-';
                $tgl_kembali = $row['tanggal_kembali'] instanceof DateTime ? $row['tanggal_kembali']->format('Y-m-d') : '-';
                $tgl_dikembalikan = $row['tanggal_dikembalikan'] instanceof DateTime ? $row['tanggal_dikembalikan']->format('Y-m-d') : '-';
                $denda = isset($row['denda']) ? "Rp " . number_format($row['denda']) : 'Rp 0';

                echo "<tr>
                        <td>{$row['judul_buku']}</td>
                        <td>$tgl_pinjam</td>
                        <td>$tgl_kembali</td>
                        <td>$tgl_dikembalikan</td>
                        <td>$denda</td>
                        <td>";
                if (!$row['tanggal_dikembalikan']) {
                    echo "<a href='kembalikan_buku.php?id={$row['id_peminjaman']}'>Kembalikan</a>";
                } else {
                    echo "-";
                }
                echo "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Belum ada peminjaman buku.</td></tr>";
        }
        ?>
    </table>
</div>

<script>
function toggleProfile() {
    document.getElementById("profileDropdown").classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('.profile-name')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var open = dropdowns[i];
            if (open.classList.contains('show')) {
                open.classList.remove('show');
            }
        }
    }
}

window.addEventListener("load", function () {
    document.querySelectorAll('.fade-in').forEach(function (el) {
        el.style.opacity = 1;
    });
});
</script>
</body>
</html>
