<?php
session_start();
require_once "koneksi.php";

if (!$conn) {
    die("Koneksi ke database gagal: " . print_r(sqlsrv_errors(), true));
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM TbUser WHERE username = ? AND password = ?";
    $params = array($username, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt && $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'] ?? '';

        header("Location: beranda.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Perpustakaan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
        @import url("https://fonts.googleapis.com/css2?family=Noto+Sans+Symbols+2&display=swap");
        @import url("https://fonts.googleapis.com/css?family=Montserrat:400,700");
        @import url("https://fonts.cdnfonts.com/css/segoe-script");

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #cfeafd;
            overflow: hidden;
        }

        .login-container {
            width: 900px;
            height: 400px;
            margin: 130px auto;
            display: flex;
            position: relative;
            z-index: 1;
        }

        .left-panel {
            border-top-left-radius: 30px;
            border-bottom-left-radius: 30px;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.3);
            background-color: white;
            width: 50%;
            text-align: center;
            padding: 10px 10px;
            box-sizing: border-box;
        }

        .left-panel h1 {
            font-size: 25px;
        }

        .left-panel p {
            font-size: 15px;
        }

        .right-panel {
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.3);
            background-color: #6dbff9;
            width: 50%;
            padding: 50px 50px;
            box-sizing: border-box;
            color: white;
        }

        .right-panel h2 {
            margin-bottom: 30px;
            font-weight: normal;
        }

        .right-panel form input[type="text"],
        .right-panel form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 3px;
        }

        .right-panel form button {
            width: 100%;
            padding: 10px;
            background-color: #0057d8;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 3px;
            cursor: pointer;
        }

        .error {
            color: yellow;
            margin-top: 10px;
        }

        @media (max-width: 950px) {
            .login-container {
                flex-direction: column;
                width: 90%;
                height: auto;
                margin: 50px auto;
            }

            .left-panel,
            .right-panel {
                width: 100%;
            }

            .right-panel {
                padding: 30px 20px;
            }

            .left-panel img {
                width: 100px;
            }
        }

        @media (max-width: 500px) {
            .right-panel h2 {
                font-size: 20px;
            }

            .left-panel h1,
            .left-panel h3 {
                font-size: 20px;
            }

            .left-panel p {
                font-size: 13px;
            }
        }
        
        #background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        overflow: hidden;
        background-color: white;
        background-image: url(https://images.unsplash.com/photo-1615800098779-1be32e60cca3?crop=entropy&cs=srgb&fm=jpg&ixid=M3wzMjM4NDZ8MHwxfHJhbmRvbXx8fHx8fHx8fDE2OTUyNDAwMTN8&ixlib=rb-4.0.3&q=85);
        background-size: cover;
        background-repeat: no-repeat;
        user-select: none;
        pointer-events: none;
        z-index: -1;
        }
        #background > div {
        --size: 5vw;
        --symbol: "✽";

        --pos_x: 0vw;
        --duration_move: 7s;
        --delay_move: 0s;

        --duration_rotate: 1.5s;
        --delay_rotate: 0s;
        --duration_clip: 10s;
        --delay_clip: 0s;
        --hue: 0deg;

        position: absolute;
        top: 0;
        left: 0;
        font-size: clamp(15px, var(--size), 80px);
        font-family: "Noto Sans Symbols 2", sans-serif;
        transform-origin: center top;
        animation: move var(--duration_move) var(--delay_move) linear infinite normal
            both;
        }
        #background span {
        display: block;
        position: relative;
        transform-origin: center;
        transform: rotate(0deg);
        animation: rotate var(--duration_rotate) var(--delay_rotate) ease-in-out
            infinite alternate both;
        }
        #background span:after {
        content: var(--symbol);
        -webkit-text-stroke: 0.5px rgba(0, 0, 0, 0.2);
        text-stroke: 0.5px rgba(0, 0, 0, 0.2);
        line-height: 1.2;
        position: relative;
        display: block;
        color: transparent;
        background-clip: text;
        /*
        filter: contrast(0.8) brightness(1.2) hue-rotate(var(--hue))
            drop-shadow(0px 0px 0.1px gold);
        */
        filter: brightness(1.2) hue-rotate(var(--hue));
        background-image: url(https://images.unsplash.com/photo-1580822115965-0b2532068eff?&ixid=M3wzMjM4NDZ8MHwxfHJhbmRvbXx8fHx8fHx8fDE2OTUxNDUzNzJ8&ixlib=rb-4.0.3&q=100&w=200&dpr=2);
        background-position: center;
        background-size: 200px auto; /* 必要に応じて調整 */
        background-repeat: repeat;
        transform: translateZ(0);
        animation: bg1 var(--duration_clip) var(--delay_clip) linear infinite
            alternate both;
        }
        #background > div:nth-child(even) span:after {
        animation-name: bg2;
        }
        @keyframes bg1 {
        0% {
            background-position: 0% 0%;
        }
        100% {
            background-position: 100% 100%;
        }
        }
        @keyframes bg2 {
        0% {
            background-position: 100% 0%;
        }
        100% {
            background-position: 0% 100%;
        }
        }
        @keyframes rotate {
        0% {
            transform: rotate(115deg);
        }
        100% {
            transform: rotate(245deg);
        }
        }
        @keyframes move {
        0% {
            transform: translate3d(var(--pos_x), calc(0vh - var(--size)), 0);
        }
        100% {
            transform: translate3d(var(--pos_x), 100vh, 0);
        }
        }

        /* 以下、ひたすら量産 */
        #background > div:nth-child(23n + 1) {
        --symbol: "🟄";
        }
        #background > div:nth-child(23n + 2) {
        --symbol: "❉";
        }
        #background > div:nth-child(23n + 3) {
        --symbol: "🟉";
        }
        #background > div:nth-child(23n + 4) {
        --symbol: "❈";
        }
        #background > div:nth-child(23n + 5) {
        --symbol: "✣";
        }
        #background > div:nth-child(23n + 6) {
        --symbol: "🞯";
        }
        #background > div:nth-child(23n + 7) {
        --symbol: "🟎";
        }
        #background > div:nth-child(23n + 8) {
        --symbol: "♦";
        }
        #background > div:nth-child(23n + 9) {
        --symbol: "✢";
        }
        #background > div:nth-child(23n + 10) {
        --symbol: "🞵";
        }
        #background > div:nth-child(23n + 11) {
        --symbol: "✤";
        }
        #background > div:nth-child(23n + 12) {
        --symbol: "✦";
        }
        #background > div:nth-child(23n + 13) {
        --symbol: "❇";
        }
        #background > div:nth-child(23n + 14) {
        --symbol: "🞻";
        }
        #background > div:nth-child(23n + 15) {
        --symbol: "✶";
        }
        #background > div:nth-child(23n + 16) {
        --symbol: "✳";
        }
        #background > div:nth-child(23n + 17) {
        --symbol: "❊";
        }
        #background > div:nth-child(23n + 18) {
        --symbol: "🟄";
        }
        #background > div:nth-child(23n + 19) {
        --symbol: "✻";
        }
        #background > div:nth-child(23n + 20) {
        --symbol: "❋";
        }
        #background > div:nth-child(23n + 21) {
        --symbol: "✷";
        }
        #background > div:nth-child(23n + 22) {
        --symbol: "✴";
        }

        #background > div:nth-child(21n + 1) {
        --pos_x: 5vw;
        }
        #background > div:nth-child(21n + 2) {
        --pos_x: 10vw;
        }
        #background > div:nth-child(21n + 3) {
        --pos_x: 15vw;
        }
        #background > div:nth-child(21n + 4) {
        --pos_x: 20vw;
        }
        #background > div:nth-child(21n + 5) {
        --pos_x: 25vw;
        }
        #background > div:nth-child(21n + 6) {
        --pos_x: 30vw;
        }
        #background > div:nth-child(21n + 7) {
        --pos_x: 35vw;
        }
        #background > div:nth-child(21n + 8) {
        --pos_x: 40vw;
        }
        #background > div:nth-child(21n + 9) {
        --pos_x: 45vw;
        }
        #background > div:nth-child(21n + 10) {
        --pos_x: 50vw;
        }
        #background > div:nth-child(21n + 11) {
        --pos_x: 55vw;
        }
        #background > div:nth-child(21n + 12) {
        --pos_x: 60vw;
        }
        #background > div:nth-child(21n + 13) {
        --pos_x: 65vw;
        }
        #background > div:nth-child(21n + 14) {
        --pos_x: 70vw;
        }
        #background > div:nth-child(21n + 15) {
        --pos_x: 75vw;
        }
        #background > div:nth-child(21n + 16) {
        --pos_x: 80vw;
        }
        #background > div:nth-child(21n + 17) {
        --pos_x: 85vw;
        }
        #background > div:nth-child(21n + 18) {
        --pos_x: 90vw;
        }
        #background > div:nth-child(21n + 19) {
        --pos_x: 95vw;
        }
        #background > div:nth-child(21n + 20) {
        --pos_x: 100vw;
        }

        #background > div:nth-child(12n + 1) {
        --hue: 30deg;
        }
        #background > div:nth-child(12n + 2) {
        --hue: 270deg;
        }
        #background > div:nth-child(12n + 3) {
        --hue: 90deg;
        }
        #background > div:nth-child(12n + 4) {
        --hue: 150deg;
        }
        #background > div:nth-child(12n + 5) {
        --hue: 330deg;
        }
        #background > div:nth-child(12n + 6) {
        --hue: 180deg;
        }
        #background > div:nth-child(12n + 7) {
        --hue: 60deg;
        }
        #background > div:nth-child(12n + 8) {
        --hue: 210deg;
        }
        #background > div:nth-child(12n + 9) {
        --hue: 120deg;
        }
        #background > div:nth-child(12n + 10) {
        --hue: 240deg;
        }
        #background > div:nth-child(12n + 11) {
        --hue: 300deg;
        }

        #background > div:nth-child(8n + 1) {
        --delay_move: -4s;
        }
        #background > div:nth-child(8n + 2) {
        --delay_move: -5s;
        }
        #background > div:nth-child(8n + 3) {
        --delay_move: -6s;
        }
        #background > div:nth-child(8n + 4) {
        --delay_move: -1s;
        }
        #background > div:nth-child(8n + 5) {
        --delay_move: -2s;
        }
        #background > div:nth-child(8n + 6) {
        --delay_move: -3s;
        }
        #background > div:nth-child(8n + 7) {
        --delay_move: -7s;
        }

        #background > div:nth-child(9n + 1) {
        --duration_move: 7.5s;
        }
        #background > div:nth-child(9n + 2) {
        --duration_move: 8s;
        }
        #background > div:nth-child(9n + 3) {
        --duration_move: 8.5s;
        }
        #background > div:nth-child(9n + 4) {
        --duration_move: 9s;
        }
        #background > div:nth-child(9n + 5) {
        --duration_move: 5.5s;
        }
        #background > div:nth-child(9n + 6) {
        --duration_move: 6s;
        }
        #background > div:nth-child(9n + 7) {
        --duration_move: 6.5s;
        }
        #background > div:nth-child(9n + 8) {
        --duration_move: 7.8s;
        }

        #background > div:nth-child(7n + 1) {
        --delay_rotate: 0.3s;
        }
        #background > div:nth-child(7n + 2) {
        --delay_rotate: 0.6s;
        }
        #background > div:nth-child(7n + 3) {
        --delay_rotate: 0.9s;
        }
        #background > div:nth-child(7n + 4) {
        --delay_rotate: -0.3s;
        }
        #background > div:nth-child(7n + 5) {
        --delay_rotate: -0.6s;
        }
        #background > div:nth-child(7n + 6) {
        --delay_rotate: -0.9s;
        }

        #background > div:nth-child(6n + 1) {
        --duration_rotate: 1s;
        }
        #background > div:nth-child(6n + 2) {
        --duration_rotate: 1.6s;
        }
        #background > div:nth-child(6n + 3) {
        --duration_rotate: 1.1s;
        }
        #background > div:nth-child(6n + 4) {
        --duration_rotate: 1.2s;
        }
        #background > div:nth-child(6n + 5) {
        --duration_rotate: 1.3s;
        }

        #background > div:nth-child(5n + 1) {
        --size: 3vw;
        }
        #background > div:nth-child(5n + 2) {
        --size: 4vw;
        }
        #background > div:nth-child(5n + 3) {
        --size: 6vw;
        }
        #background > div:nth-child(5n + 4) {
        --size: 7vw;
        }

    </style>
</head>
<body>
    <div id="background">
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
        <div><span></span></div>
    </div>
    <div class="login-container">
        <div class="left-panel">
            <h1>PERPUSTAKAAN DIGITALKU</h1>
            <img src="./asset/library.gif" alt="Perpustakaan" width="140">
            <h3>UNIVERSITAS SERANG JAYA</h3>
            <p>Selamat datang di aplikasi perpustakaan.<br>Silakan login untuk melanjutkan, melihat daftar buku, dan mengakses layanan perpustakaan kampus.</p>
        </div>
        <div class="right-panel">
            <h2><span style="color: #007bff;">HELLO,</span><br>WELCOME BACK</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit">LOGIN</button>
                <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
                <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            </form>
        </div>
    </div>
</body>
</html>
