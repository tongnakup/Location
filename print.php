<?php

set_time_limit(300);
ini_set('memory_limit', '256M');
// เรียกใช้ Autoloader ของ Composer
require 'vendor/autoload.php';

// Import คลาสที่จำเป็น
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\RoundBlockSizeMode;

// --- 1. รับข้อมูลจากฟอร์ม ---
$locationCode = $_POST['location_code'] ?? 'N/A';
$quantity = (int)($_POST['quantity'] ?? 1);

// --- 2. สร้าง QR Code ---
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


// --- 3. สร้าง HTML สำหรับแต่ละป้าย ---
$html = '';

$html .= '
<style>
    @page { margin: 1cm; } body { font-family: "DejaVu Sans", sans-serif; }
    .label-table { width: 100%; height: 90%; border-collapse: collapse; border: 2px solid green; page-break-after: always; }
    .label-table:last-child { page-break-after: auto; }
    td, th { border: 1px solid green; text-align: center; vertical-align: middle; }
    .location-header { font-size: 80px; font-weight: bold; padding: 20px; }
    .qr-cell { height: 400px; padding: 20px; }
    .footer-cell { font-size: 24px; font-weight: bold; padding: 15px; text-align: left; padding-left: 20px;}
</style>
';


for ($i = 1; $i <= $quantity; $i++) {
    // *** เปลี่ยนแปลง: กลับมาใช้รูปแบบวันที่ d/m/Y ตามที่ต้องการ ***
    $currentDate = (new DateTime("now", new DateTimeZone('Asia/Bangkok')))->format('d/m/Y');

    $html .= '
    <table class="label-table">
        <tr><td colspan="3" class="location-header">Location</td></tr>
        <tr>
            <td colspan="3" class="qr-cell">
                <img src="' . $qrCodeDataUri . '" style="width: 250px; height: 250px;">
                <div style="font-size: 28px; font-weight: bold; margin-top: 10px;">' . htmlspecialchars($locationCode) . '</div>
            </td>
        </tr>
        <tr>
            <td class="footer-cell" style="width: 33%;">QTY Box: </td>
            <td class="footer-cell" style="width: 33%; text-align: center;">' . $currentDate . '</td>
            <td class="footer-cell" style="width: 34%;">Counter: </td>
        </tr>
    </table>
    ';
}


// --- 4. สร้าง PDF ด้วย Dompdf ---
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("location-labels.pdf", ["Attachment" => 0]);
