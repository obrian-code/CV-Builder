<?php
function renderATS(array $data): string
{
    $name = htmlspecialchars($data['nombre'] ?? '');
    $email = htmlspecialchars($data['email'] ?? '');
    $phone = htmlspecialchars($data['phone'] ?? '');
    $location = htmlspecialchars($data['ubicacion'] ?? '');
    $linkedin = htmlspecialchars($data['linkedin'] ?? '');
    $github = htmlspecialchars($data['github'] ?? '');
    $resumen = htmlspecialchars($data['resumen'] ?? '');
    $experiencia = $data['experiencia'] ?? [];
    $educacion = $data['educacion'] ?? [];
    $habilidades = htmlspecialchars($data['habilidades'] ?? '');

    $contactItems = array_filter([$email, $phone, $location]);
    $links = array_filter([$linkedin, $github]);

    $html = '<div class="ats-cv">';

    $html .= '<header class="ats-header">';
    $html .= '<h1 class="ats-name">' . $name . '</h1>';

    if (!empty($contactItems)) {
        $html .= '<p class="ats-contact">' . implode(' | ', $contactItems) . '</p>';
    }

    if (!empty($links)) {
        $html .= '<p class="ats-links">' . implode(' | ', array_map(fn($l) => '<span class="ats-link">' . $l . '</span>', $links)) . '</p>';
    }

    $html .= '</header>';

    if (!empty($resumen)) {
        $html .= '<section class="ats-section">';
        $html .= '<h2 class="ats-section-title">Resumen Profesional</h2>';
        $html .= '<p class="ats-text">' . nl2br($resumen) . '</p>';
        $html .= '</section>';
    }

    if (!empty($experiencia)) {
        $html .= '<section class="ats-section">';
        $html .= '<h2 class="ats-section-title">Experiencia</h2>';
        foreach ($experiencia as $exp) {
            $empresa = htmlspecialchars($exp['empresa'] ?? '');
            $cargo = htmlspecialchars($exp['cargo'] ?? '');
            $inicio = htmlspecialchars($exp['inicio'] ?? '');
            $fin = htmlspecialchars($exp['fin'] ?? '');
            $descripcion = htmlspecialchars($exp['descripcion'] ?? '');

            $html .= '<div class="ats-entry">';
            $html .= '<div class="ats-entry-header">';
            $html .= '<strong>' . $cargo . '</strong>';
            if ($empresa) {
                $html .= ' - ' . $empresa;
            }
            $html .= '</div>';
            if ($inicio || $fin) {
                $html .= '<div class="ats-date">' . $inicio . ' - ' . ($fin ?: 'Presente') . '</div>';
            }
            if ($descripcion) {
                $html .= '<p class="ats-text">' . nl2br($descripcion) . '</p>';
            }
            $html .= '</div>';
        }
        $html .= '</section>';
    }

    if (!empty($educacion)) {
        $html .= '<section class="ats-section">';
        $html .= '<h2 class="ats-section-title">Educacion</h2>';
        foreach ($educacion as $edu) {
            $institucion = htmlspecialchars($edu['institucion'] ?? '');
            $titulo = htmlspecialchars($edu['titulo'] ?? '');
            $anio = htmlspecialchars($edu['anio'] ?? '');

            $html .= '<div class="ats-entry">';
            $html .= '<div class="ats-entry-header">';
            $html .= '<strong>' . $titulo . '</strong>';
            if ($institucion) {
                $html .= ' - ' . $institucion;
            }
            $html .= '</div>';
            if ($anio) {
                $html .= '<div class="ats-date">' . $anio . '</div>';
            }
            $html .= '</div>';
        }
        $html .= '</section>';
    }

    if (!empty($habilidades)) {
        $html .= '<section class="ats-section">';
        $html .= '<h2 class="ats-section-title">Habilidades</h2>';
        $html .= '<p class="ats-text">' . htmlspecialchars($habilidades) . '</p>';
        $html .= '</section>';
    }

    $html .= '</div>';

    return $html;
}
