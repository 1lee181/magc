<?php
/**
 * Name: Caden Chan, Matthew Kolesnik, Gurehmat Chahal, Aleesha Abdullah
 * Date: April 19, 2026
 * Description: Authentication helper functions for protecting admin routes and verifying active sessions.
 */
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