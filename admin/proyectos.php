<?php
// ============================================================
// admin/proyectos.php — CRUD de Proyectos
// ============================================================
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$pdo = getDB();
$msg = '';
$editData = null;

// --- ELIMINAR ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare('UPDATE proyectos SET activo = 0 WHERE id = ?')->execute([$id]);
    header('Location: proyectos.php?ok=deleted');
    exit;
}

// --- GUARDAR (crear o editar) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int)($_POST['id'] ?? 0);
    $titulo      = clean($_POST['titulo']      ?? '');
    $descripcion = clean($_POST['descripcion'] ?? '');
    $demo_url    = clean($_POST['demo_url']    ?? '');
    $github_url  = clean($_POST['github_url']  ?? '');
    $orden       = (int)($_POST['orden']       ?? 0);

    if (!$titulo || !$descripcion) {
        $msg = '<div class="alert alert-danger">Título y descripción son obligatorios.</div>';
    } else {
        if ($id > 0) {
            $stmt = $pdo->prepare(
                'UPDATE proyectos SET titulo=?, descripcion=?, demo_url=?, github_url=?, orden=? WHERE id=?'
            );
            $stmt->execute([$titulo, $descripcion, $demo_url, $github_url, $orden, $id]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO proyectos (titulo, descripcion, demo_url, github_url, orden) VALUES (?,?,?,?,?)'
            );
            $stmt->execute([$titulo, $descripcion, $demo_url, $github_url, $orden]);
        }
        header('Location: proyectos.php?ok=saved');
        exit;
    }
}

// --- CARGAR PARA EDITAR ---
if (isset($_GET['edit'])) {
    $editData = $pdo->prepare('SELECT * FROM proyectos WHERE id = ?');
    $editData->execute([(int)$_GET['edit']]);
    $editData = $editData->fetch();
}

$proyectos = $pdo->query('SELECT * FROM proyectos WHERE activo=1 ORDER BY orden')->fetchAll();

// Mensajes de estado
if (isset($_GET['ok'])) {
    $msg = $_GET['ok'] === 'saved'
        ? '<div class="alert alert-success">Proyecto guardado correctamente.</div>'
        : '<div class="alert alert-success">Proyecto eliminado.</div>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proyectos — Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="dash-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-header">
            <h1 class="dash-title">Proyectos</h1>
        </div>
        <?= $msg ?>

        <!-- FORMULARIO -->
        <div class="dash-card mb-4">
            <div class="dash-card-header">
                <span><?= $editData ? 'Editar proyecto' : 'Nuevo proyecto' ?></span>
                <?php if ($editData): ?>
                    <a href="proyectos.php" class="btn btn-sm btn-secondary">Cancelar</a>
                <?php endif; ?>
            </div>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $editData['id'] ?? 0 ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Título *</label>
                        <input type="text" name="titulo" class="form-control" required value="<?= htmlspecialchars($editData['titulo'] ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Orden</label>
                        <input type="number" name="orden" class="form-control" value="<?= $editData['orden'] ?? 0 ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">URL Demo</label>
                        <input type="url" name="demo_url" class="form-control" value="<?= htmlspecialchars($editData['demo_url'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Descripción *</label>
                        <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($editData['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">URL GitHub</label>
                        <input type="url" name="github_url" class="form-control" value="<?= htmlspecialchars($editData['github_url'] ?? '') ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-accent mt-3">
                    <i class="bi bi-save me-1"></i><?= $editData ? 'Actualizar' : 'Guardar' ?>
                </button>
            </form>
        </div>

        <!-- LISTADO -->
        <div class="dash-card">
            <div class="dash-card-header"><span>Proyectos registrados</span></div>
            <table class="table dash-table">
                <thead>
                    <tr><th>Orden</th><th>Título</th><th>Demo</th><th>GitHub</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($proyectos as $p): ?>
                    <tr>
                        <td><?= $p['orden'] ?></td>
                        <td><?= htmlspecialchars($p['titulo']) ?></td>
                        <td><?= $p['demo_url'] ? '<a href="' . htmlspecialchars($p['demo_url']) . '" target="_blank"><i class="bi bi-box-arrow-up-right"></i></a>' : '—' ?></td>
                        <td><?= $p['github_url'] ? '<a href="' . htmlspecialchars($p['github_url']) . '" target="_blank"><i class="bi bi-github"></i></a>' : '—' ?></td>
                        <td>
                            <a href="proyectos.php?edit=<?= $p['id'] ?>" class="btn btn-xs btn-icon"><i class="bi bi-pencil"></i></a>
                            <a href="proyectos.php?delete=<?= $p['id'] ?>" class="btn btn-xs btn-icon btn-danger-icon" onclick="return confirm('¿Eliminar este proyecto?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (!$proyectos): ?>
                    <tr><td colspan="5" class="text-center text-muted">Sin proyectos registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
