<?php
include "connect.php";
include "session_check.php";


$username = $_SESSION['username'];
$id_admin = $_SESSION['id_admin'];

// Ambil data user (opsional, jika dibutuhkan di UI)
$stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$data_user = $result->fetch_assoc();
$stmt->close();


// Pastikan form dikirim via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Verifikasi CSRF Token
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token tidak valid!');
  }

  // Ambil data arsip
  $nama_perusahaan = htmlspecialchars(strip_tags(trim($_POST['nama_perusahaan'])));
  $tgl_datang = $_POST['tanggal_datang'];
  $no_faktur = htmlspecialchars(strip_tags(trim($_POST['no_faktur'])));
  $jth_tempo = $_POST['jatuh_tempo'];
  $tgl_faktur = $_POST['tanggal_faktur'];
  $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;

  try {
    // Mulai transaksi
    $conn->begin_transaction();

    // Simpan ke tabel arsip
    $sql_arsip = "INSERT INTO arsip (nama_perusahaan, tgl_datang, no_faktur, jth_tempo, tgl_faktur, total, id_admin) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_arsip = $conn->prepare($sql_arsip);
    $stmt_arsip->bind_param("sssssdi", $nama_perusahaan, $tgl_datang, $no_faktur, $jth_tempo, $tgl_faktur, $total, $id_admin);
    $stmt_arsip->execute();
    $id_arsip = $stmt_arsip->insert_id;
    $stmt_arsip->close();

    // Simpan ke log aktivitas
    $jumlah_produk = count($_POST['nama_produk']);
    $aksi_arsip = "Tambah arsip: $nama_perusahaan, Faktur: $no_faktur, Tgl datang: $tgl_datang, $jumlah_produk produk";
    $stmt_log = $conn->prepare("INSERT INTO log_aktivitas (id_admin, aksi) VALUES (?, ?)");
    $stmt_log->bind_param("is", $id_admin, $aksi_arsip);
    $stmt_log->execute();
    $stmt_log->close();

    // Simpan produk & detail_produk
    if (isset($_POST['nama_produk']) && is_array($_POST['nama_produk'])) {
      foreach ($_POST['nama_produk'] as $index => $nama_produk) {
        $nama_produk = htmlspecialchars(strip_tags(trim($nama_produk)));
        $kadaluarsa = $_POST['kadaluarsa'][$index] ?? null;
        $batch = htmlspecialchars(strip_tags(trim($_POST['batch'][$index] ?? "")));
        $jumlah = is_numeric($_POST['jumlah'][$index]) ? (int)$_POST['jumlah'][$index] : 0;

        $satuan = $_POST['satuan'][$index] ?? '';
        if ($satuan === 'LAINNYA') {
          $satuan = $_POST['satuan_lainnya'][$index] ?? '';
        }
        $satuan = htmlspecialchars(strip_tags(trim($satuan)));

        // Cek apakah produk sudah ada
        $stmt_cek_produk = $conn->prepare("SELECT id_produk FROM produk WHERE nama_produk = ?");
        $stmt_cek_produk->bind_param("s", $nama_produk);
        $stmt_cek_produk->execute();
        $result = $stmt_cek_produk->get_result();
        $row = $result->fetch_assoc();
        $stmt_cek_produk->close();

        if ($row) {
          $id_produk = $row['id_produk'];
        } else {
          $stmt_insert_produk = $conn->prepare("INSERT INTO produk (nama_produk) VALUES (?)");
          $stmt_insert_produk->bind_param("s", $nama_produk);
          $stmt_insert_produk->execute();
          $id_produk = $stmt_insert_produk->insert_id;
          $stmt_insert_produk->close();
        }

        // Simpan detail_produk
        $stmt_detail = $conn->prepare("INSERT INTO detail_produk (id_arsip, id_produk, kadaluarsa, batch, jumlah, satuan) 
                                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_detail->bind_param("iissis", $id_arsip, $id_produk, $kadaluarsa, $batch, $jumlah, $satuan);
        $stmt_detail->execute();
        $stmt_detail->close();
      }
    }

    // Commit jika semua berhasil
    $conn->commit();

    // Hapus token CSRF
    unset($_SESSION['csrf_token']);

    // Redirect ke halaman utama
    header("Location: tambahbarang.php");
    exit();
  } catch (Exception $e) {
    //  Rollback jika ada error
    $conn->rollback();
    error_log("Gagal menambahkan data arsip: " . $e->getMessage());
    die("Terjadi kesalahan saat menyimpan data. Silakan coba lagi.");
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../img/favicon.png" type="image/x-icon">
  <title>Tambah Arsip</title>
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
    .autocomplete-items {
      position: absolute;
      border: 1px solid #ccc;
      z-index: 99;
      top: 100%;
      left: 0;
      right: 0;
      background-color: #fff;
      max-height: 200px;
      overflow-y: auto;

      border-top: none;
    }

    .autocomplete-items div {
      padding: 10px;
      cursor: pointer;
    }

    .autocomplete-items div:hover {
      background-color: #e9e9e9;
    }

    .autocomplete-active {
      background-color: #d3d3d3;
    }

    .input-box {
      position: relative;
    }
  </style>
</head>

<body>
  <header>
    <h1>Tambah Data Arsip</h1>
    <hr>
  </header>

  <nav class="sidebar" id="sidebar">
    <div class="logo">
      <img src="../img/logo2.png" alt="Logo Apotek Naura Farma" />
      <h3>Apotek Naura Farma</h3>
    </div>
    <hr>

    <div class="profile">
      <a href="javascript:void(0)" onclick="openInfoModal()">
        <img src="../img/<?php echo !empty($data_user['foto']) ? htmlspecialchars($data_user['foto']) : 'default.jpg'; ?>" alt="Foto profil pengguna" loading="lazy">
      </a>
      <span>Hai, <?php echo explode(" ", $_SESSION["username"])[0]; ?> </span>
    </div>

    <div class="menu">
      <ul>
        <li>
          <a href="dashboard.php"><i class="fa-solid fa-gauge-high"></i> <span>Dashboard</span></a>
        </li>
        <li>
          <a href="#" id="tamb_brg"><i class="fa-solid fa-square-plus"></i> <span>Tambah Arsip</span></a>
        </li>
        <li>
          <a href="daftarbarang.php"><i class="fa-solid fa-chart-simple"></i> <span>Daftar Arsip</span></a>
        </li>
      </ul>
    </div>
    <a href="#" id="logoutButton"><i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Keluar</span></a>
  </nav>

  <button class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars"></i>
    <i class="fa-solid fa-bars-staggered hidden"></i>
  </button>

  <main>
    <div class="add_brg">
      <div class="header_add_brg">
        <span>Masukkan Arsip Baru</span>
      </div>
      <form name="barangForm" id="barangForm" method="POST" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="form-group">
          <label for="nama_perusahaan">Nama Perusahaan</label>
          <div class="input-box autocomplete-container">
            <span class="icon"><i class="fa-solid fa-house-medical"></i></span>
            <input type="text" id="nama_perusahaan" name="nama_perusahaan" placeholder="Masukkan Nama PBF" required autofocus>
          </div>
        </div>

        <div class="form-group">
          <label for="tanggal_datang">Tanggal datang</label>
          <div class="input-box">
            <span class="icon"><i class="fa-solid fa-calendar-days"></i></span>
            <input type="date" name="tanggal_datang" required max="2100-12-31" title="Pilih tanggal datang">
          </div>
        </div>

        <div class="form-group">
          <label for="nama_produk[]">Nama Produk</label>
          <div id="produk-container">
            <div class="input-box autocomplete-container">
              <span class="icon"><i class="fa-solid fa-box-open"></i></span>
              <input type="text" class="nama_produk_input" name="nama_produk[]" placeholder="Masukkan Nama obat/alkes" required>
              <button type="button" class="add-btn" onclick="tambahSatuSetBarang()">+</button>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="kadaluarsa[]">Kadaluarsa</label>
          <div id="kadaluarsa-container">
            <div class="input-box">
              <span class="icon"><i class="fa-solid fa-hourglass-half"></i></span>
              <input type="date" name="kadaluarsa[]" required max="2100-12-31" title="Masukkan kadaluarsa produk">
              <button type="button" class="add-btn" onclick="tambahSatuSetBarang()">+</button>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="batch[]">Nomor Batch</label>
          <div id="batch-container">
            <div class="input-box">
              <span class="icon"><i class="fa-solid fa-arrow-down-1-9"></i></span>
              <input type="text" name="batch[]" placeholder="Masukkan No. Batch" required>
              <button type="button" class="add-btn" onclick="tambahSatuSetBarang()">+</button>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="jumlah[]">Jumlah</label>
          <div id="jumlah-container">
            <div class="input-box">
              <span class="icon"><i class="fa-solid fa-arrow-down-1-9"></i></span>
              <input type="number" name="jumlah[]" placeholder="Masukkan Jumlah Barang" required min="1">
              <button type="button" class="add-btn" onclick="tambahSatuSetBarang()">+</button>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="satuan[]">Satuan</label>
          <div id="satuan-container">
            <div class="input-box autocomplete-container">
              <span class="icon"><i class="fa-solid fa-list"></i></span>
              <select id="satuan" name="satuan[]" onchange="cekSatuan(this)" required>
                <option value="">-- Pilih Satuan --</option>
                <option value="PCS">PCS</option>
                <option value="KG">KG</option>
                <option value="BOX">BOX</option>
                <option value="FLS">FLS</option>
                <option value="LAINNYA">LAINNYA</option>
              </select>
              <input type="text" name="satuan_lainnya[]" class="satuan-lainnya" placeholder="Masukkan satuan lain" style="display: none;">
              <button type="button" class="add-btn" onclick="tambahSatuSetBarang()">+</button>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="no_faktur">Nomor Faktur</label>
          <div id="nofaktur-container">
            <div class="input-box">
              <span class="icon"><i class="fa-solid fa-arrow-down-1-9"></i></span>
              <input type="text" name="no_faktur" placeholder="Masukkan Nomor Faktur" required>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="jatuh_tempo">Jatuh tempo</label>
          <div class="input-box">
            <span class="icon"><i class="fa-solid fa-hourglass-end"></i></span>
            <input type="date" name="jatuh_tempo" required max="2100-12-31">
          </div>
        </div>

        <div class="form-group">
          <label for="tanggal_faktur">Tanggal Faktur</label>
          <div class="input-box">
            <span class="icon"><i class="fa-solid fa-calendar-days"></i></span>
            <input type="date" name="tanggal_faktur" required max="2100-12-31">
          </div>
        </div>

        <div class="form-group">
          <label for="total">Total</label>
          <div class="input-box">
            <span class="icon"><i class="fa-solid fa-rupiah-sign"></i></span>
            <input type="text" id="inputRupiah" name="total" placeholder="Masukkan Total" required>
          </div>
        </div>
        <button id="btn-brg" type="submit" name="submitbtn">Kirim</button>
      </form>
    </div>
    <!-- Modal Informasi Admin -->
    <div class="modal-overlay" id="infoModal">
      <div class="modal-content">
        <button class="close-btn" onclick="closeInfoModal()"><i class="fa-solid fa-xmark"></i></button>

        <!-- Bagian Informasi -->
        <div id="infoSection">
          <h3>Profil Admin</h3>
          <p>
            <img src="../img/<?= !empty($data_user['foto']) ? ($data_user['foto']) : 'default.jpg' ?>" alt="Foto Profil" style="height:100px;" loading="lazy">
          </p>
          <p><strong>Username:</strong> <?= ($data_user['username']) ?></p>
          <p><strong>No HP:</strong> <?= ($data_user['no_hp'] ?? '-') ?></p>
          <p><strong>Alamat:</strong> <?= ($data_user['alamat'] ?? '-') ?></p>
          <p><strong>Dibuat pada:</strong> <?= ($data_user['created_at']) ?></p>
          <button onclick="showEditForm()">Edit</button>
        </div>

        <!-- Form Edit -->
        <div id="editFormSection">
          <h3>Edit Profil</h3>
          <img src="../img/<?= !empty($data_user['foto']) ? ($data_user['foto']) : 'default.jpg' ?>" style="height:100px;" loading="lazy">
          <form action="update_admin.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <label>Ganti Foto:</label>
            <input type="file" name="foto">

            <input type="hidden" name="id_admin" value="<?= $data_user['id_admin'] ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <label>Username:</label>
            <input type="text" name="username" value="<?= ($data_user['username']) ?>" required>

            <label>Password (biarkan kosong jika tidak diganti):</label>
            <input type="password" name="password" minlength="8">

            <label>No HP:</label>
            <input type="number" name="no_hp" value="<?= ($data_user['no_hp'] ?? '') ?>">

            <label>Alamat:</label>
            <textarea name="alamat"><?= ($data_user['alamat'] ?? '') ?></textarea>

            <button type="submit">Simpan</button>
            <button type="button" onclick="cancelEdit()">Batal</button>
          </form>
        </div>
      </div>
    </div>
  </main>
  <script src="../script/script.js" defer></script>
  <!-- Fungsi autocomplete -->
  <script>
    function autocomplete(inp, arr) {
      var currentFocus;

      inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        closeAllLists();
        if (!val) return false;

        currentFocus = -1;
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");

        this.parentNode.appendChild(a);

        let foundMatch = false;

        for (i = 0; i < arr.length; i++) {
          if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            foundMatch = true;
            b = document.createElement("DIV");
            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].substr(val.length);
            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
            b.addEventListener("click", function(e) {
              inp.value = this.getElementsByTagName("input")[0].value;
              closeAllLists();
            });
            a.appendChild(b);
          }
        }

        // jika tidak ada match, hilangkan container autocomplete
        if (!foundMatch) {
          closeAllLists();
        }
      });

      inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          currentFocus++;
          addActive(x);
        } else if (e.keyCode == 38) {
          currentFocus--;
          addActive(x);
        } else if (e.keyCode == 13) {
          if (currentFocus > -1 && x) {
            e.preventDefault();
            x[currentFocus].click();
          }
        }
      });

      function addActive(x) {
        if (!x) return false;
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = x.length - 1;
        x[currentFocus].classList.add("autocomplete-active");
      }

      function removeActive(x) {
        for (var i = 0; i < x.length; i++) {
          x[i].classList.remove("autocomplete-active");
        }
      }

      function closeAllLists(elmnt) {
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
          if (elmnt != x[i] && elmnt != inp) {
            if (x[i] && x[i].parentNode) {
              x[i].parentNode.removeChild(x[i]);
            }
          }
        }
      }

      document.addEventListener("click", function(e) {
        closeAllLists(e.target);
      });
    }

    var perusahaanList = <?php
                          $res = mysqli_query($conn, "SELECT DISTINCT nama_perusahaan FROM arsip WHERE nama_perusahaan IS NOT NULL");
                          $list = [];
                          while ($row = mysqli_fetch_assoc($res)) {
                            $list[] = $row['nama_perusahaan'];
                          }
                          echo json_encode($list);
                          ?>;
    autocomplete(document.getElementById("nama_perusahaan"), perusahaanList);


    // Ambil daftar produk dari PHP
    var produkList = <?php
                      $res = mysqli_query($conn, "SELECT DISTINCT nama_produk FROM produk WHERE nama_produk IS NOT NULL");
                      $list = [];
                      while ($row = mysqli_fetch_assoc($res)) {
                        $list[] = $row['nama_produk'];
                      }
                      echo json_encode($list);
                      ?>;

    // Pasang autocomplete untuk semua input produk yang sudah ada
    document.querySelectorAll('input.nama_produk_input').forEach(function(inp) {
      autocomplete(inp, produkList);
    });



    // Ambil daftar satuan dari PHP
    var satuanList = <?php
                      $res = mysqli_query($conn, "SELECT DISTINCT satuan FROM detail_produk WHERE satuan IS NOT NULL");
                      $list = [];
                      while ($row = mysqli_fetch_assoc($res)) {
                        $list[] = $row['satuan'];
                      }
                      echo json_encode($list);
                      ?>;

    // Pasang autocomplete untuk semua input satuan yang sudah ada
    document.querySelectorAll('input.satuan-lainnya').forEach(function(inp) {
      autocomplete(inp, satuanList);
    });
  </script>
</body>

</html>