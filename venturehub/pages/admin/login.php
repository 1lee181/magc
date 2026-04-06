<?php
require_once __DIR__ . '/../../includes/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['admin_id'])) {
    header('Location: ' . BASE . '/pages/admin/dashboard.php');
    exit;
}

$error = '';
if (!empty($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | VentureHub</title>
    <link rel="stylesheet" href="<?= BASE ?>/css/style.css">
    <link rel="stylesheet" href="<?= BASE ?>/css/admin.css">
</head>
<body class="admin-body">

<div class="login-wrapper">
    <div class="login-box">
        <div class="login-logo">
            <span>VentureHub</span>
        </div>
        <h1>Admin Login</h1>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE ?>/api/auth.php?action=login">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-red" style="width:100%;">Log In</button>
        </form>

        <p style="text-align:center; margin-top:1rem; font-size:0.82rem; color:#999;">
            Session persists until logout.
        </p>
        <p style="text-align:center; margin-top:0.5rem; font-size:0.82rem;">
            <a href="<?= BASE ?>/">Back to Site</a>
        </p>
    </div>
</div>

</body>
</html>
