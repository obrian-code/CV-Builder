<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/templates/ats.php';

$items = Database::getAll();

$data = $_GET['data'] ?? null;

if ($data) {
    try {
        $data = json_decode(base64_decode($data), true, 512, JSON_THROW_ON_ERROR);
    } catch (Exception $e) {
        $data = null;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Builder ATS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container py-4">
    <div class="row">
        <div class="col-12 app-header">
            <h1>CV Builder ATS</h1>
            <p class="lead">Crea curriculums profesionales compatibles con ATS</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-4" id="form-title">Nuevo CV</h4>
                    <form id="cv-form" method="POST" action="save-cv.php">
                        <input type="hidden" name="id" id="cv-id" value="">

                        <h5 class="section-label">Informacion Personal</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <input type="text" class="form-control" name="nombre" id="field-nombre" placeholder="Nombre completo" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" id="field-email" placeholder="Email">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="phone" id="field-phone" placeholder="Telefono">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="ubicacion" id="field-ubicacion" placeholder="Ubicacion">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="linkedin" id="field-linkedin" placeholder="LinkedIn URL">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="github" id="field-github" placeholder="GitHub URL">
                            </div>
                            <div class="col-12">
                                <textarea class="form-control" name="resumen" id="field-resumen" rows="3" placeholder="Resumen profesional"></textarea>
                            </div>
                        </div>

                        <h5 class="section-label">Experiencia</h5>
                        <div id="experiencia-container">
                            <div class="experiencia-entry border rounded p-3 mb-3">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="experiencia[0][empresa]" placeholder="Empresa">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="experiencia[0][cargo]" placeholder="Cargo">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="experiencia[0][inicio]" placeholder="Fecha inicio">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="experiencia[0][fin]" placeholder="Fecha fin">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-entry" style="display:none;">Eliminar</button>
                                    </div>
                                    <div class="col-12">
                                        <textarea class="form-control" name="experiencia[0][descripcion]" rows="2" placeholder="Descripcion"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-add mb-3" id="add-experiencia">+ Agregar experiencia</button>

                        <h5 class="section-label">Educacion</h5>
                        <div id="educacion-container">
                            <div class="educacion-entry border rounded p-3 mb-3">
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="educacion[0][institucion]" placeholder="Institucion">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="educacion[0][titulo]" placeholder="Titulo">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center">
                                        <input type="text" class="form-control" name="educacion[0][anio]" placeholder="Ano">
                                    </div>
                                    <div class="col-12">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-entry" style="display:none;">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-add mb-3" id="add-educacion">+ Agregar educacion</button>

                        <h5 class="section-label">Habilidades</h5>
                        <div class="mb-3">
                            <textarea class="form-control" name="habilidades" id="field-habilidades" rows="2" placeholder="Ej: PHP, JavaScript, SQL, Python"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark" id="btn-submit">Guardar CV</button>
                            <button type="button" class="btn btn-outline-secondary" id="btn-preview">Vista Previa</button>
                            <button type="button" class="btn btn-outline-secondary" id="btn-cancel" style="display:none;">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Historial</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($items)): ?>
                        <div class="p-4 text-muted text-center">No hay CVs guardados.</div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($items as $item): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($item['nombre']) ?></strong>
                                        <br>
                                        <small class="text-muted"><?= $item['created_at'] ?></small>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <a href="edit.php?id=<?= $item['id'] ?>" class="btn btn-outline-secondary">Editar</a>
                                        <a href="preview.php?id=<?= $item['id'] ?>" class="btn btn-outline-secondary">Ver</a>
                                        <a href="delete.php?id=<?= $item['id'] ?>" class="btn btn-outline-danger" onclick="return confirm('Eliminar este CV?')">Eliminar</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Vista Previa</h5>
                </div>
                <div class="card-body p-4 preview-container" id="preview-container">
                    <div class="default-preview">
                        Completa el formulario para ver la vista previa.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let expIndex = 1;
let eduIndex = 1;

document.getElementById('add-experiencia').addEventListener('click', function() {
    const container = document.getElementById('experiencia-container');
    const entries = container.querySelectorAll('.experiencia-entry');
    const idx = entries.length;
    const div = document.createElement('div');
    div.className = 'experiencia-entry border rounded p-3 mb-3';
    div.innerHTML = `
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" class="form-control" name="experiencia[${idx}][empresa]" placeholder="Empresa">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" name="experiencia[${idx}][cargo]" placeholder="Cargo">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="experiencia[${idx}][inicio]" placeholder="Fecha inicio">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="experiencia[${idx}][fin]" placeholder="Fecha fin">
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger btn-sm remove-entry">Eliminar</button>
            </div>
            <div class="col-12">
                <textarea class="form-control" name="experiencia[${idx}][descripcion]" rows="2" placeholder="Descripcion"></textarea>
            </div>
        </div>
    `;
    container.appendChild(div);
    attachRemoveHandlers();
});

document.getElementById('add-educacion').addEventListener('click', function() {
    const container = document.getElementById('educacion-container');
    const entries = container.querySelectorAll('.educacion-entry');
    const idx = entries.length;
    const div = document.createElement('div');
    div.className = 'educacion-entry border rounded p-3 mb-3';
    div.innerHTML = `
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" class="form-control" name="educacion[${idx}][institucion]" placeholder="Institucion">
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="educacion[${idx}][titulo]" placeholder="Titulo">
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="educacion[${idx}][anio]" placeholder="Ano">
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-outline-danger btn-sm remove-entry">Eliminar</button>
            </div>
        </div>
    `;
    container.appendChild(div);
    attachRemoveHandlers();
});

function attachRemoveHandlers() {
    document.querySelectorAll('.remove-entry').forEach(btn => {
        btn.style.display = 'inline-block';
        btn.onclick = function() {
            this.closest('.experiencia-entry, .educacion-entry').remove();
        };
    });
}

function getFormData() {
    const form = document.getElementById('cv-form');
    const fd = new FormData(form);

    const data = {
        nombre: fd.get('nombre') || '',
        email: fd.get('email') || '',
        phone: fd.get('phone') || '',
        ubicacion: fd.get('ubicacion') || '',
        linkedin: fd.get('linkedin') || '',
        github: fd.get('github') || '',
        resumen: fd.get('resumen') || '',
        habilidades: fd.get('habilidades') || '',
        experiencia: [],
        educacion: []
    };

    const expEntries = document.querySelectorAll('.experiencia-entry');
    expEntries.forEach(entry => {
        const inputs = entry.querySelectorAll('input, textarea');
        const exp = {};
        inputs.forEach(inp => {
            const name = inp.name.match(/\[([a-z]+)\]$/);
            if (name) {
                exp[name[1]] = inp.value;
            }
        });
        if (exp.empresa || exp.cargo) {
            data.experiencia.push(exp);
        }
    });

    const eduEntries = document.querySelectorAll('.educacion-entry');
    eduEntries.forEach(entry => {
        const inputs = entry.querySelectorAll('input');
        const edu = {};
        inputs.forEach(inp => {
            const name = inp.name.match(/\[([a-z]+)\]$/);
            if (name) {
                edu[name[1]] = inp.value;
            }
        });
        if (edu.institucion || edu.titulo) {
            data.educacion.push(edu);
        }
    });

    return data;
}

function updatePreview() {
    const data = getFormData();
    if (!data.nombre) {
        document.getElementById('preview-container').innerHTML = '<div class="default-preview">Completa el formulario para ver la vista previa.</div>';
        return;
    }

    const encoded = btoa(unescape(encodeURIComponent(JSON.stringify(data))));

    fetch('preview.php?ajax=1&data=' + encodeURIComponent(encoded))
        .then(r => r.text())
        .then(html => {
            document.getElementById('preview-container').innerHTML = html;
        })
        .catch(() => {});
}

document.querySelectorAll('#cv-form input, #cv-form textarea').forEach(el => {
    el.addEventListener('input', updatePreview);
    el.addEventListener('change', updatePreview);
});

document.getElementById('btn-preview').addEventListener('click', function(e) {
    e.preventDefault();
    updatePreview();
});

document.getElementById('btn-cancel').addEventListener('click', function() {
    window.location.href = 'index.php';
});

<?php if ($data): ?>
document.addEventListener('DOMContentLoaded', function() {
    const d = <?= json_encode($data) ?>;

    if (d._id) {
        document.getElementById('cv-id').value = d._id;
        document.getElementById('form-title').textContent = 'Editar CV';
        document.getElementById('btn-submit').textContent = 'Actualizar CV';
        document.getElementById('btn-cancel').style.display = 'inline-block';
    }

    const setVal = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.value = val || '';
    };

    setVal('field-nombre', d.nombre);
    setVal('field-email', d.email);
    setVal('field-phone', d.phone);
    setVal('field-ubicacion', d.ubicacion);
    setVal('field-linkedin', d.linkedin);
    setVal('field-github', d.github);
    setVal('field-resumen', d.resumen);
    setVal('field-habilidades', d.habilidades);

    if (d.experiencia && d.experiencia.length > 0) {
        const container = document.getElementById('experiencia-container');
        container.innerHTML = '';
        d.experiencia.forEach((exp, i) => {
            const div = document.createElement('div');
            div.className = 'experiencia-entry border rounded p-3 mb-3';
            div.innerHTML = `
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="experiencia[${i}][empresa]" value="${esc(exp.empresa || '')}" placeholder="Empresa">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="experiencia[${i}][cargo]" value="${esc(exp.cargo || '')}" placeholder="Cargo">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="experiencia[${i}][inicio]" value="${esc(exp.inicio || '')}" placeholder="Fecha inicio">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="experiencia[${i}][fin]" value="${esc(exp.fin || '')}" placeholder="Fecha fin">
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-entry">Eliminar</button>
                    </div>
                    <div class="col-12">
                        <textarea class="form-control" name="experiencia[${i}][descripcion]" rows="2" placeholder="Descripcion">${esc(exp.descripcion || '')}</textarea>
                    </div>
                </div>
            `;
            container.appendChild(div);
        });
        attachRemoveHandlers();
    }

    if (d.educacion && d.educacion.length > 0) {
        const container = document.getElementById('educacion-container');
        container.innerHTML = '';
        d.educacion.forEach((edu, i) => {
            const div = document.createElement('div');
            div.className = 'educacion-entry border rounded p-3 mb-3';
            div.innerHTML = `
                <div class="row g-2">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="educacion[${i}][institucion]" value="${esc(edu.institucion || '')}" placeholder="Institucion">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="educacion[${i}][titulo]" value="${esc(edu.titulo || '')}" placeholder="Titulo">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="educacion[${i}][anio]" value="${esc(edu.anio || '')}" placeholder="Ano">
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-entry">Eliminar</button>
                    </div>
                </div>
            `;
            container.appendChild(div);
        });
        attachRemoveHandlers();
    }

    setTimeout(updatePreview, 100);

    function esc(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
});
<?php endif; ?>
</script>
</body>
</html>
