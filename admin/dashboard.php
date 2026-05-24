<?php
// ============================================================
// admin/dashboard.php — Panel de administración
// ============================================================
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$pdo         = getDB();
$bio         = $pdo->query('SELECT * FROM biografia LIMIT 1')->fetch();
$totalProyectos  = $pdo->query('SELECT COUNT(*) FROM proyectos WHERE activo=1')->fetchColumn();
$totalHabilidades = $pdo->query('SELECT COUNT(*) FROM habilidades WHERE activo=1')->fetchColumn();
$totalTecnologias = $pdo->query('SELECT COUNT(*) FROM tecnologias WHERE activo=1')->fetchColumn();
$totalMensajes   = $pdo->query('SELECT COUNT(*) FROM mensajes WHERE leido=0')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="dash-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">VS</div>
            <div>
                <div class="sidebar-name"><?= htmlspecialchars($_SESSION['admin_name']) ?></div>
                <div class="sidebar-role">Administrador</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php"          class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
                <i class="bi bi-grid"></i> Dashboard
            </a>
            <a href="biografia.php"          class="<?= basename($_SERVER['PHP_SELF']) === 'biografia.php' ? 'active' : '' ?>">
                <i class="bi bi-person"></i> Biografía
            </a>
            <a href="habilidades.php"        class="<?= basename($_SERVER['PHP_SELF']) === 'habilidades.php' ? 'active' : '' ?>">
                <i class="bi bi-tools"></i> Habilidades
            </a>
            <a href="tecnologias.php"        class="<?= basename($_SERVER['PHP_SELF']) === 'tecnologias.php' ? 'active' : '' ?>">
                <i class="bi bi-cpu"></i> Tecnologías
            </a>
            <a href="proyectos.php"          class="<?= basename($_SERVER['PHP_SELF']) === 'proyectos.php' ? 'active' : '' ?>">
                <i class="bi bi-folder"></i> Proyectos
            </a>
            <a href="mensajes.php"           class="<?= basename($_SERVER['PHP_SELF']) === 'mensajes.php' ? 'active' : '' ?>">
                <i class="bi bi-envelope"></i> Mensajes
                <?php if ($totalMensajes > 0): ?>
                    <span class="badge-count"><?= $totalMensajes ?></span>
                <?php endif; ?>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="../index.php" target="_blank"><i class="bi bi-eye me-2"></i>Ver sitio</a>
            <a href="../api.php?action=logout"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="dash-main">
        <div class="dash-header">
            <h1 class="dash-title">Dashboard</h1>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-folder"></i></div>
                    <div class="stat-value"><?= $totalProyectos ?></div>
                    <div class="stat-label">Proyectos</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-tools"></i></div>
                    <div class="stat-value"><?= $totalHabilidades ?></div>
                    <div class="stat-label">Habilidades</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-cpu"></i></div>
                    <div class="stat-value"><?= $totalTecnologias ?></div>
                    <div class="stat-label">Tecnologías</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-envelope"></i></div>
                    <div class="stat-value"><?= $totalMensajes ?></div>
                    <div class="stat-label">Mensajes nuevos</div>
                </div>
            </div>
        </div>

        <!-- Proyectos recientes -->
        <div class="dash-card">
            <div class="dash-card-header">
                <span>Proyectos recientes</span>
                <a href="proyectos.php" class="btn btn-sm btn-accent"><i class="bi bi-plus me-1"></i>Gestionar</a>
            </div>
            <?php
            $proyectos = $pdo->query('SELECT * FROM proyectos WHERE activo=1 ORDER BY orden LIMIT 5')->fetchAll();
            ?>
            <table class="table dash-table">
                <thead><tr><th>Título</th><th>Descripción</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php foreach ($proyectos as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['titulo']) ?></td>
                        <td class="text-muted"><?= htmlspecialchars(mb_substr($p['descripcion'], 0, 60)) ?>...</td>
                        <td>
                            <a href="proyectos.php?edit=<?= $p['id'] ?>" class="btn btn-xs btn-icon"><i class="bi bi-pencil"></i></a>
                            <a href="proyectos.php?delete=<?= $p['id'] ?>" class="btn btn-xs btn-icon btn-danger-icon" onclick="return confirm('¿Eliminar?')"><i class="bi bi-trash"></i></a>
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
