# CV Builder ATS

Aplicación web en PHP para crear, guardar y administrar currículums compatibles con sistemas de seguimiento de candidatos (ATS).

## Descripción

`cv-builder` permite a los usuarios completar un formulario con su información profesional, experiencia, educación y habilidades. El proyecto guarda cada CV en una base de datos SQLite, ofrece vista previa y puede generar un PDF con un formato ATS amigable.

## Características

- Formulario de creación y edición de CV
- Gestión de historial de CVs guardados
- Vista previa inmediata del CV
- Generación de PDF usando `dompdf`
- Almacenamiento de datos en SQLite
- Plantilla ATS optimizada para lectura automática
- Contenedor Docker listo para ejecutar

## Estructura del proyecto

- `Dockerfile` - imagen PHP/Apache con extensiones necesarias y Composer
- `docker-compose.yml` - define servicio web para el proyecto
- `src/` - código PHP de la aplicación
- `src/database.php` - manejo de SQLite
- `src/index.php` - formulario principal y listado de CVs
- `src/save-cv.php` - guarda o actualiza CVs
- `src/generate-pdf.php` - exporta CV a PDF
- `src/preview.php`, `src/edit.php`, `src/delete.php` - funciones adicionales de administración
- `src/templates/ats.php` - plantilla de CV ATS
- `assets/` - estilos y recursos estáticos
- `src/storage/` - almacenamiento de la base de datos SQLite

## Requisitos

- Docker
- Docker Compose

## Uso con Docker

1. Abre una terminal en la carpeta del proyecto.
2. Construye y levanta el contenedor:

```bash
docker compose up --build
```

3. Abre el navegador en:

```
http://localhost:8080
```

## Uso local sin Docker

1. Instala PHP 8.3 o superior con soporte para `pdo_sqlite`, `zip` y `gd`.
2. Instala Composer si aún no lo tienes.
3. Ejecuta Composer en el directorio del proyecto si no existe `vendor/`:

```bash
composer install
```

4. Asegúrate de que la carpeta `src/storage` existe y tiene permisos de escritura.
5. Sirve la carpeta `src/` desde un servidor web o PHP integrado.

## Generar PDF

La aplicación genera PDF mediante `src/generate-pdf.php` y la librería `dompdf`. La imagen Docker ya instala `dompdf` automáticamente.

## Notas

- En Docker, el volumen `./src:/var/www/html` permite editar el código localmente y ver los cambios inmediatamente.
- El archivo SQLite se guarda en `src/storage/database.sqlite`.

## Licencia

Proyecto para demostración. Ajusta la licencia según tus necesidades.
