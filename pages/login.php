<?php
include "session_check.php";
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Verifikasi CSRF Token
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token tidak valid!');
  }
  $user_input = isset($_POST["username"]) ? trim($_POST["username"]) : "";
  $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

  if (!empty($user_input) && !empty($password)) {
    $stmt = $conn->prepare("SELECT id_admin, username, email, password FROM admin WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $user_input, $user_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      if ($password == $row["password"]) {
        $_SESSION["id_admin"] = $row["id_admin"];
        $_SESSION["username"] = $row["username"];

        //  Tambah log aktivitas login
        $id_admin = $row["id_admin"];
        $aksi = "Login ke sistem";
        $waktu = date("Y-m-d H:i:s");
        $conn->query("INSERT INTO log_aktivitas (id_admin, aksi, waktu) VALUES ('$id_admin', '$aksi', '$waktu')");

        $_SESSION["login_success"] = "Login berhasil";
        header("Location: dashboard.php");
        exit;
      } else {
        sleep(1);
        echo "<script>
              document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                  icon: 'warning',
                  title: 'Peringatan',
                  text: 'Nama atau Kata Sandi salah',
                }).then(() => {
                  window.location.href = 'login.php';
                });
              });
              </script>";
      }
    } else {
      sleep(1);
      echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Nama atau Email tidak ditemukan!',
                    confirmButtonColor: '#2d336b'
                }).then(() => {
                  window.location.href = 'login.php';
                });
              });
            </script>";
    }
    $stmt->close();
  } else {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Harap isi semua kolom!',
          }).then(() => {
                  window.location.href = 'login.php';
                });
              });
        </script>";
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
  <title>Login - Apotek Naura Farma</title>
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
  <!-- Halaman login -->
  <div class="container login active">
    <div class="kiriisi">
      <div class="logo">
        <img src="../img/logo2.png" alt="Logo Apotek" />
        <div class="text-logo-login">
          <h3>DATA PENGARSIPAN</h3>
          <h1>APOTEK NAURA FARMA</h1>
        </div>
      </div>
      <div class="login-box-login">
        <h2>Masuk</h2>
        <hr />
        <form method="POST">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

          <label for="username">Nama Pengguna</label>
          <input type="text" id="username" name="username" placeholder="Masukkan Nama/email" required autofocus />

          <label for="password">Kata Sandi</label>
          <input type="password" id="password" name="password" placeholder="Masukkan Password" required minlength="8" />

          <button type="submit">Masuk</button>
          <p>Belum Punya Akun? <a href="register.php">Daftar</a></p>
        </form>
      </div>
    </div>
    <div class="kanan-img">
      <img src="../img/antibiotic.jpg" alt="Background" />
    </div>
  </div>
</body>

</html>