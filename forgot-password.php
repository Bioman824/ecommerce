<?php
/**
 * Password reset request page.
 */
require_once __DIR__ . '/Includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = sanitize($_POST['email'] ?? '');
    if ($email !== '') {
        $token = bin2hex(random_bytes(16));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = db()->prepare('INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)');
        $stmt->execute([$email, $token, $expiresAt]);
        $successMessage = 'If that email exists, a reset link has been generated.';
    }
}
?>
<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="section-card p-4 p-lg-5">
                <h2 class="fw-bold mb-3">Reset password</h2>
                <?php if (!empty($successMessage)): ?><div class="alert alert-success"><?php echo e($successMessage); ?></div><?php endif; ?>
                <form method="post">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3"><input class="form-control" type="email" name="email" placeholder="Email" required></div>
                    <button class="btn btn-accent w-100" type="submit">Send reset link</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
