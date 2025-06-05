<?php
session_start();
include "connect.php";
date_default_timezone_set('Asia/Jakarta');

$batas_waktu = 21600; // 6 jam

if (!isset($_SESSION["username"]) || (isset($_SESSION['waktu_terakhir']) && (time() - $_SESSION['waktu_terakhir'] > $batas_waktu))) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $id_admin = $_SESSION['id_admin'] ?? null;

    // Ambil data arsip
    $stmt_arsip = $conn->prepare("SELECT nama_perusahaan, tgl_datang, jth_tempo, tgl_faktur, total, no_faktur FROM arsip WHERE id_arsip = ?");
    $stmt_arsip->bind_param("i", $id);
    $stmt_arsip->execute();
    $result = $stmt_arsip->get_result();

    if ($result && $result->num_rows > 0) {
        $arsip = $result->fetch_assoc();
        $nama_perusahaan = $arsip['nama_perusahaan'];
        $tgl_datang = $arsip['tgl_datang'];
        $jth_tempo = $arsip['jth_tempo'];
        $tgl_faktur = $arsip['tgl_faktur'];
        $total = $arsip['total'];
        $no_faktur = $arsip['no_faktur'];

        // Ambil detail produk lengkap untuk undo
        $stmt_detail = $conn->prepare("SELECT * FROM detail_produk WHERE id_arsip = ?");
        $stmt_detail->bind_param("i", $id);
        $stmt_detail->execute();
        $detail_result = $stmt_detail->get_result();

        $detail_data = [];
        while ($row = $detail_result->fetch_assoc()) {
            $detail_data[] = $row; // simpan semua kolom untuk undo
        }

        // sebelum hapus, simpan data ke undo stack session
        $_SESSION['undo_stack'][] = [
            'arsip' => [
                'id_arsip' => $id,
                'nama_perusahaan' => $nama_perusahaan,
                'tgl_datang' => $tgl_datang,
                'jth_tempo' => $jth_tempo,
                'tgl_faktur' => $tgl_faktur,
                'total' => $total,
                'no_faktur' => $no_faktur
            ],
            'detail' => $detail_data
        ];
        
        // lalu hapus semuanya
        // Hapus detail_produk
        $stmt_delete_detail = $conn->prepare("DELETE FROM detail_produk WHERE id_arsip = ?");
        $stmt_delete_detail->bind_param("i", $id);
        $stmt_delete_detail->execute();

        // Hapus arsip
        $stmt_delete_arsip = $conn->prepare("DELETE FROM arsip WHERE id_arsip = ?");
        $stmt_delete_arsip->bind_param("i", $id);
        $stmt_delete_arsip->execute();

        // Log aktivitas
        $log_aksi = "Hapus arsip: $nama_perusahaan, Faktur: $no_faktur, Tgl datang: $tgl_datang, " . count($detail_data) . " produk";
        $stmt_log = $conn->prepare("INSERT INTO log_aktivitas (id_admin, aksi) VALUES (?, ?)");
        $stmt_log->bind_param("is", $id_admin, $log_aksi);
        $stmt_log->execute();

        // Redirect
        header("Location: daftarbarang.php");
        exit();
    } else {
        // Log jika arsip tidak ditemukan
        $log_aksi = "Gagal hapus: Arsip ID $id tidak ditemukan";
        $stmt_log = $conn->prepare("INSERT INTO log_aktivitas (id_admin, aksi) VALUES (?, ?)");
        $stmt_log->bind_param("is", $id_admin, $log_aksi);
        $stmt_log->execute();

        echo "Arsip tidak ditemukan!";
    }
} else {
    echo "ID tidak ditemukan!";
}
