<?php
require '../vendor/autoload.php';

$sales = $_GET['sales'];
$dari = $_GET['dari'];
$sampai = $_GET['sampai'];
// koneksi php dan mysql
$servername = "localhost";
$username = "sumberre_bond2";
$password = "creativity_unlimited2020";

$db = "sumberre_esp";

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pdo = new PDO('mysql:host=localhost;dbname=sumberre_esp', $username, $password);

date_default_timezone_set('Asia/Jakarta');
setlocale(LC_ALL, 'IND');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
echo $sales;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// sheet peratama
$sheet->setTitle('Sheet 1');

$sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(13);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(12);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(6);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(6);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(17);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(14);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);

$spreadsheet->getActiveSheet()->mergeCells('A1:K1');
$spreadsheet->getActiveSheet()->mergeCells('A2:K2');
$spreadsheet->getActiveSheet()->mergeCells('A3:K3');


$styleArray = array(
    'font'  => [
        'bold'  => true,
        'color' => array('rgb' => '305496'),
        'size'  => 16
    ],
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
);

$spreadsheet->getActiveSheet()->getCell('A1')->setValue('LAPORAN POINT KUNJUNGAN SALES');
$spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(40);


$styleArray = array(
    'font'  => [
        'bold'  => true,
        'color' => array('rgb' => '963634'),
        'size'  => 12
    ],
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
);

$tgl_dari = explode(' ', $dari);
$tgl_sampai = explode(' ', $sampai);
$spreadsheet->getActiveSheet()->getCell('A2')->setValue('PERIODE '.$tgl_dari[1].' '.$tgl_dari[0].' '.$tgl_dari[2].' - '.$tgl_sampai[1].' '.$tgl_sampai[0].' '.$tgl_sampai[2]);
$spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(23);

$styleArray = array(
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
);
$now = date("d-F-Y");
$tgl_cetak = explode('-', $now);

$sheet->setCellValue('A3', 'Tanggal dicetak : '.$tgl_cetak[0].' '.$tgl_cetak[1].' '.$tgl_cetak[2]);
$spreadsheet->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray);

$styleArray = array(
    'font'  => [
        'bold'  => true
    ],
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    ],
);
$spreadsheet->getActiveSheet()->getCell('A5')->setValue('NAMA');
$spreadsheet->getActiveSheet()->getStyle('A5')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('A6')->setValue('TARGET');
$spreadsheet->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('A7')->setValue('TOTAL POINT');
$spreadsheet->getActiveSheet()->getStyle('A7')->applyFromArray($styleArray);

$spreadsheet->getActiveSheet()->getCell('B5')->setValue($sales);

$styleArray = array(
    'font'  => [
        'bold'  => true
    ],
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ]
);

$spreadsheet->getActiveSheet()->mergeCells('D9:E9');
$spreadsheet->getActiveSheet()->mergeCells('H9:K9');

$spreadsheet->getActiveSheet()->getCell('D9')->setValue('LOGIN PT');
$spreadsheet->getActiveSheet()->getStyle('D9')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getStyle('E9')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('H9')->setValue('POINT');
$spreadsheet->getActiveSheet()->getStyle('H9:K9')->applyFromArray($styleArray);

$spreadsheet->getActiveSheet()->getCell('A10')->setValue('TGL DIBUAT RK');
$spreadsheet->getActiveSheet()->getStyle('A10')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('B10')->setValue('TGL RK');
$spreadsheet->getActiveSheet()->getStyle('B10')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('C10')->setValue('TGL KJ');
$spreadsheet->getActiveSheet()->getStyle('C10')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('D10')->setValue('IN');
$spreadsheet->getActiveSheet()->getStyle('D10')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('E10')->setValue('OUT');
$spreadsheet->getActiveSheet()->getStyle('E10')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('F10')->setValue('NAMA CUSTOMER');
$spreadsheet->getActiveSheet()->getStyle('F10')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('G10')->setValue('KATEGORI');
$spreadsheet->getActiveSheet()->getStyle('G10')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('H10')->setValue('EC/WA');
$spreadsheet->getActiveSheet()->getStyle('H10')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('I10')->setValue('KJ');
$spreadsheet->getActiveSheet()->getStyle('I10')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('J10')->setValue('DILUAR RK');
$spreadsheet->getActiveSheet()->getStyle('J10')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getCell('K10')->setValue('TOTAL');
$spreadsheet->getActiveSheet()->getStyle('K10')->applyFromArray($styleArray);
$spreadsheet->getActiveSheet()->getRowDimension('10')->setRowHeight(21);


$tanggal = intval($tgl_dari[1]) +1;
$sql_dari = $tgl_dari[2]."/".$tgl_dari[0]."/".$tanggal;

$tanggal = intval($tgl_sampai[1]) + 1;
$sql_sampai = $tgl_sampai[2] . "/" . $tgl_sampai[0] . "/" . $tanggal;
// membaca data dari mysql
 $sql = "SELECT * FROM tmkunjungan WHERE KODESALESMAN = :KODESALESMAN AND DATETRANSACTION between '".$sql_dari."' and '".$sql_sampai."'";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'KODESALESMAN' => $sales
]);

$i = 11;
if ($stmt->rowCount() > 0) {
    while ($kunjungan = $stmt->fetch()) 
    {
        $sheet->setCellValue('A' . $i, $kunjungan['NOMORBUKTI']);
        $i++;
    }
}



?>