<?php
// ============================================================
// index.php — Portafolio público
// ============================================================
require_once 'includes/db.php';

$pdo = getDB();

// Cargar datos de la BD
$bio        = $pdo->query('SELECT * FROM biografia LIMIT 1')->fetch();
$habilidades = $pdo->query('SELECT * FROM habilidades WHERE activo = 1 ORDER BY orden')->fetchAll();
$tecnologias = $pdo->query('SELECT * FROM tecnologias WHERE activo = 1 ORDER BY orden')->fetchAll();
$proyectos  = $pdo->query('SELECT * FROM proyectos WHERE activo = 1 ORDER BY orden')->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $bio ? htmlspecialchars($bio['nombre']) : 'Portafolio' ?> — Desarrollador Web</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ======================================================
     NAVBAR
     ====================================================== -->
<nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="#">
            <span class="brand-avatar">VS</span>
            <?= $bio ? htmlspecialchars($bio['nombre']) : 'Vicente Saavedra' ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#biografia">Biografía</a></li>
                <li class="nav-item"><a class="nav-link" href="#habilidades">Habilidades</a></li>
                <li class="nav-item"><a class="nav-link" href="#tecnologias">Tecnologías</a></li>
                <li class="nav-item"><a class="nav-link" href="#proyectos">Proyectos</a></li>
            </ul>
            <button class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="bi bi-lock me-1"></i> Iniciar Sesión
            </button>
        </div>
    </div>
</nav>

<!-- ======================================================
     SECCIÓN 1: BIOGRAFÍA
     ====================================================== -->
<section id="biografia" class="section-bio">
    <div class="container">
        <p class="section-label">01 — Sobre mí</p>
        <div class="row align-items-center g-5">
            <div class="col-md-3 text-center">
                <?php if ($bio && $bio['foto']): ?>
                    <img src="<?= htmlspecialchars($bio['foto']) ?>" alt="Foto de perfil" class="bio-photo">
                <?php else: ?>
                    <div class="bio-photo-placeholder">
                        <i class="bi bi-person-fill"></i>
                    </div>
                <?php endif; ?>
                <div class="bio-socials mt-3">
                    <?php if ($bio && $bio['github_url']): ?>
                        <a href="<?= htmlspecialchars($bio['github_url']) ?>" target="_blank"><i class="bi bi-github"></i></a>
                    <?php endif; ?>
                    <?php if ($bio && $bio['linkedin_url']): ?>
                        <a href="<?= htmlspecialchars($bio['linkedin_url']) ?>" target="_blank"><i class="bi bi-linkedin"></i></a>
                    <?php endif; ?>
                    <?php if ($bio && $bio['instagram_url']): ?>
                        <a href="<?= htmlspecialchars($bio['instagram_url']) ?>" target="_blank"><i class="bi bi-instagram"></i></a>
                    <?php endif; ?>
                    <?php if ($bio && $bio['email']): ?>
                        <a href="mailto:<?= htmlspecialchars($bio['email']) ?>"><i class="bi bi-envelope"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-9">
                <h1 class="bio-name"><?= $bio ? htmlspecialchars($bio['nombre']) : 'Vicente Saavedra' ?></h1>
                <p class="bio-title">// <?= $bio ? htmlspecialchars($bio['titulo']) : 'Desarrollador Web Full Stack' ?></p>
                <p class="bio-desc"><?= $bio ? nl2br(htmlspecialchars($bio['descripcion'])) : '' ?></p>
                <?php if ($bio && $bio['cv_url']): ?>
                    <a href="<?= htmlspecialchars($bio['cv_url']) ?>" class="btn btn-outline-accent" download>
                        <i class="bi bi-download me-2"></i>Descargar CV
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================
     SECCIÓN 2: HABILIDADES Y HERRAMIENTAS
     ====================================================== -->
