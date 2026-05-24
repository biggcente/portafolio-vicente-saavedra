<?php
// ============================================================
// admin/sidebar.php — Sidebar reutilizable
// ============================================================
$current = basename($_SERVER['PHP_SELF']);
$pdo2 = getDB();
$unread = $pdo2->query('SELECT COUNT(*) FROM mensajes WHERE leido=0')->fetchColumn();
?>
<aside class="sidebar">
    <div class="sidebar-user">
        <div class="sidebar-avatar">VS</div>
        <div>
            <div class="sidebar-name"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></div>
            <div class="sidebar-role">Administrador</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php"   class="<?= $current==='dashboard.php'   ? 'active':'' ?>"><i class="bi bi-grid"></i> Dashboard</a>
        <a href="biografia.php"   class="<?= $current==='biografia.php'   ? 'active':'' ?>"><i class="bi bi-person"></i> Biografía</a>
        <a href="habilidades.php" class="<?= $current==='habilidades.php' ? 'active':'' ?>"><i class="bi bi-tools"></i> Habilidades</a>
        <a href="tecnologias.php" class="<?= $current==='tecnologias.php' ? 'active':'' ?>"><i class="bi bi-cpu"></i> Tecnologías</a>
        <a href="proyectos.php"   class="<?= $current==='proyectos.php'   ? 'active':'' ?>"><i class="bi bi-folder"></i> Proyectos</a>
        <a href="mensajes.php"    class="<?= $current==='mensajes.php'    ? 'active':'' ?>">
            <i class="bi bi-envelope"></i> Mensajes
            <?php if ($unread > 0): ?><span class="badge-count"><?= $unread ?></span><?php endif; ?>
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="../index.php" target="_blank"><i class="bi bi-eye me-2"></i>Ver sitio</a>
        <a href="../api.php?action=logout"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a>
    </div>
</aside>
