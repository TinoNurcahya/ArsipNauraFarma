<?php
include "session_check.php";
include "connect.php";

$id_arsip = $_GET['id'] ?? null;

if (!$id_arsip) {
  die("ID arsip tidak ditemukan.");
}

// Ambil data arsip
$arsip = $conn->query("SELECT * FROM arsip WHERE id_arsip = $id_arsip")->fetch_assoc();

// Ambil data detail_produk + produk
$stmt = $conn->prepare("SELECT detail_produk.*, produk.nama_produk 
    FROM detail_produk 
    JOIN produk ON detail_produk.id_produk = produk.id_produk 
    WHERE detail_produk.id_arsip = ?");
$stmt->bind_param("i", $id_arsip);
$stmt->execute();
$detail_result = $stmt->get_result();

$detail_data = [];
while ($row = $detail_result->fetch_assoc()) {
  $detail_data[] = $row;
}

function formatField($label, $oldVal, $newVal)
{
  if ($oldVal !== $newVal) {
    return "$label: $oldVal menjadi $newVal";
  }
  return "$label: $oldVal";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Verifikasi CSRF Token
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token tidak valid!');
  }
  // Ambil ID admin dari session
  $id_admin = isset($_SESSION['id_admin']) ? $_SESSION['id_admin'] : null;

  // Ambil dan bersihkan input
  $nama_perusahaan = htmlspecialchars(strip_tags(trim($_POST['nama_perusahaan'])));
  $tgl_datang = $_POST['tgl_datang'];
  $no_faktur = htmlspecialchars(strip_tags(trim($_POST['no_faktur'])));
  $jth_tempo = $_POST['jth_tempo'];
  $tgl_faktur = $_POST['tgl_faktur'];
  $total = $_POST['total'];

  // Hitung jumlah produk yang diubah
  $produk_diubah = 0;

  // Update detail produk satu per satu
  foreach ($_POST['id_detail'] as $i => $id_detail) {
    $jumlah = $_POST['jumlah'][$i];
    $satuan_select = trim($_POST['satuan'][$i]);
    if ($satuan_select === "LAINNYA") {
      $satuan = htmlspecialchars(strip_tags(trim($_POST['satuan_lainnya'][$i])));
    } else {
      $satuan = htmlspecialchars(strip_tags($satuan_select));
    }
    $kadaluarsa = $_POST['kadaluarsa'][$i];
    $batch = htmlspecialchars(strip_tags(trim($_POST['batch'][$i])));

    // Ambil data lama detail produk
    $detail_produk = $detail_data[$i];

    // Cek apakah ada perubahan pada detail produk
    $perubahan = false;

    if ($detail_produk['jumlah'] != $jumlah) $perubahan = true;
    if ($detail_produk['satuan'] != $satuan) $perubahan = true;
    if ($detail_produk['kadaluarsa'] != $kadaluarsa) $perubahan = true;
    if ($detail_produk['batch'] != $batch) $perubahan = true;

    if ($perubahan) $produk_diubah++;
    // Update detail produk hanya jika ada perubahan
    if ($perubahan) {
      $stmt = $conn->prepare("UPDATE detail_produk SET jumlah=?, satuan=?, kadaluarsa=?, batch=? 
      WHERE id_detail=?");
      $stmt->bind_param("isssi", $jumlah, $satuan, $kadaluarsa, $batch, $id_detail);
      $stmt->execute();
    }
  }
  // Update data arsip
  $stmt = $conn->prepare("UPDATE arsip SET nama_perusahaan=?, tgl_datang=?, 
  no_faktur=?, jth_tempo=?, tgl_faktur=?, total=? WHERE id_arsip=?");
  $stmt->bind_param("sssssdi", $nama_perusahaan, $tgl_datang, $no_faktur, 
  $jth_tempo, $tgl_faktur, $total, $id_arsip);
  $stmt->execute();
  $stmt->close();
  // Update detail nama produk
  foreach ($_POST['id_produk'] as $i => $id_produk) {
    $nama_produk_baru = strip_tags(trim($_POST['nama_produk'][$i]));
    // Ambil nama lama dari database
    $stmt = $conn->prepare("SELECT nama_produk FROM produk WHERE id_produk = ?");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $nama_produk_lama = $result['nama_produk'];
    $stmt->close();

    if ($nama_produk_baru !== $nama_produk_lama) {
      // Log perubahan nama produk
      $produk_diubah++;

      // Cek apakah produk ini dipakai di lebih dari 1 detail_produk
      $stmt = $conn->prepare("SELECT COUNT(*) as jumlah FROM detail_produk WHERE id_produk = ?");
      $stmt->bind_param("i", $id_produk);
      $stmt->execute();
      $count_result = $stmt->get_result()->fetch_assoc();
      $jumlah_pakai = $count_result['jumlah'];
      $stmt->close();

      if ($jumlah_pakai > 1) {
        // Duplikat produk
        $stmt = $conn->prepare("INSERT INTO produk (nama_produk) VALUES (?)");
        $stmt->bind_param("s", $nama_produk_baru);
        $stmt->execute();
        $id_produk_baru = $stmt->insert_id;
        $stmt->close();

        // Update detail_produk untuk pakai id_produk baru
        $id_detail = $_POST['id_detail'][$i];
        $stmt = $conn->prepare("UPDATE detail_produk SET id_produk=? WHERE id_detail=?");
        $stmt->bind_param("ii", $id_produk_baru, $id_detail);
        $stmt->execute();
        $stmt->close();
      } else {
        // Produk hanya dipakai 1x, boleh update langsung
        $stmt = $conn->prepare("UPDATE produk SET nama_produk=? WHERE id_produk=?");
        $stmt->bind_param("si", $nama_produk_baru, $id_produk);
        $stmt->execute();
        $stmt->close();
      }
    }
  }
  // Buat log aktivitas format khusus
  $arsip_logs = [];
  $arsip_logs[] = formatField("Edit arsip", $arsip['nama_perusahaan'], $nama_perusahaan);
  $arsip_logs[] = formatField("Faktur", $arsip['no_faktur'], $no_faktur);
  $arsip_logs[] = formatField("Tgl datang", $arsip['tgl_datang'], $tgl_datang);

  $log_aktivitas = implode(", ", $arsip_logs) . "; $produk_diubah produk diubah.";


  // Simpan log aktivitas jika ada perubahan
  if (
    $arsip['nama_perusahaan'] !== $nama_perusahaan
    || $arsip['no_faktur'] !== $no_faktur
    || $arsip['tgl_datang'] !== $tgl_datang
    || $produk_diubah > 0
  ) {
    $stmt_log = $conn->prepare("INSERT INTO log_aktivitas (id_admin, aksi) VALUES (?, ?)");
    $stmt_log->bind_param("is", $id_admin, $log_aktivitas);
    $stmt_log->execute();
    $stmt_log->close();
  }
  header("Location: daftarbarang.php");

  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../img/favicon.png" type="image/x-icon">
  <title>Edit Arsip</title>
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
   body{
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
    <div class="edit-arsip">

      <a href="daftarbarang.php" class="btn-kembali">
        <i class="fa-solid fa-arrow-left"></i> <span>Kembali</span>
      </a>
      <div class="judul-riwayat">
        <h1>Edit Arsip dan Produk</h1>
      </div>

      <div class="edit-arsip-form arsip-atas">
        <form method="post" id="editForm" autocomplete="off">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
          <div class="form-group autocomplete-container" style="position: relative;">
            <label>Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" value="<?= $arsip['nama_perusahaan']; ?>" placeholder="Masukkan Nama PBF" required autofocus>
          </div>

          <div class="form-group">
            <label>Nomor Faktur</label>
            <input type="text" name="no_faktur" value="<?= $arsip['no_faktur']; ?>" placeholder="Masukkan Nomor Faktur" required>
          </div>

          <div class="form-group">
            <label>Tanggal Datang</label>
            <input type="date" name="tgl_datang" value="<?= $arsip['tgl_datang']; ?>" max="2100-12-31" required>
          </div>

          <div class="form-group">
            <label>Jatuh Tempo</label>
            <input type="date" name="jth_tempo" value="<?= $arsip['jth_tempo']; ?>" max="2100-12-31" required>
          </div>

          <div class="form-group">
            <label>Tanggal Faktur</label>
            <input type="date" name="tgl_faktur" value="<?= $arsip['tgl_faktur']; ?>" max="2100-12-31" required>
          </div>

          <div class="form-group">
            <label>Total</label>
            <input type="number" name="total" value="<?= $arsip['total']; ?>" placeholder="Masukkan Total" required>
          </div>
      </div>

      <div class="edit-arsip-form">
        <table>
          <thead>
            <tr>
              <th>Nama Produk</th>
              <th>Kadaluarsa</th>
              <th>Batch</th>
              <th>Jumlah</th>
              <th>Satuan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($detail_data as $i => $d): ?>
              <tr>
                <td>
                  <input type="text" name="nama_produk[]" value="<?= $d['nama_produk']; ?>" placeholder="Masukkan Nama obat/alkes" required>
                  <input type="hidden" name="id_produk[]" value="<?= $d['id_produk']; ?>">
                </td>
                <td>
                  <input type="date" name="kadaluarsa[]" value="<?= $d['kadaluarsa']; ?>" max="2100-12-31" required>
                  <input type="hidden" name="id_detail[]" value="<?= $d['id_detail']; ?>">
                </td>
                <td><input type="text" name="batch[]" value="<?= $d['batch']; ?>" placeholder="Masukkan No. Batch" required></td>
                <td><input type="number" name="jumlah[]" value="<?= $d['jumlah']; ?>" placeholder="Masukkan Jumlah Barang" required min="1"></td>
                <td>
                  <div class="row-satuan">
                  <select name="satuan[]" onchange="cekSatuan(this)">
                    <option value="">-- Pilih Satuan --</option>
                    <option value="PCS" <?= $d['satuan'] == 'PCS' ? 'selected' : '' ?>>PCS</option>
                    <option value="KG" <?= $d['satuan'] == 'KG' ? 'selected' : '' ?>>KG</option>
                    <option value="BOX" <?= $d['satuan'] == 'BOX' ? 'selected' : '' ?>>BOX</option>
                    <option value="FLS" <?= $d['satuan'] == 'FLS' ? 'selected' : '' ?>>FLS</option>
                    <option value="LAINNYA" <?= !in_array($d['satuan'], ['PCS', 'KG', 'BOX', 'FLS']) ? 'selected' : '' ?>>LAINNYA</option>
                  </select>
                  <input type="text" name="satuan_lainnya[]" placeholder="Masukkan satuan lain"
                    value="<?= !in_array($d['satuan'], ['PCS', 'KG', 'BOX', 'FLS']) ? htmlspecialchars($d['satuan']) : '' ?>"
                    class="satuan-lainnya"
                    style="<?= !in_array($d['satuan'], ['PCS', 'KG', 'BOX', 'FLS']) ? 'display:block;' : 'display:none;' ?>">
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br>
        <button type="submit">Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>

    <script src="../script/script.js"></script>
    <script>
      document.getElementById("editForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Mencegah submit langsung

        let form = this;
        let inputs = form.querySelectorAll("input[required], select[required]");

        for (let input of inputs) {
          if (input.value.trim() === "") {
            Swal.fire({
              icon: "warning",
              title: "Oops...",
              text: "Semua kolom wajib diisi!",
            });
            return;
          }
        }

        Swal.fire({
          title: "Konfirmasi pengiriman",
          text: "Pastikan data yang kamu masukkan benar!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#00BB3E",
          cancelButtonColor: "#d33",
          background: "#2d336b",
          color: "#ffffff",
          confirmButtonText: "Update",
          cancelButtonText: "Batal",
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: "Berhasil!",
              text: "Data berhasil diupdate",
              icon: "success",
              confirmButtonColor: "#2d336b",
            }).then(() => {
              form.submit();
            });
          }
        });
      });
    </script>

</body>

</html>