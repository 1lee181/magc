<?php
/**
 * Name: Caden Chan, Matthew Kolesnik, Gurehmat Chahal, Aleesha Abdullah
 * Date: April 19, 2026
 * Description: API endpoint handling CRUD (Create, Read, Update, Delete) operations for past events via AJAX.
 */
// api/events.php - Matthew's past events CRUD handler
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'list') {
        $rows = getDB()->query('SELECT * FROM past_events ORDER BY event_date DESC')->fetchAll();
        echo json_encode(['events' => $rows]);
        exit;
    }

    if ($action === 'get') {
        $id   = (int)($_GET['id'] ?? 0);
        $stmt = getDB()->prepare('SELECT * FROM past_events WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        echo json_encode($row ? ['event' => $row] : ['error' => 'Not found.']);
        exit;
    }
}

requireAdmin();

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'insert':
            $title = trim($_POST['title'] ?? '');
            if (!$title) {
                echo json_encode(['success' => false, 'error' => 'Title is required.']);
                exit;
            }

            $stmt = getDB()->prepare(
                'INSERT INTO past_events (title, description, event_date, location, photo_url) VALUES (?,?,?,?,?)'
            );
            $stmt->execute([
                $title,
                trim($_POST['description'] ?? ''),
                $_POST['event_date'] ?: null,
                trim($_POST['location']  ?? ''),
                trim($_POST['photo_url'] ?? ''),
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'update':
            $id    = (int)($_POST['id']    ?? 0);
            $title = trim($_POST['title']  ?? '');
            if (!$id || !$title) {
                echo json_encode(['success' => false, 'error' => 'Invalid data.']);
                exit;
            }

            $stmt = getDB()->prepare(
                'UPDATE past_events SET title=?, description=?, event_date=?, location=?, photo_url=? WHERE id=?'
            );
            $stmt->execute([
                $title,
                trim($_POST['description'] ?? ''),
                $_POST['event_date'] ?: null,
                trim($_POST['location']  ?? ''),
                trim($_POST['photo_url'] ?? ''),
                $id,
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) {
                echo json_encode(['success' => false, 'error' => 'Invalid ID.']);
                exit;
            }
            getDB()->prepare('DELETE FROM past_events WHERE id = ?')->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action.']);
    }
}
