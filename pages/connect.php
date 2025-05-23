<?php
$host = "localhost";  // Sesuaikan dengan host database
$user = "root";       // Username database
$pass = "";           // Password database
$dbname = "proyek1";    // Nama database

$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}
?>