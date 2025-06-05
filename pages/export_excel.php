<?php
require '../vendor/autoload.php';
include "connect.php";


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Ambil filter dari URL
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$dari = isset($_GET['dari']) ? $_GET['dari'] : '';
$sampai = isset($_GET['sampai']) ? $_GET['sampai'] : '';

$where_clauses = [];

if (!empty($keyword)) {
    $safe_keyword = $conn->real_escape_string($keyword);
    $where_clauses[] = "(a.nama_perusahaan LIKE '%$safe_keyword%' 
                         OR a.no_faktur LIKE '%$safe_keyword%' 
                         OR p.nama_produk LIKE '%$safe_keyword%')";
}

if (!empty($dari) && !empty($sampai)) {
    $where_clauses[] = "DATE(a.tgl_datang) BETWEEN '$dari' AND '$sampai'";
} elseif (!empty($dari)) {
    $where_clauses[] = "DATE(a.tgl_datang) >= '$dari'";
} elseif (!empty($sampai)) {
    $where_clauses[] = "DATE(a.tgl_datang) <= '$sampai'";
}

$where = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Query gabungan data arsip
$sql = "SELECT 
            a.id_arsip, a.nama_perusahaan, a.tgl_datang,  a.no_faktur, a.jth_tempo, a.tgl_faktur, a.total,
            p.nama_produk, d.kadaluarsa, d.batch, d.jumlah, d.satuan
        FROM arsip a
        LEFT JOIN detail_produk d ON a.id_arsip = d.id_arsip
        LEFT JOIN produk p ON d.id_produk = p.id_produk
        $where
        ORDER BY a.tgl_datang DESC";

$result = $conn->query($sql);

// Buat file Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Judul kolom
$headers = [
    'Perusahaan', 'Tanggal Datang', 'Nomor Faktur', 'Jatuh Tempo', 'Tanggal Faktur', 'Total',
    'Nama Produk', 'Kadaluarsa', 'Batch', 'Jumlah', 'Satuan'
];
$sheet->fromArray($headers, NULL, 'A1');

// Isi data
$row_num = 2;
$current_perusahaan = ''; // Menyimpan nama perusahaan saat ini
$current_id_arsip = 0; // Menyimpan id_arsip yang sedang diproses
$current_tgl_datang = ''; // Menyimpan tgl_datang yang sudah ditampilkan
$current_no_faktur = ''; // Menyimpan no_faktur yang sudah ditampilkan
$current_jth_tempo = ''; // Menyimpan jth_tempo yang sudah ditampilkan
$current_tgl_faktur = ''; // Menyimpan tgl_faktur yang sudah ditampilkan
$current_total = ''; //menyimpan total
$is_zebra_stripe = false; // Menyimpan status zebra stripe

$first_arsip = true;
$is_zebra_stripe = false;

while ($row = $result->fetch_assoc()) {
    if ($current_id_arsip !== $row['id_arsip']) {
        $current_id_arsip = $row['id_arsip'];
        $current_perusahaan = $row['nama_perusahaan'];
        $current_tgl_datang = $row['tgl_datang'];
        $current_no_faktur = $row['no_faktur'];
        $current_jth_tempo = $row['jth_tempo'];
        $current_tgl_faktur = $row['tgl_faktur'];
        $current_total = $row['total'];

        // Tampilkan data perusahaan
        $sheet->setCellValue('A' . $row_num, $current_perusahaan);
        $sheet->setCellValue('B' . $row_num, date("d-m-Y", strtotime($current_tgl_datang)));
        $sheet->setCellValue('C' . $row_num, $current_no_faktur);
        $sheet->setCellValue('D' . $row_num, date("d-m-Y", strtotime($current_jth_tempo)));
        $sheet->setCellValue('E' . $row_num, date("d-m-Y", strtotime($current_tgl_faktur)));
        $sheet->setCellValue('F' . $row_num, $current_total);

        // Skip toggle pertama kali agar arsip pertama tetap putih
        if ($first_arsip) {
            $first_arsip = false;
        } else {
            $is_zebra_stripe = !$is_zebra_stripe;
        }
    } else {
        // Kosongkan data yang tidak perlu ditampilkan ulang
        $sheet->setCellValue('A' . $row_num, '');
        $sheet->setCellValue('B' . $row_num, '');
        $sheet->setCellValue('C' . $row_num, '');
        $sheet->setCellValue('D' . $row_num, '');
        $sheet->setCellValue('E' . $row_num, '');
        $sheet->setCellValue('F' . $row_num, '');
    }

    // Data produk
    $sheet->fromArray([
        $row['nama_produk'],
        date("d-m-Y", strtotime($row['kadaluarsa'])),
        $row['batch'],
        $row['jumlah'],
        $row['satuan']
    ], NULL, 'G' . $row_num);

    // Terapkan zebra stripe jika flag true
    if ($is_zebra_stripe) {
        $sheet->getStyle("A$row_num:K$row_num")->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9EAF7');
    }

    $row_num++;
}

// Auto-size kolom
foreach (range('A', 'K') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Styling header
$sheet->getStyle('A1:K1')->getFont()->setBold(true);
$sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000');
// Terapkan rata tengah ke semua sel dari A1 sampai baris terakhir
$sheet->getStyle('A1:K' . ($row_num - 1))
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
// Format angka
$sheet->getStyle("E2:E$row_num")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
$sheet->getStyle("I2:I$row_num")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

// Border semua sel
$sheet->getStyle("A1:K" . ($row_num - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Header untuk download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="arsip_obat.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
