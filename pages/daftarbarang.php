<?php
include "session_check.php";
include "connect.php";


$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$data_user = $result->fetch_assoc();
$stmt->close();

// Keyword dan filter tanggal 
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$dari = isset($_GET['dari']) ? $_GET['dari'] : '';
$sampai = isset($_GET['sampai']) ? $_GET['sampai'] : '';

$where_clauses = [];

if (!empty($keyword)) {
  $safe_keyword = $conn->real_escape_string($keyword);
  $where_clauses[] = "(a.nama_perusahaan LIKE '%$safe_keyword%' 
                         OR a.no_faktur LIKE '%$safe_keyword%'
                         OR d.batch LIKE '%$safe_keyword%'
                         OR p.nama_produk LIKE '%$safe_keyword%')";
}

if (!empty($dari) && !empty($sampai)) {
  $where_clauses[] = "DATE(a.tgl_datang) BETWEEN '$dari' AND '$sampai'";
} elseif (!empty($dari)) {
  $where_clauses[] = "DATE(a.tgl_datang) >= '$dari'";
} elseif (!empty($sampai)) {
  $where_clauses[] = "DATE(a.tgl_datang) <= '$sampai'";
}

$where = "";
if (count($where_clauses) > 0) {
  $where = "WHERE " . implode(" AND ", $where_clauses);
}


// PAGINATION SETTING
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

// PAGINATION SETTING
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$mulai = ($halaman - 1) * $limit;

// Hitung total data (berdasarkan pencarian)
$sql_total = "SELECT COUNT(DISTINCT a.id_arsip) AS total 
              FROM arsip a
              LEFT JOIN detail_produk d ON a.id_arsip = d.id_arsip
              LEFT JOIN produk p ON d.id_produk = p.id_produk
              $where";
$total_data = $conn->query($sql_total)->fetch_assoc()['total'];

// Total halaman
$total_halaman = ceil($total_data / $limit);

// jika 0
if ($total_data == 0) {
  $start_range = 0;
  $end_range = 0;
} else {
  $start_range = $mulai + 1;
  $end_range = min($mulai + $limit, $total_data);
}

// Ambil daftar id_arsip berdasarkan pencarian + pagination
$sql_id_arsip = "SELECT a.id_arsip 
                 FROM arsip a
                 LEFT JOIN detail_produk d ON a.id_arsip = d.id_arsip
                 LEFT JOIN produk p ON d.id_produk = p.id_produk
                 $where
                 GROUP BY a.id_arsip
                 ORDER BY a.tgl_datang DESC
                 LIMIT $mulai, $limit";
$result_id = $conn->query($sql_id_arsip);

$id_arsip_list = [];
while ($row = $result_id->fetch_assoc()) {
  $id_arsip_list[] = $row['id_arsip'];
}

