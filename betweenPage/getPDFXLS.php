<?php
// basixs
require '../basic/basicPage.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

// 2. Receive the POST data
$text = $_POST['text'] ?? 'Default text';
$imgLocation = $_POST['img_location'] ?? 'd';


$imageSrc = '';
// beveiliging 
if (!empty($imgLocation)) {
    // prevents ../ traversal
    $baseDir = __DIR__;
    $relative = ltrim($imgLocation, './');
    $absolutePath = realpath($baseDir . DIRECTORY_SEPARATOR . $relative);

    if (
        $absolutePath !== false &&
        str_starts_with($absolutePath, realpath($baseDir) . DIRECTORY_SEPARATOR) &&
        is_file($absolutePath)
    ) {
        $imageData = file_get_contents($absolutePath);
        $mimeType  = mime_content_type($absolutePath);

        // checkt of het wel echt een img is)
        if (str_starts_with($mimeType, 'image/')) {
            $base64   = base64_encode($imageData);
            $imageSrc = "data:$mimeType;base64,$base64";
        }
    }
}

// generate pdf
$options = new Options();
$options->setAllowedProtocols(['data://']);
$dompdf = new Dompdf($options);

$html = '
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .container { padding: 20px; }
        .image-box { text-align: center; margin-bottom: 20px; }
        .text-box { font-size: 14px; line-height: 1.6; }
    </style>
    </head>
    <body>
    <div class="container">
        ' . ($imageSrc ? '<div class="image-box"><img src="' . $imageSrc
    . '" style="max-width: 100%; height: auto;"></div>' : '') . '
        <div class="text-box">' . nl2br(htmlspecialchars($text)) . nl2br(htmlspecialchars($imgLocation)) . '</div>
    </div>
    </body>
    </html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Generate XLS
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data');
$sheet->setCellValue('A1', 'Text Content');
$sheet->setCellValue('A2', $text);

$writer = new Xls($spreadsheet);

// temporary location
$tempDir = sys_get_temp_dir();
$pdfFile = $tempDir . '/output_' . time() . '.pdf';
$xlsFile = $tempDir . '/output_' . time() . '.xls';

file_put_contents($pdfFile, $dompdf->output());
$writer->save($xlsFile);

// Prepare for download in zip
$zipFile = $tempDir . '/output_' . time() . '.zip';
$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
    $zip->addFile($pdfFile, 'document.pdf');
    $zip->addFile($xlsFile, 'data.xls');
    $zip->close();
}

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="files.zip"');
header('Content-Length: ' . filesize($zipFile));
readfile($zipFile);

// Clean up temporary files
unlink($pdfFile);
unlink($xlsFile);
unlink($zipFile);
