<?php
/**
 * Payment gateway configuration screen for the admin panel.
 */
$pageTitle = 'Payment Gateways';
$pageSubtitle = 'Connect and configure your checkout payment providers.';
$activeNav = 'payment';
require_once __DIR__ . '/Includes/header.php';

$successMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach (['flutterwave', 'paystack'] as $gateway) {
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
<?php if ($successMessage !== ''): ?><div class="admin-alert"><i class="bi bi-check-circle-fill me-1"></i><?php echo e($successMessage); ?></div><?php endif; ?>
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h5>Providers</h5>
            <p>Toggle a gateway on and fill in its API credentials</p>
        </div>
    </div>
    <div class="admin-card-body">
        <form method="post">
            <?php echo csrf_field(); ?>
            <?php foreach (['flutterwave' => ['Flutterwave', 'bi-flag'], 'paystack' => ['Paystack', 'bi-credit-card']] as $key => $meta):
                $isEnabled = (bool) ($gatewayMap[$key]['is_enabled'] ?? 0);
                $env = $gatewayMap[$key]['environment'] ?? 'sandbox';
            ?>
                <div class="gateway-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi <?php echo e($meta[1]); ?> fs-5 text-muted"></i>
                            <span class="fw-bold"><?php echo e($meta[0]); ?></span>
                            <span class="status-pill status-<?php echo e($env); ?>"><?php echo e($env); ?></span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" name="<?php echo e($key); ?>_enabled" id="<?php echo e($key); ?>_enabled" <?php echo $isEnabled ? 'checked' : ''; ?>>
                            <label class="form-check-label small fw-semibold" for="<?php echo e($key); ?>_enabled"><?php echo $isEnabled ? 'Enabled' : 'Disabled'; ?></label>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Public key</label>
                            <input class="form-control" name="<?php echo e($key); ?>_public_key" placeholder="pk_..." value="<?php echo e($gatewayMap[$key]['public_key'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Secret key</label>
                            <input class="form-control" type="password" name="<?php echo e($key); ?>_secret_key" placeholder="sk_..." value="<?php echo e($gatewayMap[$key]['secret_key'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Encryption key</label>
                            <input class="form-control" name="<?php echo e($key); ?>_encryption_key" placeholder="Optional" value="<?php echo e($gatewayMap[$key]['encryption_key'] ?? ''); ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Webhook secret</label>
                            <input class="form-control" name="<?php echo e($key); ?>_webhook_secret" placeholder="whsec_..." value="<?php echo e($gatewayMap[$key]['webhook_secret'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Environment</label>
                            <select class="form-select" name="<?php echo e($key); ?>_environment">
                                <option value="sandbox" <?php echo $env === 'sandbox' ? 'selected' : ''; ?>>Sandbox</option>
                                <option value="production" <?php echo $env === 'production' ? 'selected' : ''; ?>>Production</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <button class="btn-admin-accent mt-4" type="submit"><i class="bi bi-check-lg me-1"></i>Save gateway settings</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
