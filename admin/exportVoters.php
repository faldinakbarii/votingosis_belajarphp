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

$query = $database->query("SELECT * FROM voters");

// BUAT SPREADSHEET
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// HEADER KOLOM (sesuaikan kolom lu)
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nama');
$sheet->setCellValue('C1', 'NISN');
$sheet->setCellValue('D1', 'Kelas');
$sheet->setCellValue('E1', 'Pilihan');
$sheet->setCellValue('F1', 'Vote Time');

// ISI DATA
$row = 2;
while ($data = $query->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['id']);
    $sheet->setCellValue('B' . $row, $data['nama']);
    $sheet->setCellValue('C' . $row, $data['nisn']);
    $sheet->setCellValue('D' . $row, $data['kelas']);
    $sheet->setCellValue('E' . $row, $data['vote_who']);
    $sheet->setCellValue('F' . $row, $data['vote_time']);
    $row++;
}

// DOWNLOAD FILE KE BROWSER
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename='DATA VOTERS_$tanggalWaktuSekarang.xlsx'");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;