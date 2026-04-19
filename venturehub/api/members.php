<?php
/**
 * Name: Caden Chan, Matthew Kolesnik, Gurehmat Chahal, Aleesha Abdullah
 * Date: April 19, 2026
 * Description: API endpoint handling general member sign-ups, form submissions, and real-time duplicate email validation.
 */

// api/members.php - Gurehmat's sign-up form handler
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$action = $_POST['action'] ?? '';

switch ($action) {

    case 'check_email':
        $email = trim($_POST['email'] ?? '');
        if (!$email) { echo json_encode(['taken' => false]); exit; }

        $stmt = getDB()->prepare('SELECT id FROM members WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        echo json_encode(['taken' => (bool)$stmt->fetch()]);
        break;

    case 'submit':
        $name    = trim($_POST['name']    ?? '');
        $email   = trim($_POST['email']   ?? '');
        $program = trim($_POST['program'] ?? '');
        $year    = (int)($_POST['year']   ?? 0);

        // Server-side validation
        if (!$name)  { echo json_encode(['success' => false, 'error' => 'Name is required.']);    exit; }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'error' => 'Invalid email address.']); exit;
        }
        if (!$program) { echo json_encode(['success' => false, 'error' => 'Program is required.']); exit; }
        if ($year < 1 || $year > 5) { echo json_encode(['success' => false, 'error' => 'Invalid year.']); exit; }

        // Check duplicate
        $chk = getDB()->prepare('SELECT id FROM members WHERE email = ? LIMIT 1');
        $chk->execute([$email]);
        if ($chk->fetch()) {
            echo json_encode(['success' => false, 'error' => 'This email is already registered.']);
            exit;
        }

        // Insert
        $ins = getDB()->prepare(
            'INSERT INTO members (name, email, program, year) VALUES (?, ?, ?, ?)'
        );
        $ins->execute([$name, $email, $program, $year]);

        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action.']);
}