<?php
include "session_check.php";
include "connect.php";


if (isset($_SESSION["id_admin"])) {
    $id_admin = $_SESSION["id_admin"];
    
    // Catat log aktivitas logout
    $aksi = "Logout dari sistem";
    $waktu = date("Y-m-d H:i:s");

    $log_stmt = $conn->prepare("INSERT INTO log_aktivitas (id_admin, aksi, waktu) VALUES (?, ?, ?)");
    $log_stmt->bind_param("iss", $id_admin, $aksi, $waktu);
    $log_stmt->execute();
    $log_stmt->close();

    // Hapus semua data sesi
    session_unset();

    // Hancurkan sesi
    session_destroy();
}

// Redirect ke halaman login
header("Location: login.php");
exit();
?>