<section id="habilidades" class="section-alt">
    <div class="container">
        <p class="section-label">02 — Habilidades y herramientas</p>
        <div class="row g-3">
            <?php foreach ($habilidades as $h): ?>
            <div class="col-6 col-md-3">
                <div class="skill-card">
                    <span class="skill-icon"><?= htmlspecialchars($h['icono']) ?></span>
                    <span class="skill-name"><?= htmlspecialchars($h['nombre']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ======================================================
     SECCIÓN 3: TECNOLOGÍAS DOMINADAS
     ====================================================== -->
<section id="tecnologias">
    <div class="container">
        <p class="section-label">03 — Tecnologías dominadas</p>
        <div class="row g-3">
            <?php foreach ($tecnologias as $t): ?>
            <div class="col-md-6">
                <div class="tech-item">
                    <span class="tech-name"><?= htmlspecialchars($t['nombre']) ?></span>
                    <div class="tech-bar-bg">
                        <div class="tech-bar" data-width="<?= (int)$t['nivel'] ?>"></div>
                    </div>
                    <span class="tech-pct"><?= (int)$t['nivel'] ?>%</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ======================================================
     SECCIÓN 4: PROYECTOS REALIZADOS
     ====================================================== -->
<section id="proyectos" class="section-alt">
    <div class="container">
        <p class="section-label">04 — Proyectos realizados</p>
        <div class="row g-4">
            <?php foreach ($proyectos as $p): ?>
            <div class="col-md-4">
                <div class="project-card">
                    <div class="project-img">
                        <?php if ($p['imagen']): ?>
                            <img src="<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['titulo']) ?>">
                        <?php else: ?>
                            <i class="bi bi-image"></i>
                        <?php endif; ?>
                    </div>
                    <div class="project-body">
                        <h5 class="project-title"><?= htmlspecialchars($p['titulo']) ?></h5>
                        <p class="project-desc"><?= htmlspecialchars($p['descripcion']) ?></p>
                        <div class="project-links">
                            <?php if ($p['demo_url']): ?>
                                <a href="<?= htmlspecialchars($p['demo_url']) ?>" target="_blank" class="btn btn-sm btn-outline-accent">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Demo
                                </a>
                            <?php endif; ?>
                            <?php if ($p['github_url']): ?>
                                <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" class="btn btn-sm btn-outline-accent">
                                    <i class="bi bi-github me-1"></i>GitHub
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ======================================================
     SECCIÓN 5: FORMULARIO DE CONTACTO
     ====================================================== -->
<section id="contacto">
    <div class="container">
        <p class="section-label">05 — Formulario de contacto</p>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" id="c-nombre" class="form-control" placeholder="Tu nombre">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" id="c-email" class="form-control" placeholder="tu@email.com">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Asunto</label>
                    <input type="text" id="c-asunto" class="form-control" placeholder="Asunto del mensaje">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="form-label">Mensaje</label>
                    <textarea id="c-mensaje" class="form-control" rows="7" placeholder="Escribe tu mensaje aquí..."></textarea>
                </div>
                <button class="btn btn-accent" id="btnEnviar">
                    <i class="bi bi-send me-2"></i>Enviar Mensaje
                </button>
                <div id="contactAlert" class="mt-3" style="display:none"></div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================
     FOOTER
     ====================================================== -->
<footer>
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="footer-brand"><?= $bio ? htmlspecialchars($bio['nombre']) : 'Vicente Saavedra' ?></div>
                <div class="footer-tagline"><?= $bio ? htmlspecialchars($bio['titulo']) : 'Desarrollador Web Full Stack' ?></div>
                <p class="footer-sub">Creando soluciones web modernas y eficientes.</p>
            </div>
            <div class="col-md-2">
                <h6 class="footer-nav-title">Navegación</h6>
                <a href="#biografia">Biografía</a>
                <a href="#habilidades">Habilidades</a>
                <a href="#tecnologias">Tecnologías</a>
                <a href="#proyectos">Proyectos</a>
            </div>
            <div class="col-md-3">
                <h6 class="footer-nav-title">Contacto</h6>
                <?php if ($bio && $bio['email']): ?>
                    <a href="mailto:<?= htmlspecialchars($bio['email']) ?>"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($bio['email']) ?></a>
                <?php endif; ?>
                <?php if ($bio && $bio['telefono']): ?>
                    <a href="tel:<?= htmlspecialchars($bio['telefono']) ?>"><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($bio['telefono']) ?></a>
                <?php endif; ?>
                <?php if ($bio && $bio['ciudad']): ?>
                    <a><i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars($bio['ciudad']) ?></a>
                <?php endif; ?>
            </div>
            <div class="col-md-3">
                <h6 class="footer-nav-title">Redes Sociales</h6>
                <?php if ($bio && $bio['github_url']): ?>
                    <a href="<?= htmlspecialchars($bio['github_url']) ?>" target="_blank"><i class="bi bi-github me-2"></i>GitHub</a>
                <?php endif; ?>
                <?php if ($bio && $bio['linkedin_url']): ?>
                    <a href="<?= htmlspecialchars($bio['linkedin_url']) ?>" target="_blank"><i class="bi bi-linkedin me-2"></i>LinkedIn</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-copy">
            © <?= date('Y') ?> <?= $bio ? htmlspecialchars($bio['nombre']) : 'Vicente Saavedra' ?>. Todos los derechos reservados.
        </div>
    </div>
</footer>

<!-- ======================================================
     MODAL LOGIN
     ====================================================== -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-dark">
            <div class="modal-body p-4">
                <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="modal"></button>
                <div class="login-icon"><i class="bi bi-person"></i></div>
                <h4 class="text-center mb-1">Iniciar Sesión</h4>
                <p class="text-center text-muted-light mb-4">Accede a tu cuenta</p>
                <div id="loginAlert" class="alert alert-danger d-none" role="alert"></div>
                <div class="mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" id="loginEmail" class="form-control" placeholder="tu@email.com">
                </div>
                <div class="mb-2">
                    <label class="form-label">Contraseña</label>
                    <input type="password" id="loginPassword" class="form-control" placeholder="••••••••">
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label text-muted-light" for="rememberMe">Recordarme</label>
                    </div>
                    <a class="link-accent small">¿Olvidaste tu contraseña?</a>
                </div>
                <button class="btn btn-accent w-100" id="btnLogin">Ingresar</button>
                <p class="text-center text-muted-light small mt-3">
                    ¿No tienes cuenta? <a class="link-accent">Contacta al administrador</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
