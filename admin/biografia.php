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

    // Verificar si ya existe un registro
    $existe = $pdo->query('SELECT COUNT(*) FROM biografia')->fetchColumn();
    if ($existe) {
        $sets = implode('=?, ', $campos) . '=?';
        $stmt = $pdo->prepare("UPDATE biografia SET $sets");
        $stmt->execute(array_values($valores));
    } else {
        $keys = implode(',', $campos);
        $placeholders = implode(',', array_fill(0, count($campos), '?'));
        $stmt = $pdo->prepare("INSERT INTO biografia ($keys) VALUES ($placeholders)");
        $stmt->execute(array_values($valores));
    }
    $msg = '<div class="alert alert-success">Biografía actualizada correctamente.</div>';
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
</head>
<body>
<div class="dash-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-header"><h1 class="dash-title">Editar Biografía</h1></div>
        <?= $msg ?>
        <div class="dash-card">
            <form method="POST">
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
                <button type="submit" class="btn btn-accent mt-4"><i class="bi bi-save me-2"></i>Guardar Cambios</button>
            </form>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
