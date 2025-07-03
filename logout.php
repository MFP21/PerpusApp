<?php
session_start();         // Mulai session
session_unset();         // Hapus semua variabel session
session_destroy();       // Hancurkan session

header("Location: login.php"); // Arahkan kembali ke login
exit();
