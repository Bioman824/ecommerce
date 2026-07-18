<?php
/**
 * Basic settings management screen for the admin panel.
 */
$pageTitle = 'Settings';
$pageSubtitle = 'General storefront information.';
$activeNav = 'settings';
require_once __DIR__ . '/Includes/header.php';

$successMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach (['site_name', 'site_tagline'] as $key) {
        $value = sanitize($_POST[$key] ?? '');
        $stmt = db()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        $stmt->execute([$key, $value]);
    }
    $successMessage = 'Settings updated.';
}

$siteName = get_setting('site_name', SITE_NAME);
$siteTagline = get_setting('site_tagline', 'Premium fashion for modern living.');
?>
<?php if ($successMessage !== ''): ?><div class="admin-alert"><i class="bi bi-check-circle-fill me-1"></i><?php echo e($successMessage); ?></div><?php endif; ?>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h5>Store Details</h5>
                    <p>Shown in the header, footer and page titles</p>
                </div>
            </div>
            <div class="admin-card-body">
                <form method="post">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Site name</label>
                        <input class="form-control" name="site_name" value="<?php echo e($siteName); ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Site tagline</label>
                        <input class="form-control" name="site_tagline" value="<?php echo e($siteTagline); ?>">
                    </div>
                    <button class="btn-admin-accent" type="submit"><i class="bi bi-check-lg me-1"></i>Save Settings</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h5>Preview</h5>
                    <p>How it appears to shoppers</p>
                </div>
            </div>
            <div class="admin-card-body">
                <div class="row-title fs-5"><?php echo e($siteName); ?></div>
                <div class="row-subtle"><?php echo e($siteTagline); ?></div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
