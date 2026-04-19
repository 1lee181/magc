<?php
// api/stats.php - Caden's dynamic stats fetcher
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

try {
    $db = getDB();

    // Dynamically count rows in the tables
    $membersCount = $db->query("SELECT COUNT(*) FROM members")->fetchColumn();
    $eventsCount  = $db->query("SELECT COUNT(*) FROM past_events")->fetchColumn();
    $execsCount   = $db->query("SELECT COUNT(*) FROM executives")->fetchColumn();

    // Send the EXACT raw database counts back to the JavaScript (No fake padding!)
    echo json_encode([
        'success' => true,
        'stats' => [
            'members'    => (int)$membersCount,
            'events'     => (int)$eventsCount,
            'executives' => (int)$execsCount
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}