<?php
include "session_check.php";
include "connect.php";


// PROSES PENDAFTARAN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Verifikasi CSRF Token
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token tidak valid!');
  }
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $konf_pass = $_POST["konf_pass"];

  // Cek apakah email sudah ada di database
  $stmt = $conn->prepare("SELECT id_admin FROM admin WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Email sudah digunakan, silahkan gunakan yang lain!',
                confirmButtonColor: '#2d336b'
            });
        });
        </script>";
    $stmt->close();
  } elseif ($password !== $konf_pass) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Kata sandi tidak cocok',
                text: 'Pastikan kata sandi yang kamu masukkan sama!',
                confirmButtonColor: '#2d336b'
            });
        });
        </script>";
  } else {
    // Jika belum ada, simpan data ke database
    $stmt_insert = $conn->prepare("INSERT INTO admin (username, email, password) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("sss", $username, $email, $password);

    if ($stmt_insert->execute()) {
      // Ambil id_admin yang baru saja ditambahkan
      $id_admin = $conn->insert_id;

      // Pastikan id_admin valid
      if ($id_admin) {
        // Tambah log aktivitas pendaftaran
        $aksi = "Pendaftaran akun baru";
        $waktu = date("Y-m-d H:i:s");
        $log_stmt = $conn->prepare("INSERT INTO log_aktivitas (id_admin, aksi, waktu) VALUES (?, ?, ?)");
        $log_stmt->bind_param("iss", $id_admin, $aksi, $waktu);
        $log_stmt->execute();
        $log_stmt->close();
        echo "<script>
          document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Berhasil mendaftar',
              showConfirmButton: false,
              timer: 3000
            }).then(() => { window.location.href='login.php'; });
          });
          </script>";
      }
    } else {
      echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: ' Sistem Error',
                    text: 'Terjadi kesalahan saat mendaftar!',
                    confirmButtonColor: '#2d336b'
                });
            });
            </script>";
    }
    $stmt_insert->close();
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../img/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/style.css">
  <title>Registrasi - Apotek Naura Farma</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
    rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-image: url(../img/antibiotic.jpg);
      background-repeat: no-repeat;
      background-size: cover;
      backdrop-filter: blur(15px);
    }

  </style>
</head>

<body>
  <!-- Halaman Registrasi -->
  <div class="container">
    <div class="kiri">
      <img src="../img/antibiotic.jpg" alt="Background" />
    </div>
    <div class="kananisi">
      <div class="logo">
        <img src="../img/logo2.png" alt="Logo Apotek" />
        <div class="text-logo">
          <h3>DATA PENGARSIPAN</h3>
          <h1>APOTEK NAURA FARMA</h1>
        </div>
      </div>
      <div class="login-box">
        <h2>Daftar</h2>
        <hr />
        <form method="POST">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

          <label for="username">Nama Pengguna</label>
          <input type="text" id="username" name="username" placeholder="Masukkan Nama" required autofocus />

          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Masukkan email" required />

          <label for="password">Kata Sandi</label>
          <input type="password" id="password" name="password" placeholder="Masukkan Password" required minlength="8" />

          <label for="konf_pass">Konfirmasi Kata Sandi</label>
          <input type="password" id="konf_pass" name="konf_pass" placeholder="Konfirmasi Password" required />

          <button type="submit" name="registrasi">Daftar</button>
          <p>Sudah Punya Akun? <a href="login.php">Masuk</a></p>
        </form>
      </div>
    </div>
  </div>
</body>

</html>