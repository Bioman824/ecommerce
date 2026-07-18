<?php
/**
 * Payment gateway configuration screen for the admin panel.
 */
require_once __DIR__ . '/../Includes/functions.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    redirect(BASE_URL . 'Admin/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach (['flutterwave','paystack'] as $gateway) {
        $enabled = isset($_POST[$gateway . '_enabled']) ? 1 : 0;
        $environment = sanitize($_POST[$gateway . '_environment'] ?? 'sandbox');
        $publicKey = sanitize($_POST[$gateway . '_public_key'] ?? '');
        $secretKey = sanitize($_POST[$gateway . '_secret_key'] ?? '');
        $encryptionKey = sanitize($_POST[$gateway . '_encryption_key'] ?? '');
        $webhookSecret = sanitize($_POST[$gateway . '_webhook_secret'] ?? '');
        $stmt = db()->prepare('INSERT INTO payment_gateways (gateway_name, public_key, secret_key, encryption_key, webhook_secret, environment, is_enabled, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE public_key = VALUES(public_key), secret_key = VALUES(secret_key), encryption_key = VALUES(encryption_key), webhook_secret = VALUES(webhook_secret), environment = VALUES(environment), is_enabled = VALUES(is_enabled)');
        $stmt->execute([$gateway, $publicKey, $secretKey, $encryptionKey, $webhookSecret, $environment, $enabled, 1]);
    }
    $successMessage = 'Gateway settings updated.';
}

$gateways = db()->query('SELECT gateway_name, public_key, secret_key, encryption_key, webhook_secret, environment, is_enabled FROM payment_gateways ORDER BY sort_order ASC')->fetchAll();
$gatewayMap = [];
foreach ($gateways as $gateway) {
    $gatewayMap[$gateway['gateway_name']] = $gateway;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateways - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-4">
    <h2 class="fw-bold mb-4">Payment Gateways</h2>
    <?php if (!empty($successMessage)): ?><div class="alert alert-success"><?php echo e($successMessage); ?></div><?php endif; ?>
    <div class="card p-4">
        <form method="post">
            <?php echo csrf_field(); ?>
            <?php foreach (['flutterwave' => 'Flutterwave', 'paystack' => 'Paystack'] as $key => $label): ?>
                <div class="border rounded p-3 mb-3">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="<?php echo e($key); ?>_enabled" id="<?php echo e($key); ?>_enabled" <?php echo (($gatewayMap[$key]['is_enabled'] ?? 0) ? 'checked' : ''); ?>>
                        <label class="form-check-label fw-bold" for="<?php echo e($key); ?>_enabled"><?php echo e($label); ?></label>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4"><input class="form-control" name="<?php echo e($key); ?>_public_key" placeholder="Public Key" value="<?php echo e($gatewayMap[$key]['public_key'] ?? ''); ?>"></div>
                        <div class="col-md-4"><input class="form-control" name="<?php echo e($key); ?>_secret_key" placeholder="Secret Key" value="<?php echo e($gatewayMap[$key]['secret_key'] ?? ''); ?>"></div>
                        <div class="col-md-4"><input class="form-control" name="<?php echo e($key); ?>_encryption_key" placeholder="Encryption Key" value="<?php echo e($gatewayMap[$key]['encryption_key'] ?? ''); ?>"></div>
                        <div class="col-md-6"><input class="form-control" name="<?php echo e($key); ?>_webhook_secret" placeholder="Webhook Secret" value="<?php echo e($gatewayMap[$key]['webhook_secret'] ?? ''); ?>"></div>
                        <div class="col-md-6">
                            <select class="form-select" name="<?php echo e($key); ?>_environment">
                                <option value="sandbox" <?php echo (($gatewayMap[$key]['environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : ''); ?>>Sandbox</option>
                                <option value="production" <?php echo (($gatewayMap[$key]['environment'] ?? 'sandbox') === 'production' ? 'selected' : ''); ?>>Production</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <button class="btn btn-dark" type="submit">Save gateway settings</button>
        </form>
    </div>
</div>
</body>
</html>
