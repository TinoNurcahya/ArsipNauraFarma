function toggleSidebar() {
  let sidebar = document.getElementById("sidebar");
  let toggleBtn = document.getElementById("toggleBtn");
  const icons = toggleBtn.querySelectorAll("i");

  // Toggle sidebar
  sidebar.classList.toggle("mini");

  // Simpan status ke localStorage
  if (sidebar.classList.contains("mini")) {
    localStorage.setItem("sidebarState", "mini");
    icons[0].classList.remove("hidden"); // icon buka
    icons[1].classList.add("hidden"); // icon tutup
  } else {
    localStorage.setItem("sidebarState", "normal");
    icons[0].classList.add("hidden"); // icon buka
    icons[1].classList.remove("hidden"); // icon tutup
  }
}

window.addEventListener("DOMContentLoaded", () => {
  let sidebar = document.getElementById("sidebar");
  let toggleBtn = document.getElementById("toggleBtn");
  const icons = toggleBtn.querySelectorAll("i");

  const sidebarState = localStorage.getItem("sidebarState");

  if (sidebarState === "mini") {
    sidebar.classList.add("mini");
    icons[0].classList.remove("hidden"); // icon buka
    icons[1].classList.add("hidden"); // icon tutup
  } else {
    sidebar.classList.remove("mini");
    icons[0].classList.add("hidden"); // icon buka
    icons[1].classList.remove("hidden"); // icon tutup
  }
});

let timeout = 21600000; // 1 jam dalam milidetik (3600 detik * 1000)
let logoutTimer;

function resetTimer() {
  clearTimeout(logoutTimer); // Hapus timer lama
  logoutTimer = setTimeout(autoLogout, timeout); // Set timer baru
}

function autoLogout() {
  alert("Sesi Anda telah berakhir. Anda akan logout otomatis.");
  window.location.href = "logout.php";
}
// Set timer awal saat halaman dimuat
resetTimer();

// Deteksi aktivitas pengguna (gerakan mouse, klik, tombol keyboard)
document.addEventListener("mousemove", resetTimer);
document.addEventListener("keypress", resetTimer);
document.addEventListener("click", resetTimer);
document.addEventListener("scroll", resetTimer);

//* start Fitur Logout alert
document.getElementById("logoutButton").addEventListener("click", function (event) {
  event.preventDefault();
  Swal.fire({
    title: "Apakah kamu yakin?",
    text: "Kamu akan keluar!",
    icon: "warning",
    background: "#2d336b",
    color: "#ffffff",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    confirmButtonText: "Keluar",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "logout.php";
    }
  });
});
//* end Fitur Logout alert


//* start tambahbarang.php
let inputCounters = {}; // Objek untuk menyimpan counter per container

function tambahInput(containerId, type = "text", isSatuSet = false) {
  let container = document.getElementById(containerId);

  // Buat elemen div baru untuk input tambahan
  let newInputBox = document.createElement("div");
  newInputBox.classList.add("input-box");

  // Tambahkan ikon sesuai jenis input
  let icon = document.createElement("span");
  icon.classList.add("icon");
  if (containerId.includes("produk-container")) {
    icon.innerHTML = '<i class="fa-solid fa-box-open"></i>';
  } else if (containerId.includes("kadaluarsa-container")) {
    icon.innerHTML = '<i class="fa-solid fa-hourglass-half"></i>';
  } else {
    icon.innerHTML = '<i class="fa-solid fa-arrow-down-1-9"></i>';
  }

  // Buat elemen input baru
  let newInput = document.createElement("input");
  newInput.type = type;
  newInput.required = true; // Tambahkan required

  // Tentukan name dan placeholder sesuai container
  if (containerId.includes("produk-container")) {
    newInput.name = "nama_produk[]";
    newInput.placeholder = "Masukkan Nama Produk";
  } else if (containerId.includes("kadaluarsa")) {
    newInput.name = "kadaluarsa[]";
    newInput.type = "date";
    newInput.max = "2100-12-31";
  } else if (containerId.includes("batch-container")) {
    newInput.name = "batch[]";
    newInput.placeholder = "Masukkan No. Batch";
  } else if (containerId.includes("jumlah-container")) {
    newInput.name = "jumlah[]";
    newInput.placeholder = "Masukkan Jumlah Barang";
  } else {
    newInput.name = `${containerId}[]`;
    newInput.placeholder = "Masukkan data baru";
  }

  // Tombol hapus (-)
  let removeBtn = document.createElement("button");
  removeBtn.type = "button";
  removeBtn.classList.add("remove-btn");
  removeBtn.innerHTML = "−";

  // Atur tombol hapus untuk semua input terkait
  if (isSatuSet) {
    removeBtn.onclick = function () {
      let index = Array.from(container.children).indexOf(newInputBox);
      hapusSatuSet(index); // Panggil hapus satu set
    };
  } else {
    removeBtn.onclick = function () {
      container.removeChild(newInputBox);
      updatePlaceholders(containerId); // Update placeholder setelah hapus
    };
  }

  // Masukkan elemen ke dalam div baru
  newInputBox.appendChild(icon);
  newInputBox.appendChild(newInput);
  newInputBox.appendChild(removeBtn);

  // Tambahkan ke container
  container.appendChild(newInputBox);
  updatePlaceholders(containerId); // Update placeholder setelah input baru ditambahkan
}

