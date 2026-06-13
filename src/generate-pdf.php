<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/templates/ats.php';

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$data = null;

if (isset($_GET['id']) && (int) $_GET['id'] > 0) {
    $cv = Database::getById((int) $_GET['id']);
    if ($cv) {
        $data = $cv['contenido'];
    }
} elseif (isset($_GET['data']) && !empty($_GET['data'])) {
    $json = base64_decode($_GET['data']);
    $data = json_decode($json, true);
}

if (!$data) {
    http_response_code(404);
    echo 'CV no encontrado.';
    exit;
}

$html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 30px;
        }
        .ats-cv {
            max-width: 100%;
        }
        .ats-header {
            text-align: left;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
        }
        .ats-name {
            font-size: 22pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .ats-contact {
            font-size: 10pt;
            color: #333;
            margin: 3px 0;
        }
        .ats-links {
            font-size: 10pt;
            margin: 3px 0;
        }
        .ats-link {
            color: #000;
        }
        .ats-section {
            margin-bottom: 18px;
        }
        .ats-section-title {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            margin-bottom: 10px;
        }
        .ats-entry {
            margin-bottom: 12px;
        }
        .ats-entry-header {
            font-size: 11pt;
        }
        .ats-date {
            font-size: 10pt;
            color: #555;
            margin: 2px 0;
        }
        .ats-text {
            font-size: 10pt;
            margin: 4px 0 0 0;
            color: #333;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    ' . renderATS($data) . '
</body>
</html>';

$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = 'CV_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $data['nombre'] ?? 'cv') . '.pdf';

$dompdf->stream($filename, ['Attachment' => true]);
exit;
