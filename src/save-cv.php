<?php
require_once __DIR__ . '/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');

if (empty($nombre)) {
    header('Location: index.php');
    exit;
}

$contenido = [
    'nombre' => $nombre,
    'email' => $_POST['email'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'ubicacion' => $_POST['ubicacion'] ?? '',
    'linkedin' => $_POST['linkedin'] ?? '',
    'github' => $_POST['github'] ?? '',
    'resumen' => $_POST['resumen'] ?? '',
    'experiencia' => [],
    'educacion' => [],
    'habilidades' => $_POST['habilidades'] ?? '',
];

if (isset($_POST['experiencia']) && is_array($_POST['experiencia'])) {
    foreach ($_POST['experiencia'] as $exp) {
        if (!empty(trim($exp['empresa'] ?? '')) || !empty(trim($exp['cargo'] ?? ''))) {
            $contenido['experiencia'][] = [
                'empresa' => $exp['empresa'] ?? '',
                'cargo' => $exp['cargo'] ?? '',
                'inicio' => $exp['inicio'] ?? '',
                'fin' => $exp['fin'] ?? '',
                'descripcion' => $exp['descripcion'] ?? '',
            ];
        }
    }
}

if (isset($_POST['educacion']) && is_array($_POST['educacion'])) {
    foreach ($_POST['educacion'] as $edu) {
        if (!empty(trim($edu['institucion'] ?? '')) || !empty(trim($edu['titulo'] ?? ''))) {
            $contenido['educacion'][] = [
                'institucion' => $edu['institucion'] ?? '',
                'titulo' => $edu['titulo'] ?? '',
                'anio' => $edu['anio'] ?? '',
            ];
        }
    }
}

if ($id > 0) {
    Database::update($id, $nombre, $contenido);
} else {
    Database::save($nombre, $contenido);
}

header('Location: index.php');
exit;
