<?php
// ============================================================
// admin/biografia.php — Editar Biografía
// ============================================================
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$pdo = getDB();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campos = ['nombre','titulo','descripcion','email','telefono','ciudad',
               'github_url','linkedin_url','instagram_url','cv_url'];
    $valores = [];
    foreach ($campos as $c) {
        $valores[$c] = clean($_POST[$c] ?? '');
    }

    // Manejo de foto
    $fotoPath = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];
        if (!in_array($ext, $allowed)) {
            $msg = '<div class="alert alert-danger">Solo se permiten imágenes JPG, PNG, WEBP o GIF.</div>';
        } else {
            $uploadDir = '../assets/img/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = 'perfil_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadDir . $filename)) {
                $fotoPath = 'assets/img/' . $filename;
            }
        }
    }

    if (empty($msg)) {
        // Verificar si ya existe un registro
        $existe = $pdo->query('SELECT COUNT(*) FROM biografia')->fetchColumn();
        if ($existe) {
            if ($fotoPath) {
                $sets = implode('=?, ', $campos) . '=?, foto=?';
                $stmt = $pdo->prepare("UPDATE biografia SET $sets");
                $stmt->execute(array_merge(array_values($valores), [$fotoPath]));
            } else {
                $sets = implode('=?, ', $campos) . '=?';
                $stmt = $pdo->prepare("UPDATE biografia SET $sets");
                $stmt->execute(array_values($valores));
            }
        } else {
            $campos[] = 'foto';
            $valores[] = $fotoPath;
            $keys = implode(',', $campos);
            $placeholders = implode(',', array_fill(0, count($campos), '?'));
            $stmt = $pdo->prepare("INSERT INTO biografia ($keys) VALUES ($placeholders)");
            $stmt->execute(array_values($valores));
        }
        $msg = '<div class="alert alert-success">Biografía actualizada correctamente.</div>';
    }
}

$bio = $pdo->query('SELECT * FROM biografia LIMIT 1')->fetch();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><title>Biografía — Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
<style>
.foto-upload-area { display:flex; flex-direction:column; align-items:flex-start; gap:8px; }
.foto-preview { width:120px; height:120px; border-radius:50%; object-fit:cover; border:3px solid var(--border2,#3d3a6b); }
.foto-placeholder { width:120px; height:120px; border-radius:50%; background:#1a1a26; border:3px solid #3d3a6b; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#6b6888; font-size:2.5rem; }
.foto-placeholder span { font-size:12px; margin-top:4px; }
.btn-outline-accent { background:transparent; color:#9b97b8; border:1px solid #3d3a6b; padding:6px 14px; border-radius:7px; font-size:13px; transition:all .2s; }
.btn-outline-accent:hover { border-color:#7c5cfc; color:#a78bfa; }
</style>
</head>
<body>
<div class="dash-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-header"><h1 class="dash-title">Editar Biografía</h1></div>
        <?= $msg ?>
        <div class="dash-card">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($bio['nombre'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Título profesional</label>
                        <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($bio['titulo'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="5"><?= htmlspecialchars($bio['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($bio['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($bio['telefono'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ciudad</label>
                        <input type="text" name="ciudad" class="form-control" value="<?= htmlspecialchars($bio['ciudad'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-github me-1"></i>GitHub URL</label>
                        <input type="url" name="github_url" class="form-control" value="<?= htmlspecialchars($bio['github_url'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-linkedin me-1"></i>LinkedIn URL</label>
                        <input type="url" name="linkedin_url" class="form-control" value="<?= htmlspecialchars($bio['linkedin_url'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-instagram me-1"></i>Instagram URL</label>
                        <input type="url" name="instagram_url" class="form-control" value="<?= htmlspecialchars($bio['instagram_url'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-file-earmark-person me-1"></i>CV (URL o ruta)</label>
                        <input type="text" name="cv_url" class="form-control" value="<?= htmlspecialchars($bio['cv_url'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-12 mt-3">
                        <label class="form-label"><i class="bi bi-camera me-1"></i>Foto de perfil</label>
                        <div class="foto-upload-area">
                            <?php if ($bio && $bio['foto']): ?>
                                <img src="../<?= htmlspecialchars($bio['foto']) ?>" class="foto-preview" id="fotoPreview" alt="Foto actual">
                            <?php else: ?>
                                <div class="foto-placeholder" id="fotoPlaceholder">
                                    <i class="bi bi-person-fill"></i>
                                    <span>Sin foto</span>
                                </div>
                                <img src="" class="foto-preview d-none" id="fotoPreview" alt="Preview">
                            <?php endif; ?>
                            <div class="mt-2">
                                <label for="fotoInput" class="btn btn-outline-accent btn-sm" style="cursor:pointer">
                                    <i class="bi bi-upload me-1"></i>Seleccionar foto
                                </label>
                                <input type="file" name="foto" id="fotoInput" accept="image/*" class="d-none"
                                    onchange="previewFoto(this)">
                                <small class="text-muted ms-2">JPG, PNG, WEBP — máx. 2MB</small>
                            </div>
                        </div>
                    </div>
                <button type="submit" class="btn btn-accent mt-4"><i class="bi bi-save me-2"></i>Guardar Cambios</button>
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('fotoPreview');
            const placeholder = document.getElementById('fotoPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
            </form>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>