let produkCounter = 2;

function tambahSatuSetBarang() {
  // Tambah input nama produk dengan autocomplete
  var produkContainer = document.getElementById("produk-container");
  var newProdukBox = document.createElement("div");
  newProdukBox.classList.add("input-box");

  let inputId = "nama_produk_input_" + produkCounter++;

  newProdukBox.innerHTML = `
    <span class="icon"><i class="fa-solid fa-box-open"></i></span>
    <input type="text" class="nama_produk_input" id="${inputId}" name="nama_produk[]" placeholder="Masukkan Nama obat/alkes" required>
    <button type="button" class="remove-btn" onclick="this.parentNode.remove()">-</button>
  `;
  produkContainer.appendChild(newProdukBox);

  var newInput = newProdukBox.querySelector("input.nama_produk_input");
  autocomplete(newInput, produkList);

  updatePlaceholders("produk-container");

  // Tambah input lainnya (kadaluarsa, batch, jumlah, satuan)
  tambahInput("kadaluarsa-container", "date", true);
  tambahInput("batch-container", "text", true);
  tambahInput("jumlah-container", "number", true);
  let satuanInputBaru = tambahDropdown(true);
  autocomplete(satuanInputBaru, satuanList);
}


// Fungsi untuk memperbarui nomor placeholder setelah elemen dihapus
function updatePlaceholders(containerId) {
  let container = document.getElementById(containerId);
  let inputBoxes = container.querySelectorAll(".input-box");

  inputBoxes.forEach((box, index) => {
    let input = box.querySelector("input");
    if (containerId.includes("produk-container")) {
      input.placeholder = `Masukkan Nama Obat/Alkes ke ${index + 1}`;
    } else if (containerId.includes("batch-container")) {
      input.placeholder = `Masukkan No. Batch ke ${index + 1}`;
    } else if (containerId.includes("jumlah-container")) {
      input.placeholder = `Masukkan Jumlah Barang ke ${index + 1}`;
    }
  });
}

