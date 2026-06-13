<?php
require_once __DIR__ . '/database.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    Database::delete($id);
}

header('Location: index.php');
exit;
