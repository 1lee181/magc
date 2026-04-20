<?php

/**
 * Name: Caden Chan, Matthew Kolesnik, Gurehmat Chahal, Aleesha Abdullah
 * Date: April 19, 2026
 * Description: API endpoint that dynamically fetches live club statistics (members, events, executives) from the database.
 */

// api/stats.php - Caden's dynamic stats fetcher
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

try {
    $db = getDB();

    // Dynamically count rows in the tables
    $membersCount = $db->query("SELECT COUNT(*) FROM members")->fetchColumn();
    $eventsCount  = $db->query("SELECT COUNT(*) FROM events")->fetchColumn();
    $execsCount   = $db->query("SELECT COUNT(*) FROM executives")->fetchColumn();

    // Add base padding to reflect the history of the club!
    $displayMembers = 100 + (int)$membersCount; // remove base padding of 100 for more accurate display
    $displayEvents  = 30 + (int)$eventsCount; // remove base padding of 30 for more accurate display
    $displayExecs   = (int)$execsCount; // Left as the real number (7)

    // Send the padded counts back to the JavaScript
    echo json_encode([
        'success' => true,
        'stats' => [
            'members'    => $displayMembers,
            'events'     => $displayEvents,
            'executives' => $displayExecs
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
