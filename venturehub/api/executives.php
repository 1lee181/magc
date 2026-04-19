<?php
// api/executives.php - Aleesha's executives CRUD handler
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'list') {
        $rows = getDB()->query('SELECT * FROM executives ORDER BY display_order ASC')->fetchAll();
        echo json_encode(['executives' => $rows]);
        exit;
    }

    if ($action === 'get') {
        $id   = (int)($_GET['id'] ?? 0);
        $stmt = getDB()->prepare('SELECT * FROM executives WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        echo json_encode($row ? ['executive' => $row] : ['error' => 'Not found.']);
        exit;
    }
}

requireAdmin();

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'insert':
            $name = trim($_POST['name'] ?? '');
            if (!$name) { echo json_encode(['success' => false, 'error' => 'Name required.']); exit; }

            $stmt = getDB()->prepare(
                'INSERT INTO executives (name, role, bio, photo_url, linkedin_url, instagram_url, display_order)
                 VALUES (?,?,?,?,?,?,?)'
            );
            $stmt->execute([
                $name,
                trim($_POST['role']          ?? ''),
                trim($_POST['bio']           ?? ''),
                trim($_POST['photo_url']     ?? ''),
                trim($_POST['linkedin_url']  ?? ''),
                trim($_POST['instagram_url'] ?? ''),
                (int)($_POST['display_order'] ?? 0),
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'update':
            $id   = (int)($_POST['id']   ?? 0);
            $name = trim($_POST['name']  ?? '');
            if (!$id || !$name) { echo json_encode(['success' => false, 'error' => 'Invalid data.']); exit; }

            $stmt = getDB()->prepare(
                'UPDATE executives SET name=?, role=?, bio=?, photo_url=?, linkedin_url=?, instagram_url=?, display_order=?
                 WHERE id=?'
            );
            $stmt->execute([
                $name,
                trim($_POST['role']          ?? ''),
                trim($_POST['bio']           ?? ''),
                trim($_POST['photo_url']     ?? ''),
                trim($_POST['linkedin_url']  ?? ''),
                trim($_POST['instagram_url'] ?? ''),
                (int)($_POST['display_order'] ?? 0),
                $id,
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) { echo json_encode(['success' => false, 'error' => 'Invalid ID.']); exit; }
            getDB()->prepare('DELETE FROM executives WHERE id = ?')->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action.']);
    }
}