<?php
/**
 * Name: Caden Chan, Matthew Kolesnik, Gurehmat Chahal, Aleesha Abdullah
 * Date: April 19, 2026
 * Description: PDO database connection singleton for secure MySQL interactions across the application.
 */

// Database configuration for server database
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'venturehub');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDB(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database connection failed.']);
            exit;
        }
    }
    return $pdo;
}
