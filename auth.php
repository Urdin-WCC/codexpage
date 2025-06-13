<?php
require_once __DIR__.'/db.php';


if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
function current_user(): ?array {
session_start();

function get_current_user(): ?array {
    if (isset($_SESSION['user_id'])) {
        return get_user_by_id($_SESSION['user_id']);
    }
    return null;
}

function get_user_by_id(int $id): ?array {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function login(string $email, string $password): bool {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

function logout(): void {
    session_unset();
    session_destroy();
}

function require_login(): void {
    if (!isset($_SESSION['user_id'])) {

        $redirect = urlencode($_SERVER['REQUEST_URI'] ?? 'index.php');
        header('Location: login.php?redirect='.$redirect);

        header('Location: login.php');

        exit;
    }
}

function check_access(array $config): bool {

    $user = current_user();

    $user = get_current_user();

    $level = $user['level'] ?? 0;
    $user_id = $user['id'] ?? 0;

    // rule: 'public', 'authenticated', 'ids', 'range'
    $rule = $config['rule'] ?? 'public';
    $allowed_ids = $config['ids'] ?? [];
    $min = $config['min'] ?? 1;
    $max = $config['max'] ?? 100;
    $except_ids = $config['except_ids'] ?? [];

    if ($level === 100) {
        return true; // full access for level 100
    }


    if (in_array($user_id, $except_ids, true)) {

    if (in_array($user_id, $except_ids)) {
        return false;
    }

    switch ($rule) {
        case 'public':
            return true;
        case 'authenticated':
            return $level >= 1;
        case 'ids':
            return in_array($user_id, $allowed_ids);
        case 'range':
            return $level >= $min && $level <= $max;
        default:
            return false;
    }
}

function enforce_access(array $config): void {
    $allowed = check_access($config);
    $user = current_user();

    $user = get_current_user();
    if (!$allowed) {
        if ($user) {
            echo 'Acceso denegado';
            exit;
        } else {
            $redirect = urlencode($_SERVER['REQUEST_URI'] ?? 'index.php');
            header('Location: login.php?redirect='.$redirect);

            header('Location: login.php');
            exit;
        }
    }
}
