<?php
include "session_check.php";
include 'connect.php';

$undo_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['undo'])) {
  if (!empty($_SESSION['undo_stack'])) {
    $last_deleted = array_pop($_SESSION['undo_stack']);
    $arsip = $last_deleted['arsip'];
    $details = $last_deleted['detail'];

    // Restore arsip
    $stmt_restore_arsip = $conn->prepare("INSERT INTO arsip (id_arsip, nama_perusahaan, 
    tgl_datang, jth_tempo, tgl_faktur, total, no_faktur) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt_restore_arsip->bind_param(
      "issssds",
      $arsip['id_arsip'],
      $arsip['nama_perusahaan'],
      $arsip['tgl_datang'],
      $arsip['jth_tempo'],
      $arsip['tgl_faktur'],
      $arsip['total'],
      $arsip['no_faktur']
    );
    $stmt_restore_arsip->execute();

    // Restore detail_produk
    foreach ($details as $d) {
      $stmt_restore_detail = $conn->prepare("INSERT INTO detail_produk (id_detail, 
      id_arsip, id_produk, kadaluarsa, batch, jumlah, satuan) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt_restore_detail->bind_param(
        "iiissis",
        $d['id_detail'],
        $d['id_arsip'],
        $d['id_produk'],
        $d['kadaluarsa'],
        $d['batch'],
        $d['jumlah'],
        $d['satuan']
      );
      $stmt_restore_detail->execute();
    }

    // Log aktivitas undo
    $id_admin = $_SESSION['id_admin'] ?? null;
    $nama_perusahaan = $arsip['nama_perusahaan'];
    $no_faktur = $arsip['no_faktur'];
    $tgl_datang = $arsip['tgl_datang'];
    $jumlah_produk = count($details);

    $log_aksi = "Undo hapus arsip: $nama_perusahaan, Faktur: $no_faktur, Tgl datang: $tgl_datang, $jumlah_produk produk";
    $stmt_log = $conn->prepare("INSERT INTO log_aktivitas (id_admin, aksi) VALUES (?, ?)");
    $stmt_log->bind_param("is", $id_admin, $log_aksi);
    $stmt_log->execute();

    // Cek AJAX
    if (
      !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    ) {
      echo json_encode(['success' => true]);
      exit;
    } else {
      // Redirect kalau bukan AJAX
      header("Location: logaktifitas.php");
      exit;
    }
  } else {
    // Kalau ngga ada datanya
    if (
      !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    ) {
      echo json_encode(['success' => false, 'message' => 'Tidak ada data untuk undo']);
      exit;
    } else {
      header("Location: logaktifitas.php");
      exit;
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../img/favicon.png" type="image/x-icon">
  <title>Riwayat Aktivitas</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="../css/style.css">


  <!-- Preconnect & DNS Prefetch -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="dns-prefetch" href="https://fonts.googleapis.com">
  <link rel="dns-prefetch" href="https://fonts.gstatic.com">

  <link rel="preconnect" href="https://kit.fontawesome.com" crossorigin>
  <link rel="dns-prefetch" href="https://kit.fontawesome.com">
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

  <!-- Google Fonts: Lazy-load (non render-blocking) -->
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" onload="this.onload=null;this.rel='stylesheet'">
  <noscript>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap">
  </noscript>

  <!-- icon -->
  <script src="https://kit.fontawesome.com/c8f4e6dde8.js" crossorigin="anonymous" defer></script>
  <!-- sweet alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      position: relative;
      background: none;
      z-index: 0;
      background-image: url(../img/antibiotic.jpg);
      background-repeat: no-repeat;
      background-size: cover;
      backdrop-filter: blur(25px);
    }
  </style>
</head>

<body>
  <div class="blur-overlay"></div>
  <div class="container-center">
    <div class="riwayat">

      <a href="dashboard.php" class="btn-kembali">
        <i class="fa-solid fa-arrow-left"></i> <span>Kembali</span>
      </a>
      <div class="judul-riwayat">
        <h1>Riwayat Aktivitas</h1>
      </div>
      
      <div class="undo-riwayat">
        <?php if (!empty($_SESSION['undo_stack'])):
          $last_deleted = end($_SESSION['undo_stack']);
          $arsip = $last_deleted['arsip'];
          $details = $last_deleted['detail'];
        ?>
          <table class="undo-info-table">
            <thead>
              <tr>
                <th colspan="2" style="padding: 8px;">Arsip Terakhir Dihapus</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Nama Perusahaan</td>
                <td><?= htmlspecialchars($arsip['nama_perusahaan']) ?></td>
              </tr>
              <tr>
                <td>Faktur</td>
                <td><?= htmlspecialchars($arsip['no_faktur']) ?></td>
              </tr>
              <tr>
                <td>Tgl Datang</td>
                <td><?= htmlspecialchars($arsip['tgl_datang']) ?></td>
              </tr>
              <tr>
                <td>Jumlah Produk</td>
                <td><?= count($details) ?></td>
              </tr>
            </tbody>
          </table>

          <form method="post" id="undoForm">
            <button type="submit" name="undo">Undo</button>
          </form>
        <?php endif; ?>
      </div>
      <div class="log">
        <table id="logTable" class="display nowrap" style="width:100%">
          <thead>
            <tr>
              <th>No</th>
              <th>Username</th>
              <th>Aksi</th>
              <th>Waktu</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = "
    SELECT log_aktivitas.*, admin.username 
    FROM log_aktivitas 
    JOIN admin ON log_aktivitas.id_admin = admin.id_admin 
    ORDER BY log_aktivitas.waktu DESC
  ";
            $result = mysqli_query($conn, $query);
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)):
            ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['aksi']) ?></td>
                <td><?= $row['waktu'] ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#logTable').DataTable({
        dom: 'lfrtip', // length menu + search + table + info + pagination
        responsive: true
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      const undoForm = document.getElementById('undoForm');
      if (undoForm) {
        undoForm.addEventListener('submit', function(e) {
          e.preventDefault();

          Swal.fire({
            title: 'Yakin ingin undo?',
            text: "Data yang dihapus akan dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#00BB3E",
            cancelButtonColor: "#d33",
            background: "#2d336b",
            color: "#ffffff",
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url: 'logaktifitas.php',
                method: 'POST',
                data: {
                  undo: true
                },
                dataType: 'json',
                success: function(response) {
                  if (response.success) {
                    Swal.fire({
                      icon: 'success',
                      title: 'Undo berhasil',
                      text: "Data berhasil dikembalikan.",
                      background: "#2d336b",
                      color: "#ffffff",
                      showConfirmButton: false,
                      timer: 1500
                    }).then(() => {
                      location.reload();
                    });
                  } else {
                    Swal.fire('Gagal', response.message || 'Undo gagal', 'error');
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  console.log('AJAX ERROR:', textStatus, errorThrown, jqXHR.responseText);
                  Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                }
              });
            }
          });
        });
      }
    });
  </script>
</body>

</html>