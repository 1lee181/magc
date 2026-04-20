<?php

/**
 * Name: Caden Chan, Matthew Kolesnik, Gurehmat Chahal, Aleesha Abdullah
 * Date: April 19, 2026
 * Description: API endpoint handling admin authentication, login verification, and logout actions.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'logout') {
    session_destroy();
    header('Location: ' . BASE . '/pages/admin/login.php');
    exit;
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $_SESSION['login_error'] = 'Please enter both username and password.';
        header('Location: ' . BASE . '/pages/admin/login.php');
        exit;
    }

    $stmt = getDB()->prepare('SELECT * FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id']       = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: ' . BASE . '/pages/admin/dashboard.php');
    } else {
        $_SESSION['login_error'] = 'Invalid username or password.';
        header('Location: ' . BASE . '/pages/admin/login.php');
    }
    exit;
}

header('Location: ' . BASE . '/pages/admin/login.php');
exit;
