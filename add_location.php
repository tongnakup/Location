<?php
// เรียกใช้ Autoloader และคลาสที่จำเป็น
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 1. รับข้อมูลและตรวจสอบ
if (!isset($_POST['location_code']) || empty(trim($_POST['location_code']))) {
    // ถ้าไม่มีข้อมูลส่งมา ให้ส่งกลับไปหน้า dashboard พร้อม error
    header('Location: dashboard.php?status=error&message=' . urlencode('กรุณากรอก Location Code'));
    exit;
}
$newLocationCode = trim($_POST['location_code']);


// 2. กำหนดตำแหน่งไฟล์
$excelFilePath = __DIR__ . '/source/data.xlsx';


try {
    // 3. โหลด (หรือสร้าง) ไฟล์ Excel
    if (file_exists($excelFilePath)) {
        // ถ้ามีไฟล์ data.xlsx อยู่แล้ว ให้โหลดขึ้นมา
        $spreadsheet = IOFactory::load($excelFilePath);
    } else {
        // ถ้ายังไม่มีไฟล์ ให้สร้างขึ้นมาใหม่
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        // สร้างหัวข้อตารางไว้ที่แถวแรก
        $worksheet->setCellValue('A1', 'LocationCode');
    }

    $worksheet = $spreadsheet->getActiveSheet();

    // 4. หาแถวว่างสุดท้ายแล้วเพิ่มข้อมูล
    $nextRow = $worksheet->getHighestRow() + 1;
    $worksheet->setCellValue('A' . $nextRow, $newLocationCode);

    // 5. บันทึกไฟล์ทับของเดิม
    $writer = new Xlsx($spreadsheet);
    $writer->save($excelFilePath);

    // 6. ส่งผู้ใช้กลับไปหน้า Dashboard พร้อมข้อความสำเร็จ
    header('Location: dashboard.php?status=success&code=' . urlencode($newLocationCode));
    exit;
} catch (Exception $e) {
    // กรณีเกิดข้อผิดพลาดในการอ่าน/เขียนไฟล์
    header('Location: dashboard.php?status=error&message=' . urlencode('เกิดข้อผิดพลาดกับไฟล์ Excel: ' . $e->getMessage()));
    exit;
}
