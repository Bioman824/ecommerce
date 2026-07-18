<?php
/**
 * Basic settings management screen for the admin panel.
 */
require_once __DIR__ . '/../Includes/functions.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    redirect(BASE_URL . 'Admin/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach (['site_name','site_tagline'] as $key) {
        $value = sanitize($_POST[$key] ?? '');
        $stmt = db()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        $stmt->execute([$key, $value]);
    }
    $successMessage = 'Settings updated.';
}

$siteName = get_setting('site_name', SITE_NAME);
$siteTagline = get_setting('site_tagline', 'Premium fashion for modern living.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-4">
    <h2 class="fw-bold mb-4">Settings</h2>
    <?php if (!empty($successMessage)): ?><div class="alert alert-success"><?php echo e($successMessage); ?></div><?php endif; ?>
    <div class="card p-4">
        <form method="post">
            <?php echo csrf_field(); ?>
            <div class="mb-3"><label class="form-label">Site Name</label><input class="form-control" name="site_name" value="<?php echo e($siteName); ?>"></div>
            <div class="mb-3"><label class="form-label">Site Tagline</label><input class="form-control" name="site_tagline" value="<?php echo e($siteTagline); ?>"></div>
            <button class="btn btn-dark" type="submit">Save Settings</button>
        </form>
    </div>
</div>
</body>
</html>
