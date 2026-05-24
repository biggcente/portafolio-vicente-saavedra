<?php
// ============================================================
// api.php — Endpoints AJAX: login + contacto
// ============================================================
require_once 'includes/db.php';
require_once 'includes/auth.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ----------------------------------------------------------
    // LOGIN
    // ----------------------------------------------------------
    case 'login':
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$email || !$password) {
            jsonResponse(false, 'Completa todos los campos.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            jsonResponse(false, 'Correo inválido.');
        }
        if (login($email, $password)) {
            jsonResponse(true, 'Sesión iniciada.', ['redirect' => 'admin/dashboard.php']);
        } else {
            jsonResponse(false, 'Correo o contraseña incorrectos.');
        }
        break;

    // ----------------------------------------------------------
    // CONTACTO
    // ----------------------------------------------------------
    case 'contacto':
        $nombre  = clean($_POST['nombre']  ?? '');
        $email   = clean($_POST['email']   ?? '');
        $asunto  = clean($_POST['asunto']  ?? '');
        $mensaje = clean($_POST['mensaje'] ?? '');

        if (!$nombre || !$email || !$asunto || !$mensaje) {
            jsonResponse(false, 'Completa todos los campos.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            jsonResponse(false, 'Correo inválido.');
        }

        $pdo  = getDB();
        $stmt = $pdo->prepare(
            'INSERT INTO mensajes (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$nombre, $email, $asunto, $mensaje]);
        jsonResponse(true, '¡Mensaje enviado con éxito! Te responderé pronto.');
        break;

    // ----------------------------------------------------------
    // LOGOUT
    // ----------------------------------------------------------
    case 'logout':
        logout();
        break;

    default:
        http_response_code(400);
        jsonResponse(false, 'Acción no válida.');
}
