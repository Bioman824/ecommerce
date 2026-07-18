<?php
/**
 * Customer registration page.
 */
require_once __DIR__ . '/Includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name !== '' && $email !== '' && $password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = db()->prepare('INSERT INTO users (full_name, email, password_hash, role, is_active, email_verified) VALUES (?, ?, ?, "customer", 1, 0)');
        $stmt->execute([$name, $email, $hash]);
        $successMessage = 'Account created. You can now sign in.';
    }
}
?>
<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="section-card p-4 p-lg-5">
                <h2 class="fw-bold mb-3">Create your account</h2>
                <?php if (!empty($successMessage)): ?><div class="alert alert-success"><?php echo e($successMessage); ?></div><?php endif; ?>
                <form method="post">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3"><input class="form-control" type="text" name="name" placeholder="Full name" required></div>
                    <div class="mb-3"><input class="form-control" type="email" name="email" placeholder="Email" required></div>
                    <div class="mb-3"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
                    <button class="btn btn-accent w-100" type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
