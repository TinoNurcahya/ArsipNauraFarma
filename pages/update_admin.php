<?php
include "session_check.php";
include "connect.php";


// Ambil data dari form
$id_admin = $_POST['id_admin'];
$username = htmlspecialchars(strip_tags(trim($_POST['username'])));
$password = htmlspecialchars(strip_tags(trim($_POST['password'])));
$no_hp = htmlspecialchars(strip_tags(trim($_POST['no_hp'])));
$alamat = htmlspecialchars(strip_tags(trim($_POST['alamat'])));

// Validasi nomor HP
if (!empty($no_hp) && !preg_match('/^[0-9]+$/', $no_hp)) {
    die("Nomor HP hanya boleh berisi angka.");
}

// Ambil foto lama dari database
$stmt_foto = $conn->prepare("SELECT foto FROM admin WHERE id_admin = ?");
$stmt_foto->bind_param("i", $id_admin);
$stmt_foto->execute();
$result_foto = $stmt_foto->get_result();
$data_foto = $result_foto->fetch_assoc();
$foto_lama = $data_foto['foto'] ?? 'default.jpg';
$stmt_foto->close();

// Proses upload foto jika ada
$nama_file_baru = $foto_lama; // Menggunakan foto lama jika tidak ada foto baru
if (!empty($_FILES['foto']['name'])) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $file_type = $_FILES['foto']['type'];
    $file_size = $_FILES['foto']['size'];

    if (!in_array($file_type, $allowed_types)) {
        die("File harus berupa JPG atau PNG.");
    }

    if ($file_size > 2 * 1024 * 1024) {
        die("Ukuran file maksimal 2MB.");
    }

    // Hapus foto lama jika bukan default
    if (!empty($foto_lama) && $foto_lama !== 'default.jpg') {
        @unlink("../img/" . $foto_lama);  // Hapus foto lama
    }

    // Simpan file baru
    $nama_file_baru = uniqid() . '_' . basename($_FILES['foto']['name']);
    $lokasi_simpan = "../img/" . $nama_file_baru;

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $lokasi_simpan)) {
        die("Gagal mengupload foto.");
    }
}

// Siapkan query update
if (!empty($password)) {
    // Update password jika ada
    $query_update = "UPDATE admin SET username=?, password=?, no_hp=?, alamat=?, foto=? WHERE id_admin=?";
    $stmt = mysqli_prepare($conn, $query_update);
    mysqli_stmt_bind_param($stmt, "sssssi", $username, $password, $no_hp, $alamat, $nama_file_baru, $id_admin);
} else {
    // Update tanpa password jika kosong
    $query_update = "UPDATE admin SET username=?, no_hp=?, alamat=?, foto=? WHERE id_admin=?";
    $stmt = mysqli_prepare($conn, $query_update);
    mysqli_stmt_bind_param($stmt, "ssssi", $username, $no_hp, $alamat, $nama_file_baru, $id_admin);
}

// Eksekusi query
if (mysqli_stmt_execute($stmt)) {
    $_SESSION["username"] = $username;
    $_SESSION["login_success"] = "Data admin berhasil diperbarui.";

    // Tambah log aktivitas
    $aksi = "Mengubah profil";
    $waktu = date("Y-m-d H:i:s");
    $log_stmt = $conn->prepare("INSERT INTO log_aktivitas (id_admin, aksi, waktu) VALUES (?, ?, ?)");
    $log_stmt->bind_param("iss", $id_admin, $aksi, $waktu);
    $log_stmt->execute();
    $log_stmt->close();
} else {
    $_SESSION["login_success"] = "Terjadi kesalahan saat memperbarui data.";
}

mysqli_stmt_close($stmt);
header("Location: dashboard.php");
exit();
?>