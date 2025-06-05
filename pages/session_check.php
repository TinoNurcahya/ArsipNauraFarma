<?php
session_start();

date_default_timezone_set('Asia/Jakarta');

$batas_waktu = 21600; // 6 jam
$current_file = basename($_SERVER['PHP_SELF']);
$public_pages = ['login.php', 'register.php'];

// Generate CSRF token jika belum ada
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!in_array($current_file, $public_pages)) {
    // Cek login untuk halaman selain public
    if (!isset($_SESSION["username"]) || !isset($_SESSION["id_admin"])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    // Cek idle timeout
    if (isset($_SESSION['waktu_terakhir'])) {
        $waktu_berjalan = time() - $_SESSION['waktu_terakhir'];
        if ($waktu_berjalan > $batas_waktu) {
            session_destroy();
            header("Location: login.php");
            exit();
        }
    }
    $_SESSION['waktu_terakhir'] = time();
}
?>