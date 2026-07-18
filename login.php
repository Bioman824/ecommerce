<?php
/**
 * Customer and admin login page.
 */
require_once __DIR__ . '/Includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = db()->prepare('SELECT id, full_name, email, password_hash, role FROM users WHERE email = ? AND is_active = 1 LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    $validPassword = $user && password_verify($password, $user['password_hash']);

    if ($validPassword && $user['role'] === 'admin') {
        $errorMessage = 'Admin accounts must sign in from the admin login page.';
    } elseif ($validPassword) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        redirect(BASE_URL . 'index.php');
    } else {
        $errorMessage = 'Invalid credentials.';
    }
}
?>
<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="section-card p-4 p-lg-5">
                <h2 class="fw-bold mb-3">Welcome back</h2>
                <p class="text-muted">Sign in to manage your orders, wishlist and account preferences.</p>
                <?php if (!empty($errorMessage)): ?><div class="alert alert-danger"><?php echo e($errorMessage); ?></div><?php endif; ?>
                <form method="post">
                    <div class="mb-3"><input class="form-control" type="email" name="email" placeholder="Email" required></div>
                    <div class="mb-3"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
                    <button class="btn btn-accent w-100" type="submit">Log in</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
