<?php
// เรียกใช้ Autoloader และคลาสที่จำเป็นทั้งหมด
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\RoundBlockSizeMode;
use PhpOffice\PhpSpreadsheet\IOFactory;

// --- 1. กำหนดตำแหน่งไฟล์ Excel โดยใช้ Relative Path (ดีกว่าเดิม) ---
// __DIR__ คือที่อยู่ของโฟลเดอร์ปัจจุบันที่ไฟล์ process.php นี้อยู่ (C:\xampp\htdocs\Location)
$excelFilePath = __DIR__ . '/source/data.xlsx';


// ตรวจสอบว่ามีไฟล์อยู่จริงหรือไม่
if (file_exists($excelFilePath)) {

    // --- 2. โหลดไฟล์ Excel และดึงข้อมูล ---
    try {
        $spreadsheet = IOFactory::load($excelFilePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $html = '';

        // --- 3. วนลูปสร้าง HTML สำหรับแต่ละป้าย ---
        for ($row = 2; $row <= $highestRow; ++$row) {
            $locationCode = $worksheet->getCell('A' . $row)->getValue();

            if (empty($locationCode)) {
                continue;
            }

            // (ส่วนที่เหลือของโค้ดเหมือนเดิมทุกประการ)
            $qrCode = new QrCode(
                $locationCode,
                new Encoding('UTF-8'),
                ErrorCorrectionLevel::High,
                300,
                10,
                RoundBlockSizeMode::Margin,
                new Color(0, 0, 0),
                new Color(255, 255, 255)
            );
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $qrCodeDataUri = $result->getDataUri();

            $currentDate = (new DateTime("now", new DateTimeZone('Asia/Bangkok')))->format('d/m/Y');

            $html .= '
            <table class="label-table">
                <tr><td colspan="3" class="location-header">Location</td></tr>
                <tr>
                    <td colspan="3" class="location-code-text">' . htmlspecialchars($locationCode) . '</td>
                </tr>
                <tr>
                    <td colspan="3" class="qr-cell">
                        <img src="' . $qrCodeDataUri . '" style="width: 200px; height: 200px;">
                    </td>
                </tr>
                <tr>
                    <td class="footer-cell">QTY Box: </td>
                    <td class="footer-cell" style="text-align: center;">' . $currentDate . '</td>
                    <td class="footer-cell">Counter: </td>
                </tr>
            </table>';
        }

        // --- 4. สร้าง PDF ด้วย Dompdf ---
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $fullHtml = '
        <style>
            @page { margin: 1cm; } 
            body { font-family: "DejaVu Sans", sans-serif; }
            .label-table { width: 100%; height: 90%; border-collapse: collapse; border: 2px solid green; page-break-after: always; }
            .label-table:last-child { page-break-after: auto; }
            td { border: 1px solid green; text-align: center; vertical-align: middle; padding: 10px; }
            .location-header { font-size: 60px; font-weight: bold; }
            .location-code-text { font-size: 80px; font-weight: bold; height: 120px; }
            .qr-cell { height: 220px; }
            .footer-cell { font-size: 24px; font-weight: bold; text-align: left; padding-left: 20px; width: 33.33%; }
        </style>' . $html;

        $dompdf->loadHtml($fullHtml);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("location-labels-auto.pdf", ["Attachment" => 0]);
    } catch (Exception $e) {
        die('Error loading file: ' . $e->getMessage());
    }
} else {
    die('Error: ไม่พบไฟล์ที่ตำแหน่ง ' . htmlspecialchars($excelFilePath) . ' กรุณาตรวจสอบอีกครั้ง');
}
