<?php
// ============================================================
// admin/habilidades.php — CRUD de Habilidades
// ============================================================
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$pdo = getDB();
$msg = '';
$editData = null;

if (isset($_GET['delete'])) {
    $pdo->prepare('UPDATE habilidades SET activo=0 WHERE id=?')->execute([(int)$_GET['delete']]);
    header('Location: habilidades.php?ok=deleted'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id']    ?? 0);
    $nombre = clean($_POST['nombre'] ?? '');
    $icono  = clean($_POST['icono']  ?? '');
    $orden  = (int)($_POST['orden']  ?? 0);

    if (!$nombre) {
        $msg = '<div class="alert alert-danger">El nombre es obligatorio.</div>';
    } else {
        if ($id > 0) {
            $pdo->prepare('UPDATE habilidades SET nombre=?,icono=?,orden=? WHERE id=?')
                ->execute([$nombre, $icono, $orden, $id]);
        } else {
            $pdo->prepare('INSERT INTO habilidades (nombre,icono,orden) VALUES (?,?,?)')
                ->execute([$nombre, $icono, $orden]);
        }
        header('Location: habilidades.php?ok=saved'); exit;
    }
}

if (isset($_GET['edit'])) {
    $s = $pdo->prepare('SELECT * FROM habilidades WHERE id=?');
    $s->execute([(int)$_GET['edit']]);
    $editData = $s->fetch();
}

$habilidades = $pdo->query('SELECT * FROM habilidades WHERE activo=1 ORDER BY orden')->fetchAll();
if (isset($_GET['ok'])) $msg = '<div class="alert alert-success">Operación realizada.</div>';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><title>Habilidades — Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="dash-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-header"><h1 class="dash-title">Habilidades</h1></div>
        <?= $msg ?>
        <div class="dash-card mb-4">
            <div class="dash-card-header">
                <span><?= $editData ? 'Editar habilidad' : 'Nueva habilidad' ?></span>
                <?php if ($editData): ?><a href="habilidades.php" class="btn btn-sm btn-secondary">Cancelar</a><?php endif; ?>
            </div>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $editData['id'] ?? 0 ?>">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($editData['nombre'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ícono (emoji)</label>
                        <input type="text" name="icono" class="form-control" value="<?= htmlspecialchars($editData['icono'] ?? '') ?>" placeholder="🌐">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Orden</label>
                        <input type="number" name="orden" class="form-control" value="<?= $editData['orden'] ?? 0 ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-accent mt-3"><i class="bi bi-save me-1"></i><?= $editData ? 'Actualizar' : 'Guardar' ?></button>
            </form>
        </div>
        <div class="dash-card">
            <div class="dash-card-header"><span>Habilidades registradas</span></div>
            <table class="table dash-table">
                <thead><tr><th>Orden</th><th>Ícono</th><th>Nombre</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($habilidades as $h): ?>
                    <tr>
                        <td><?= $h['orden'] ?></td>
                        <td style="font-size:1.4rem"><?= htmlspecialchars($h['icono']) ?></td>
                        <td><?= htmlspecialchars($h['nombre']) ?></td>
                        <td>
                            <a href="habilidades.php?edit=<?= $h['id'] ?>" class="btn btn-xs btn-icon"><i class="bi bi-pencil"></i></a>
                            <a href="habilidades.php?delete=<?= $h['id'] ?>" class="btn btn-xs btn-icon btn-danger-icon" onclick="return confirm('¿Eliminar?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
