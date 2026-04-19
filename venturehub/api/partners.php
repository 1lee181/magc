<?php
// api/partners.php - Matthew's partners/startups CRUD handler
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'list') {
        $rows = getDB()->query('SELECT * FROM partners ORDER BY display_order ASC')->fetchAll();
        echo json_encode(['partners' => $rows]);
        exit;
    }

    if ($action === 'get') {
        $id   = (int)($_GET['id'] ?? 0);
        $stmt = getDB()->prepare('SELECT * FROM partners WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        echo json_encode($row ? ['partner' => $row] : ['error' => 'Not found.']);
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
                'INSERT INTO partners (name, logo_url, website_url, description, display_order) VALUES (?,?,?,?,?)'
            );
            $stmt->execute([
                $name,
                trim($_POST['logo_url']    ?? ''),
                trim($_POST['website_url'] ?? ''),
                trim($_POST['description'] ?? ''),
                (int)($_POST['display_order'] ?? 0),
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'update':
            $id   = (int)($_POST['id']   ?? 0);
            $name = trim($_POST['name']  ?? '');
            if (!$id || !$name) { echo json_encode(['success' => false, 'error' => 'Invalid data.']); exit; }

            $stmt = getDB()->prepare(
                'UPDATE partners SET name=?, logo_url=?, website_url=?, description=?, display_order=? WHERE id=?'
            );
            $stmt->execute([
                $name,
                trim($_POST['logo_url']    ?? ''),
                trim($_POST['website_url'] ?? ''),
                trim($_POST['description'] ?? ''),
                (int)($_POST['display_order'] ?? 0),
                $id,
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) { echo json_encode(['success' => false, 'error' => 'Invalid ID.']); exit; }
            getDB()->prepare('DELETE FROM partners WHERE id = ?')->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action.']);
    }
}