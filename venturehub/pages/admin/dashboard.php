<?php
/**
 * Name: Caden Chan, Matthew Kolesnik, Gurehmat Chahal, Aleesha Abdullah
 * Date: April 19, 2026
 * Description: Secure admin panel interface for managing site content (events, partners, execs) dynamically.
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();
$adminUser = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | MVCC</title>
    <link rel="stylesheet" href="<?= BASE ?>/css/style.css">
    <link rel="stylesheet" href="<?= BASE ?>/css/admin.css">
</head>
<body class="admin-body">

<nav class="admin-navbar">
  <div class="admin-navbar-inner">
    <a class="navbar-brand" href="<?= BASE ?>/pages/admin/dashboard.php">
      <img src="<?= BASE ?>/images/mvcc-logo.png" alt="MVCC">
    </a>
    <ul class="admin-nav-links">
        <li><a href="<?= BASE ?>/">View Site</a></li>
        <li><a href="<?= BASE ?>/api/auth.php?action=logout">Logout (<?= htmlspecialchars($adminUser, ENT_QUOTES, 'UTF-8') ?>)</a></li>
    </ul>
  </div>
</nav>

<div class="admin-container">

    <div id="flashMsg" class="alert" style="display:none;"></div>

    <h1 class="admin-page-title">Admin Dashboard</h1>

    <!-- ============================================================
         PAST EVENTS — Matthew
         ============================================================ -->
    <div id="admin-events">

        <div class="admin-card">
            <div class="admin-card-header">
                <span id="eventFormTitle">Add New Event</span>
                <button class="btn btn-sm btn-outline" id="eventCancel" style="display:none;">Cancel</button>
            </div>
            <div class="admin-card-body">
                <form id="eventForm">
                    <input type="hidden" id="eventId">
                    <div class="admin-form-grid">
                        <div class="form-group full">
                            <label>Title *</label>
                            <input type="text" id="eventTitle" placeholder="Event title" required>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" id="eventDate">
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" id="eventLocation" placeholder="e.g. MDCL 1305 or Online">
                        </div>
                        <div class="form-group full">
                            <label>Description</label>
                            <textarea id="eventDescription" rows="3" placeholder="Event description..."></textarea>
                        </div>
                        <div class="form-group full">
                            <label>Photo URL (optional)</label>
                            <input type="text" id="eventPhoto" placeholder="https://...">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-red btn-sm">Save Event</button>
                </form>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">Past Events</div>
            <div class="admin-card-body" style="padding:0; overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr><th>Title</th><th>Date</th><th>Location</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="eventsTbody">
                        <tr><td colspan="4" style="padding:1rem;color:#999;">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ============================================================
         PARTNERS — Matthew
         ============================================================ -->
    <div id="admin-partners">

        <div class="admin-card">
            <div class="admin-card-header">
                <span id="partnerFormTitle">Add New Partner</span>
                <button class="btn btn-sm btn-outline" id="partnerCancel" style="display:none;">Cancel</button>
            </div>
            <div class="admin-card-body">
                <form id="partnerForm">
                    <input type="hidden" id="partnerId">
                    <div class="admin-form-grid">
                        <div class="form-group">
                            <label>Name *</label>
                            <input type="text" id="partnerName" placeholder="Company or startup name" required>
                        </div>
                        <div class="form-group">
                            <label>Display Order</label>
                            <input type="number" id="partnerOrder" value="0" min="0">
                        </div>
                        <div class="form-group">
                            <label>Logo URL</label>
                            <input type="text" id="partnerLogo" placeholder="https://...">
                        </div>
                        <div class="form-group">
                            <label>Website URL</label>
                            <input type="text" id="partnerWebsite" placeholder="https://...">
                        </div>
                        <div class="form-group full">
                            <label>Description</label>
                            <textarea id="partnerDesc" rows="2" placeholder="Short description..."></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-red btn-sm">Save Partner</button>
                </form>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">Partners and Startups</div>
            <div class="admin-card-body" style="padding:0; overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr><th>Name</th><th>Website</th><th>Order</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="partnersTbody">
                        <tr><td colspan="4" style="padding:1rem;color:#999;">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ============================================================
         EXECUTIVES — Aleesha
         ============================================================ -->
    <div id="admin-execs">

        <div class="admin-card">
            <div class="admin-card-header">
                <span id="execFormTitle">Add New Executive</span>
                <button class="btn btn-sm btn-outline" id="execCancel" style="display:none;">Cancel</button>
            </div>
            <div class="admin-card-body">
                <form id="execForm">
                    <input type="hidden" id="execId">
                    <div class="admin-form-grid">
                        <div class="form-group">
                            <label>Name *</label>
                            <input type="text" id="execName" placeholder="Full name" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" id="execRole" placeholder="e.g. President, VP Finance">
                        </div>
                        <div class="form-group full">
                            <label>Bio</label>
                            <textarea id="execBio" rows="2" placeholder="Short biography..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Photo URL</label>
                            <input type="text" id="execPhoto" placeholder="https://...">
                        </div>
                        <div class="form-group">
                            <label>Display Order</label>
                            <input type="number" id="execOrder" value="0" min="0">
                        </div>
                        <div class="form-group">
                            <label>LinkedIn URL</label>
                            <input type="text" id="execLinkedin" placeholder="https://linkedin.com/in/...">
                        </div>
                        <div class="form-group">
                            <label>Instagram URL</label>
                            <input type="text" id="execInstagram" placeholder="https://instagram.com/...">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-red btn-sm">Save Executive</button>
                </form>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">Executive Team</div>
            <div class="admin-card-body" style="padding:0; overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr><th>Name</th><th>Role</th><th>Order</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="execsTbody">
                        <tr><td colspan="4" style="padding:1rem;color:#999;">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<script>
    // Pass BASE path to JS so AJAX calls use correct URLs
    const BASE = '<?= BASE ?>';
</script>
<script src="<?= BASE ?>/js/admin.js"></script>
</body>
</html>