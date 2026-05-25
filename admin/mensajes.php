<?php

// admin/mensajes.php — Ver mensajes de contacto

require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$pdo = getDB();

if (isset($_GET['delete'])) {
    $pdo->prepare('DELETE FROM mensajes WHERE id=?')->execute([(int)$_GET['delete']]);
    header('Location: mensajes.php'); exit;
}

// Marcar como leído al ver
if (isset($_GET['ver'])) {
    $pdo->prepare('UPDATE mensajes SET leido=1 WHERE id=?')->execute([(int)$_GET['ver']]);
}

$mensajes = $pdo->query('SELECT * FROM mensajes ORDER BY creado_en DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><title>Mensajes — Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="dash-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="dash-main">
        <div class="dash-header"><h1 class="dash-title">Mensajes de Contacto</h1></div>
        <div class="dash-card">
            <table class="table dash-table">
                <thead><tr><th>Estado</th><th>Nombre</th><th>Email</th><th>Asunto</th><th>Fecha</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($mensajes as $m): ?>
                    <tr class="<?= !$m['leido'] ? 'fw-bold' : '' ?>">
                        <td><?= !$m['leido'] ? '<span class="badge bg-accent">Nuevo</span>' : '<span class="badge bg-secondary">Leído</span>' ?></td>
                        <td><?= htmlspecialchars($m['nombre']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a></td>
                        <td><?= htmlspecialchars($m['asunto']) ?></td>
                        <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($m['creado_en'])) ?></td>
                        <td>
                            <button class="btn btn-xs btn-icon" data-bs-toggle="modal" data-bs-target="#msgModal"
                                data-nombre="<?= htmlspecialchars($m['nombre']) ?>"
                                data-email="<?= htmlspecialchars($m['email']) ?>"
                                data-asunto="<?= htmlspecialchars($m['asunto']) ?>"
                                data-mensaje="<?= htmlspecialchars($m['mensaje']) ?>"
                                onclick="location.href='mensajes.php?ver=<?= $m['id'] ?>'">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="mensajes.php?delete=<?= $m['id'] ?>" class="btn btn-xs btn-icon btn-danger-icon"
                               onclick="return confirm('¿Eliminar mensaje?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (!$mensajes): ?>
                    <tr><td colspan="6" class="text-center text-muted">Sin mensajes.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Modal ver mensaje -->
<div class="modal fade" id="msgModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="msgAsunto"></h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted-light small mb-3" id="msgMeta"></p>
                <div id="msgCuerpo" class="p-3 rounded" style="background:rgba(255,255,255,.05);white-space:pre-wrap"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('msgModal').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    if (!btn) return;
    document.getElementById('msgAsunto').textContent  = btn.dataset.asunto;
    document.getElementById('msgMeta').textContent    = 'De: ' + btn.dataset.nombre + ' <' + btn.dataset.email + '>';
    document.getElementById('msgCuerpo').textContent  = btn.dataset.mensaje;
});
</script>
</body>
</html>
