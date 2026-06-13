<?php
require_once __DIR__ . '/database.php';

$id = (int) ($_GET['id'] ?? 0);
$cv = Database::getById($id);

if (!$cv) {
    header('Location: index.php');
    exit;
}

$data = $cv['contenido'];
$data['_id'] = $id;
$encoded = base64_encode(json_encode($data));
header('Location: index.php?data=' . urlencode($encoded));
exit;
