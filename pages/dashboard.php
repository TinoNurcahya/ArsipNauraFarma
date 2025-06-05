<?php
include "session_check.php";
include "connect.php";

// Cek apakah ada pesan sukses login
$alertMessage = "";
if (isset($_SESSION["login_success"])) {
  $alertMessage = $_SESSION["login_success"];
  unset($_SESSION["login_success"]);
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$data_user = $result->fetch_assoc();
$stmt->close();

// Ambil data transaksi bulanan dari tabel arsip (tgl_datang)
$tahun_filter = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$stmt_transaksi = $conn->prepare("
  SELECT MONTH(tgl_datang) AS bulan, SUM(total) AS total_arsip 
  FROM arsip 
  WHERE YEAR(tgl_datang) = ?
  GROUP BY MONTH(tgl_datang)
  ORDER BY MONTH(tgl_datang) ASC
");
$stmt_transaksi->bind_param("i", $tahun_filter);
$stmt_transaksi->execute();
$resultarsip = $stmt_transaksi->get_result();
$dataarsip = [];
while ($row = $resultarsip->fetch_assoc()) {
  $dataarsip[] = $row;
}
$stmt_transaksi->close();

// Ambil data nama_perusahaan terbanyak dari tabel arsip berdasarkan tahun
$stmt_nama_pbf = $conn->prepare("
  SELECT nama_perusahaan, COUNT(*) AS jumlah 
  FROM arsip 
  WHERE YEAR(tgl_datang) = ?
  GROUP BY nama_perusahaan 
  ORDER BY jumlah DESC 
  LIMIT 5
");
$stmt_nama_pbf->bind_param("i", $tahun_filter);
$stmt_nama_pbf->execute();
$resultnama_perusahaan = $stmt_nama_pbf->get_result();
$datanama_perusahaan = [];
while ($row = $resultnama_perusahaan->fetch_assoc()) {
  $datanama_perusahaan[] = $row;
}
$stmt_nama_pbf->close();

// Ambil data produk yang paling sering muncul dalam transaksi berdasarkan tahun
$stmt_produk = $conn->prepare("
  SELECT p.nama_produk, COUNT(*) AS jumlah_transaksi 
  FROM detail_produk dp
  JOIN produk p ON dp.id_produk = p.id_produk 
  JOIN arsip a ON dp.id_arsip = a.id_arsip
  WHERE YEAR(a.tgl_datang) = ?
  GROUP BY p.nama_produk 
  ORDER BY jumlah_transaksi DESC
  LIMIT 5
");
$stmt_produk->bind_param("i", $tahun_filter);
$stmt_produk->execute();
$resultnama_produk = $stmt_produk->get_result();
$datanama_produk = [];
while ($row = $resultnama_produk->fetch_assoc()) {
  $datanama_produk[] = $row;
}
$stmt_produk->close();

// Konversi data ke format JSON
$dataJSON = json_encode([
  "arsip" => $dataarsip,
  "nama_perusahaan" => $datanama_perusahaan,
  "nama_produk" => $datanama_produk,
  "tahun" => $tahun_filter
]);

// Ambil tahun & bulan saat ini
$tahun_ini = date('Y');
$bulan_ini = date('m');

// Total arsip bulan ini
$stmt_arsip_bln_ini = $conn->prepare("
  SELECT COUNT(*) AS total_arsip_bulan_ini 
  FROM arsip 
  WHERE YEAR(tgl_datang) = ? AND MONTH(tgl_datang) = ?
");
$stmt_arsip_bln_ini->bind_param("ii", $tahun_ini, $bulan_ini);
$stmt_arsip_bln_ini->execute();
$result = $stmt_arsip_bln_ini->get_result();
$row_bulan_ini = $result->fetch_assoc();
$total_arsip_bulan_ini = $row_bulan_ini['total_arsip_bulan_ini'];
$stmt_arsip_bln_ini->close();

// Total perusahaan bulan ini
$stmt_pbf_bln_ini = $conn->prepare("
  SELECT COUNT(DISTINCT nama_perusahaan) AS total_perusahaan_bulan_ini 
  FROM arsip 
  WHERE YEAR(tgl_datang) = ? AND MONTH(tgl_datang) = ?
");
$stmt_pbf_bln_ini->bind_param("ii", $tahun_ini, $bulan_ini);
$stmt_pbf_bln_ini->execute();
$result = $stmt_pbf_bln_ini->get_result();
$row_perusahaan = $result->fetch_assoc();
$total_perusahaan_bulan_ini = $row_perusahaan['total_perusahaan_bulan_ini'];
$stmt_pbf_bln_ini->close();

// Total produk bulan ini
$stmt_produk_bln_ini = $conn->prepare("
  SELECT COUNT(DISTINCT dp.id_produk) AS total_produk_bulan_ini
  FROM arsip a
  JOIN detail_produk dp ON a.id_arsip = dp.id_arsip
  WHERE YEAR(a.tgl_datang) = ? AND MONTH(a.tgl_datang) = ?
");
$stmt_produk_bln_ini->bind_param("ii", $tahun_ini, $bulan_ini);
$stmt_produk_bln_ini->execute();
$result = $stmt_produk_bln_ini->get_result();
$row_produk = $result->fetch_assoc();
$total_produk_bulan_ini = $row_produk['total_produk_bulan_ini'];
$stmt_produk_bln_ini->close();


// Total transaksi bulan ini
$stmt_transaksi_bln_ini = $conn->prepare("
  SELECT SUM(total) AS total_transaksi_bulan_ini 
  FROM arsip 
  WHERE YEAR(tgl_datang) = ? AND MONTH(tgl_datang) = ?
");
$stmt_transaksi_bln_ini->bind_param("ii", $tahun_ini, $bulan_ini);
$stmt_transaksi_bln_ini->execute();
$result = $stmt_transaksi_bln_ini->get_result();
$row_transaksi = $result->fetch_assoc();
$total_transaksi_bulan_ini = $row_transaksi['total_transaksi_bulan_ini'] ?? 0;
$stmt_transaksi_bln_ini->close();

$riwayat = [];
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
$stmt = $conn->prepare("
  SELECT la.*, a.username 
  FROM log_aktivitas la
  JOIN admin a ON la.id_admin = a.id_admin
  ORDER BY la.waktu DESC
  LIMIT ?
");
$stmt->bind_param("i", $limit);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $riwayat[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../img/favicon.png" type="image/x-icon">
  <title>Dashboard</title>
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
</head>

<body>
  <?php if (!empty($alertMessage)) : ?>
    <script>
      Swal.fire({
        position: 'center',
        icon: 'success',
        title: '<?= $alertMessage ?>',
        showConfirmButton: false,
        timer: 2000
      });
    </script>
  <?php endif; ?>


  <header>
    <h1>Dashboard</h1>
    <hr>
  </header>

  <nav class="sidebar" id="sidebar">
    <div class="logo">
      <img src="../img/logo2.png" alt="Logo Apotek Naura Farma" loading="lazy" />
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
          <a href="#" id="dashboard"><i class="fa-solid fa-gauge-high"></i> <span>Dashboard</span></a>
        </li>
        <li>
          <a href="tambahbarang.php"><i class="fa-solid fa-square-plus"></i> <span>Tambah Arsip</span></a>
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
    <form method="GET" id="filter-thn">
      <label for="tahun">Pilih Tahun:</label>
      <select name="tahun" id="tahun" onchange="this.form.submit()">
        <?php
        $tahun_sekarang = date('Y');
        for ($i = $tahun_sekarang; $i >= $tahun_sekarang - 4; $i--) {
          $selected = (isset($_GET['tahun']) && $_GET['tahun'] == $i) ? "selected" : "";
          echo "<option value='$i' $selected>$i</option>";
        }
        ?>
      </select>
    </form>


    <div class="summary">
      <div class="summary-box arsip">
        <div class="summary-text">
          <span>Arsip Masuk (bln)</span><br>
          <p><?= $total_arsip_bulan_ini ?></p>
        </div>
        <div class="sum-icon">
          <i class="fa-solid fa-box-archive"></i>
        </div>
      </div>
      <div class="summary-box perusahaan">
        <div class="summary-text">
          <span>Perusahaan Masuk (bln)</span><br>
          <p><?= $total_perusahaan_bulan_ini ?></p>
        </div>
        <div class="sum-icon">
          <i class="fa-solid fa-house-chimney-medical"></i>
        </div>
      </div>
      <div class="summary-box produk">
        <div class="summary-text">
          <span>Produk Masuk (bln)</span><br>
          <p><?= $total_produk_bulan_ini ?></p>
        </div>
        <div class="sum-icon">
          <i class="fa-solid fa-boxes-stacked"></i>
        </div>
      </div>
      <div class="summary-box total">
        <div class="summary-text">
          <span>Transaksi Masuk (bln)</span><br>
          <p>RP <?= number_format($total_transaksi_bulan_ini) ?></p>
        </div>
        <div class="sum-icon">
          <i class="fa-solid fa-rupiah-sign"></i>
        </div>
      </div>
    </div>

    <div class="chart-row">
      <div class="graf-box transaksi-box">
        <h3>Transaksi Bulanan</h3>
        <canvas id="arsipChart"></canvas>
      </div>
      <div class="graf-box produk-box">
        <h3>Produk Populer</h3>
        <canvas id="nama_produkChart"></canvas>
      </div>
    </div>

    <div class="chart-row single">
      <div class="graf-box perusahaan-box">
        <h3>Perusahaan Terbanyak</h3>
        <canvas id="nama_perusahaanChart"></canvas>
      </div>
      <div class="graf-box riwayat-box">
        <h3>Riwayat Aksi</h3>
        <ul>
          <?php foreach ($riwayat as $row): ?>
            <li title="<?= htmlspecialchars($row['aksi']) ?>">
              <strong><?= htmlspecialchars($row['username']) ?></strong> –
              <?= strlen($row['aksi']) > 25 ? htmlspecialchars(substr($row['aksi'], 0, 25)) . '…' : htmlspecialchars($row['aksi']) ?>
              <span>(<?= $row['waktu'] ?>)</span>
            </li>
          <?php endforeach; ?>
        </ul>
        <div class="btn-riwayat">
          <a href="logaktifitas.php">Selengkapnya</a>
        </div>
      </div>
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

  <!-- chart js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const dataFromPHP = <?= $dataJSON; ?>;

    // Grafik arsip Bulanan
    const arsipData = dataFromPHP.arsip.map(item => item.total_arsip);
    const arsipLabels = dataFromPHP.arsip.map(item => {
      const bulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      return bulan[item.bulan - 1];
    });

    new Chart(document.getElementById('arsipChart'), {
      type: 'line',
      data: {
        labels: arsipLabels,
        datasets: [{
          data: arsipData,
          borderColor: 'rgba(78, 115, 223, 1)',
          backgroundColor: 'rgba(153, 102, 255, 0.5)',
          pointBackgroundColor: 'rgba(78, 115, 223, 1)',
          pointRadius: 4,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        interaction: {
          mode: 'index',
          intersect: false
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: function(context) {
                let value = context.parsed.y.toLocaleString("id-ID", {
                  style: "currency",
                  currency: "IDR"
                });
                return 'Total: ' + value;
              }
            }
          },
          legend: {
            display: false
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            }
          },
          y: {
            ticks: {
              callback: function(value) {
                return 'Rp' + value.toLocaleString('id-ID');
              }
            }
          }
        }
      }
    });

    // Grafik nama_perusahaan Terbanyak
    dataFromPHP.nama_perusahaan.sort((a, b) => a.jumlah - b.jumlah);
    const nama_perusahaanData = dataFromPHP.nama_perusahaan.map(item => item.jumlah);
    const nama_perusahaanLabels = dataFromPHP.nama_perusahaan.map(item => item.nama_perusahaan.length > 10 ?
      item.nama_perusahaan.substring(0, 10) + '...' :
      item.nama_perusahaan
    );

    new Chart(document.getElementById('nama_perusahaanChart'), {
      type: 'bar',
      data: {
        labels: nama_perusahaanLabels,
        datasets: [{
          label: 'Jumlah Arsip',
          data: nama_perusahaanData,
          backgroundColor: [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        interaction: {
          mode: 'index',
          intersect: false
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              title: (tooltipItems) => {
                return dataFromPHP.nama_perusahaan[tooltipItems[0].dataIndex].nama_perusahaan;
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            ticks: {
              maxRotation: 90,
              minRotation: 45
            }
          }
        }
      }
    });

    // Grafik nama_produk Populer
    const nama_produkData = dataFromPHP.nama_produk.map(item => item.jumlah_transaksi);
    const nama_produkLabels = dataFromPHP.nama_produk.map(item =>
      item.nama_produk.length > 10 ?
      item.nama_produk.substring(0, 10) + '...' :
      item.nama_produk
    );

    new Chart(document.getElementById('nama_produkChart'), {
      type: 'doughnut',
      data: {
        labels: nama_produkLabels,
        datasets: [{
          label: 'Jumlah Produk',
          data: dataFromPHP.nama_produk.map(item => item.jumlah_transaksi),
          backgroundColor: ['rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
          legend: {
            display: true,
            position: 'bottom',
            labels: {
              usePointStyle: true,
              pointStyle: 'circle',
              padding: 20
            }
          },
          tooltip: {
            callbacks: {
              title: function(tooltipItems) {
                return dataFromPHP.nama_produk[tooltipItems[0].dataIndex].nama_produk;
              }
            }
          }
        }
      }
    });


    // Fungsi untuk mengubah ukuran chart
    function resizeChart(chartId, width) {
      var chartContainer = document.getElementById(chartId).parentNode;
      chartContainer.style.width = width + 'px';
      chartId === 'arsipChart' ? arsipChart.resize() : namaProdukChart.resize();
      chartContainer.style.transition = 'width 0.3s';
    }

    // Event listener untuk tombol lebar
    document.getElementById('toggleBtn').addEventListener('click', function() {
      resizeChart('arsipChart', 440);
    });

    // Event listener untuk tombol kecil
    document.getElementById('sidebar').addEventListener('click', function() {
      resizeChart('arsipChart', 440);
    });
  </script>
  <script src="../script/script.js" defer></script>
</body>

</html>