if (count($id_arsip_list) > 0) {
  $id_string = implode(",", $id_arsip_list);
  $sql = "SELECT a.id_arsip, a.nama_perusahaan, a.tgl_datang, a.no_faktur, a.jth_tempo, a.tgl_faktur, a.total, 
                   p.nama_produk, d.kadaluarsa, d.batch, d.jumlah, d.satuan
            FROM arsip a
            LEFT JOIN detail_produk d ON a.id_arsip = d.id_arsip
            LEFT JOIN produk p ON d.id_produk = p.id_produk
            WHERE a.id_arsip IN ($id_string)
            ORDER BY a.tgl_datang DESC, d.id_detail ASC";
  $result = $conn->query($sql);
  $data = $result->fetch_all(MYSQLI_ASSOC);
} else {
  $data = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../img/favicon.png" type="image/x-icon">
  <title>Daftar Arsip</title>
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
</head>

<body>
  <header>
    <h1>Daftar Arsip</h1>
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
          <a href="tambahbarang.php"><i class="fa-solid fa-square-plus"></i> <span>Tambah Arsip</span></a>
        </li>
        <li>
          <a href="#" id="daf_brg"><i class="fa-solid fa-chart-simple"></i> <span>Daftar Arsip</span></a>
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

    <div class="table-con">
      <div class="filter">
        <div class="print">
          <a href="tambahbarang.php"><i class="fas fa-plus"></i>Tambah</a>
          <a href="export_excel.php?keyword=<?= urlencode($keyword); ?>&dari=<?= $dari; ?>&sampai=<?= $sampai; ?>" class="cetakxcl">
            <i class="fas fa-file-excel"></i> Cetak Excel
          </a>
        </div>
        <form method="GET">
          <div class="row">
            <input type="text" name="keyword" placeholder="Cari"
              value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
          </div>
          <div class="row">
            <label for="dari">Dari:</label>
            <input type="date" name="dari" value="<?= isset($_GET['dari']) ? $_GET['dari'] : '' ?>" max="2100-01-01">

            <label for="sampai">Sampai:</label>
            <input type="date" name="sampai" value="<?= isset($_GET['sampai']) ? $_GET['sampai'] : '' ?>" max="2100-01-01">
          </div>
          <div class="row">
            <label for="limit">Jumlah:</label>
            <select name="limit">
              <option value="10" <?= isset($_GET['limit']) && $_GET['limit'] == '10' ? 'selected' : '' ?>>10</option>
              <option value="20" <?= isset($_GET['limit']) && $_GET['limit'] == '20' ? 'selected' : '' ?>>20</option>
              <option value="30" <?= isset($_GET['limit']) && $_GET['limit'] == '30' ? 'selected' : '' ?>>30</option>
              <option value="50" <?= isset($_GET['limit']) && $_GET['limit'] == '50' ? 'selected' : '' ?>>50</option>
            </select>
          </div>
          <div class="row">
            <button type="submit">Tampilkan</button>
            <a href="daftarbarang.php"><button type="button">Reset</button></a>
          </div>
        </form>
      </div>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Perusahaan</th>
            <th>Tanggal Datang</th>
            <th>No Faktur</th>
            <th>Jatuh Tempo</th>
            <th>Tanggal Faktur</th>
            <th>Total</th>
            <th>Nama Produk</th>
            <th>Kadaluarsa</th>
            <th>No Batch</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $last_id = null;
          $rowspan = [];
          $warna_grup = []; // Menyimpan warna untuk setiap ID arsip
          $no = $mulai + 1; // Menambahkan nilai mulai untuk halaman

          $warna_index = 0; // untuk menentukan warna selang-seling

          // Hitung jumlah produk per arsip dan tentukan warna berdasarkan urutan
          foreach ($data as $row) {
            $id_arsip = $row['id_arsip'];

            if (!isset($rowspan[$id_arsip])) {
              $rowspan[$id_arsip] = 1;

              // Warna ditentukan berdasarkan urutan kemunculan, bukan ID
              $warna_grup[$id_arsip] = ($warna_index % 2 == 0) ? 'genap' : 'ganjil';
              $warna_index++;
            } else {
              $rowspan[$id_arsip]++;
            }
          }


          // Tampilkan data dengan warna yang tetap
          foreach ($data as $row):
            $id_arsip = $row['id_arsip'];
          ?>
            <tr class="<?= $warna_grup[$id_arsip]; ?> group-<?= $id_arsip; ?>">
              <?php if ($last_id !== $id_arsip): ?>
                <td rowspan="<?= $rowspan[$id_arsip]; ?>"><?= $no++; ?></td> <!-- Nomor urut -->
                <td rowspan="<?= $rowspan[$id_arsip]; ?>"><?= $row['nama_perusahaan']; ?></td>
                <td rowspan="<?= $rowspan[$id_arsip]; ?>"><?= date("d-m-Y", strtotime($row['tgl_datang'])); ?></td>
                <td rowspan="<?= $rowspan[$id_arsip]; ?>"><?= $row['no_faktur']; ?></td>
                <td rowspan="<?= $rowspan[$id_arsip]; ?>"><?= date("d-m-Y", strtotime($row['jth_tempo'])); ?></td>
                <td rowspan="<?= $rowspan[$id_arsip]; ?>"><?= date("d-m-Y", strtotime($row['tgl_faktur'])); ?></td>
                <td rowspan="<?= $rowspan[$id_arsip]; ?>">
                  Rp <?= number_format($row['total'], 0, ',', '.'); ?>
                </td>
              <?php endif; ?>

              <td><?= ($row['nama_produk']); ?></td>
              <td><?= date("d-m-Y", strtotime($row['kadaluarsa'])); ?></td>
              <td><?= ($row['batch']); ?></td>
              <td><?= ($row['jumlah']); ?></td>
              <td><?= ($row['satuan']); ?></td>

              <?php if ($last_id !== $id_arsip): ?>
                <td rowspan="<?= $rowspan[$id_arsip]; ?>">
                  <a href="edit.php?id=<?= $row['id_arsip']; ?>" class="edit-link"
                    data-id="<?= $row['id_arsip']; ?>"
                    onclick="return false;"><i class="fa-solid fa-pen"></i></a>
                  <a href="hapus.php?id=<?= $row['id_arsip']; ?>" class="hapus-link"
                    data-id="<?= $row['id_arsip']; ?>"
                    onclick="return false;"><i class="fa-solid fa-trash-can"></i></a>
                </td>
              <?php endif; ?>
            </tr>
          <?php
            $last_id = $id_arsip;
          endforeach;
          ?>
        </tbody>
      </table>

      <div class="table-footer">
        <!-- Menampilkan informasi tentang rentang data -->
        <div class="data-range">
          <?php
          if ($total_data == 0) {
            echo '<p>Tidak ada data ditemukan</p>';
          } else {
            echo "<p>Menampilkan $start_range – $end_range dari $total_data data.</p>";
          }
          ?>
        </div>

        <!-- page per 10 data -->
        <div class="pagination">
          <?php
          if ($total_halaman > 1) {
            $jumlah_link_tengah = 2;

            // Tombol ke halaman pertama
            if ($halaman > 1) {
              echo '<a href="?halaman=1' . (!empty($keyword) ? '&keyword=' . urlencode($keyword) : '') . (!empty($dari) ? '&dari=' . $dari : '') . (!empty($sampai) ? '&sampai=' . $sampai : '') . '&limit=' . $limit . '">&laquo; Awal</a>';
            }

            // Tampilkan "..." jika perlu
            if ($halaman > ($jumlah_link_tengah + 2)) {
              echo '<span>...</span>';
            }

            $start = max(1, $halaman - $jumlah_link_tengah);
            $end = min($total_halaman, $halaman + $jumlah_link_tengah);

            for ($i = $start; $i <= $end; $i++) {
              if ($i == $halaman) {
                echo '<a href="?halaman=' . $i . (!empty($keyword) ? '&keyword=' . urlencode($keyword) : '') . (!empty($dari) ? '&dari=' . $dari : '') . (!empty($sampai) ? '&sampai=' . $sampai : '') . '&limit=' . $limit . '" class="active">' . $i . '</a>';
              } else {
                echo '<a href="?halaman=' . $i . (!empty($keyword) ? '&keyword=' . urlencode($keyword) : '') . (!empty($dari) ? '&dari=' . $dari : '') . (!empty($sampai) ? '&sampai=' . $sampai : '') . '&limit=' . $limit . '">' . $i . '</a>';
              }
            }

            if ($halaman < ($total_halaman - $jumlah_link_tengah - 1)) {
              echo '<span>...</span>';
            }

            // Tombol ke halaman terakhir
            if ($halaman < $total_halaman) {
              echo '<a href="?halaman=' . $total_halaman . (!empty($keyword) ? '&keyword=' . urlencode($keyword) : '') . (!empty($dari) ? '&dari=' . $dari : '') . (!empty($sampai) ? '&sampai=' . $sampai : '') . '&limit=' . $limit . '">Akhir &raquo;</a>';
            }
          }
          ?>
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

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const rows = document.querySelectorAll("tr[class*='group-']");

      rows.forEach(row => {
        const groupClass = [...row.classList].find(c => c.startsWith('group-'));
        const groupId = groupClass?.split('-')[1];
        if (!groupId) return;

        const groupRows = document.querySelectorAll(`.group-${groupId}`);

        row.addEventListener("mouseenter", () => {
          groupRows.forEach(gr => gr.classList.add("hover"));
        });

        row.addEventListener("mouseleave", () => {
          groupRows.forEach(gr => gr.classList.remove("hover"));
        });
      });
    });

    document.querySelectorAll('.hapus-link').forEach(function(link) {
      link.addEventListener('click', function() {
        const id = this.getAttribute('data-id');

        // Menampilkan SweetAlert2
        Swal.fire({
          title: 'Hapus Arsip?',
          text: "Data ini akan dihapus secara permanen.",
          icon: 'warning',
          showCancelButton: true,
          background: "#2d336b",
          color: "#ffffff",
          confirmButtonColor: "#d33",
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: "Berhasil",
              text: "Data berhasil dihapus.",
              icon: "success",
              background: "#2d336b",
              color: "#ffffff",
              showConfirmButton: false,
              timer: 2000
            }).then(() => {
              // Redirect setelah alert sukses ditutup
              window.location.href = 'hapus.php?id=' + id;
            });
          }
        });
      });
    });

    document.querySelectorAll('.edit-link').forEach(function(link) {
      link.addEventListener('click', function() {
        const id = this.getAttribute('data-id');

        // Menampilkan SweetAlert2 edit link
        Swal.fire({
          title: 'Edit Arsip?',
          text: "Anda akan memasuki mode edit!",
          icon: 'warning',
          showCancelButton: true,
          background: "#2d336b",
          color: "#ffffff",
          confirmButtonColor: "#00BB3E",
          cancelButtonColor: "#d33",
          confirmButtonText: 'Edit',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = 'edit.php?id=' + id;
          }
        });
      });
    });
  </script>
  <script src="../script/script.js" defer></script>
</body>

</html>