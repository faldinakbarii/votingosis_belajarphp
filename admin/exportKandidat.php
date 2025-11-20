<?php  
session_start();
if (!isset($_SESSION["loginAdmin"])) {
    header("Location: auth/login.php");
    exit;
}

require '../vendor/autoload.php';
require '../include/functions.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$query = $database->query("SELECT * FROM candidate");

// BUAT SPREADSHEET
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


// HEADER KOLOM (sesuaikan kolom lu)
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nama Ketua');
$sheet->setCellValue('C1', 'Nama Wakil');
$sheet->setCellValue('D1', 'NISN Ketua');
$sheet->setCellValue('E1', 'NISN Wakil');
$sheet->setCellValue('F1', 'Kelas Ketua');
$sheet->setCellValue('G1', 'Kelas Wakil');
$sheet->setCellValue('H1', 'VISI');
$sheet->setCellValue('I1', 'MISI');
$sheet->setCellValue('J1', 'Total Suara');

// nama ketua & wakil
$hasil = ShowsUseObj("SELECT * FROM candidate");

$row = 2;
foreach ($hasil as $semua) {
    // define
    $namaK = explode("&", $semua->nama)[0];
    $namaW = explode("&", $semua->nama)[1];
    $kelasK = explode("&", $semua->kelas)[0];
    $kelasW = explode("&", $semua->kelas)[1];

    // isi data
    $sheet->setCellValue('A' . $row, $semua->id);
    $sheet->setCellValue('B' . $row, $namaK);
    $sheet->setCellValue('C' . $row, $namaW);
    $sheet->setCellValue('D' . $row, $semua->nisn_ketua);
    $sheet->setCellValue('E' . $row, $semua->nisn_wakil);
    $sheet->setCellValue('F' . $row, $kelasK);
    $sheet->setCellValue('G' . $row, $kelasW);
    $sheet->setCellValue('H' . $row, $semua->visi);
    $sheet->setCellValue('I' . $row, $semua->misi);
    $sheet->setCellValue('J' . $row, $semua->jumlah_suara);
    $row++;
}

ob_clean();

// DOWNLOAD FILE KE BROWSER
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename='DATA KANDIDAT_$tanggalWaktuSekarang.xlsx'");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;