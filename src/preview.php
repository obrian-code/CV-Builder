<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/templates/ats.php';

$ajax = isset($_GET['ajax']);
$data = null;

if (isset($_GET['id'])) {
    $cv = Database::getById((int) $_GET['id']);
    if ($cv) {
        $data = $cv['contenido'];
    }
} elseif (isset($_GET['data'])) {
    $json = base64_decode($_GET['data']);
    $data = json_decode($json, true);
}

if (!$data) {
    if ($ajax) {
        echo '<div class="text-muted text-center py-5">CV no encontrado.</div>';
        exit;
    }
    header('Location: index.php');
    exit;
}

if ($ajax) {
    echo renderATS($data);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa - CV Builder ATS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="index.php" class="btn btn-outline-secondary">&larr; Volver</a>
        <div>
            <button class="btn btn-dark" onclick="window.print()">Imprimir</button>
            <a href="generate-pdf.php?id=<?= (int) ($_GET['id'] ?? 0) ?>&data=<?= urlencode($_GET['data'] ?? '') ?>" class="btn btn-dark">Descargar PDF</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-5">
            <?= renderATS($data) ?>
        </div>
    </div>
</div>

</body>
</html>
