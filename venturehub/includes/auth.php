<?php
/**
 * Name: Caden Chan, Matthew Kolesnik, Gurehmat Chahal, Aleesha Abdullah
 * Date: April 19, 2026
 * Description: Authentication helper functions for protecting admin routes and verifying active sessions.
 */
require_once __DIR__ . '/config.php';

/**
 * Enforces admin-only access for a page or endpoint.
 * Starts a session if one is not already active, then checks for a valid
 * admin session. If no admin session is found, redirects the user to the
 * admin login page and halts execution.
 *
 * @return void
 */

function requireAdmin(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['admin_id'])) {
        header('Location: ' . BASE . '/pages/admin/login.php');
        exit;
    }
}

/**
 * Checks whether the current user has an active admin session.
 * Starts a session if one is not already active, then returns whether
 * the admin_id session variable is set and non-empty.
 *
 * @return bool True if an admin is currently logged in, false otherwise.
 */

function isAdmin(): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($_SESSION['admin_id']);
}