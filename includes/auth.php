<?php
// ============================================================
// includes/auth.php — Sesión, login y seguridad
// ============================================================

session_start();

function isLoggedIn(): bool {
    return isset($_SESSION['admin_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ../index.php?login=required');
        exit;
    }
}

function login(string $email, string $password): bool {
    $pdo  = getDB();
    $stmt = $pdo->prepare('SELECT id, nombre, password FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id']   = $user['id'];
        $_SESSION['admin_name'] = $user['nombre'];
        return true;
    }
    return false;
}

function logout(): void {
    session_destroy();
    header('Location: ../index.php');
    exit;
}

// Sanitizar entrada de texto
function clean(string $value): string {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

// Respuesta JSON estándar
function jsonResponse(bool $success, string $message, array $data = []): void {
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
    exit;
}
