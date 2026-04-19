<?php
require_once __DIR__ . '/config.php';

function requireAdmin(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['admin_id'])) {
        header('Location: ' . BASE . '/pages/admin/login.php');
        exit;
    }
}

function isAdmin(): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($_SESSION['admin_id']);
}