function tambahDropdown(isSatuSet = false) {
  var container = document.getElementById("satuan-container");

  let newInputBox = document.createElement("div");
  newInputBox.classList.add("input-box");

  let icon = document.createElement("span");
  icon.classList.add("icon");
  icon.innerHTML = '<i class="fa-solid fa-list"></i>';

  let newSelect = document.createElement("select");
  newSelect.classList.add("satuan");
  newSelect.name = "satuan[]";
  newSelect.required = true;
  newSelect.onchange = function () {
    cekSatuan(newSelect);
  };
  newSelect.innerHTML = `
    <option value="">-- Pilih Satuan --</option>
    <option value="PCS">PCS</option>
    <option value="KG">KG</option>
    <option value="BOX">BOX</option>
    <option value="FLS">FLS</option>
    <option value="LAINNYA">LAINNYA</option>
  `;

  let newTextInput = document.createElement("input");
  newTextInput.type = "text";
  newTextInput.classList.add("satuan-lainnya");
  newTextInput.name = "satuan_lainnya[]";
  newTextInput.placeholder = "Masukkan satuan lain";
  newTextInput.style.display = "none";
  newTextInput.width = "100%";

  let removeBtn = document.createElement("button");
  removeBtn.type = "button";
  removeBtn.classList.add("remove-btn");
  removeBtn.innerHTML = "−";

  if (isSatuSet) {
    removeBtn.onclick = function () {
      let index = Array.from(container.children).indexOf(newInputBox);
      hapusSatuSet(index);
    };
  } else {
    removeBtn.onclick = function () {
      container.removeChild(newInputBox);
    };
  }

  newInputBox.appendChild(icon);
  newInputBox.appendChild(newSelect);
  newInputBox.appendChild(newTextInput);
  newInputBox.appendChild(removeBtn);

  container.appendChild(newInputBox);
    return newTextInput;
}

function cekSatuan(selectElement) {
  let textInput = selectElement.nextElementSibling;
  if (selectElement.value === "LAINNYA") {
    textInput.style.display = "block";
    textInput.style.width = "100%";
    textInput.required = true;
    textInput.focus();
  } else {
    textInput.style.display = "none";
  }
}

document.querySelector("form").addEventListener("submit", function (e) {
  const satuanSelects = document.querySelectorAll('select[name="satuan[]"]');
  satuanSelects.forEach((select) => {
    if (select.value === "LAINNYA") {
      const inputLainnya = select.nextElementSibling;
      if (inputLainnya && inputLainnya.value.trim() !== "") {
        select.value = "LAINNYA"; // Tidak perlu ubah ke input, biarkan PHP yang proses
      }
    }
  });
});

function hapusSatuSet(index) {
  // Array container yang berisi input yang ingin dihapus
  const containerIds = [
    "produk-container",
    "kadaluarsa-container",
    "batch-container",
    "jumlah-container",
    "satuan-container",
  ];

  // Hapus elemen pada masing-masing container yang sesuai dengan index
  containerIds.forEach((id) => {
    const container = document.getElementById(id);
    const inputBoxes = container.querySelectorAll(".input-box");
    if (inputBoxes[index]) {
      container.removeChild(inputBoxes[index]);
    }
  });

  // Update placeholder setelah menghapus
  containerIds.forEach((id) => {
    updatePlaceholders(id);
  });
}

// format input rupiah
const inputRupiah = document.getElementById("inputRupiah");
if (inputRupiah) {
  inputRupiah.addEventListener("input", function () {
    let angka = this.value.replace(/[^0-9]/g, "");
    this.value = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  });
}

// menangani submit form
document.getElementById("barangForm").addEventListener("submit", function (event) {
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
    confirmButtonText: "Tambah",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      let angkaMentah = inputRupiah.value.replace(/\./g, "");
      inputRupiah.value = angkaMentah;

      Swal.fire({
        title: "Berhasil!",
        text: "Item berhasil ditambahkan 👌",
        icon: "success",
        confirmButtonColor: "#2d336b",
      }).then(() => {
        console.log(form);
        form.submit(); //  Submit form setelah SweetAlert sukses
      });
    }
  });
});
//* end tambahbarang.php

//* start script daftarbarang.php
//* end script daftarbarang.php


//* MODAL PROFILE START 
function openInfoModal() {
  const modal = document.getElementById("infoModal");
  modal.style.display = "block";
  document.getElementById("infoSection").style.display = "block";
  document.getElementById("editFormSection").style.display = "none";
}

function closeInfoModal() {
  document.getElementById("infoModal").style.display = "none";
}

function showEditForm() {
  document.getElementById("infoSection").style.display = "none";
  document.getElementById("editFormSection").style.display = "block";
}

function cancelEdit() {
  document.getElementById("editFormSection").style.display = "none";
  document.getElementById("infoSection").style.display = "block";
}

// Tutup modal saat klik di luar konten modal
window.onclick = function (event) {
  if (event.target.classList.contains("modal-overlay")) {
    event.target.style.display = "none";
  }
};
//* MODAL PROFILE END