<?php
/**
 * User profile and account management area.
 */
require_once __DIR__ . '/../Includes/header.php';

if (!isset($_SESSION['user_id'])) {
    redirect(BASE_URL . 'login.php');
}

$userId = (int) $_SESSION['user_id'];
$profile = db()->prepare('SELECT full_name, email, created_at FROM users WHERE id = ? LIMIT 1');
$profile->execute([$userId]);
$user = $profile->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    verify_csrf();
    $name = sanitize($_POST['name'] ?? '');
    $stmt = db()->prepare('UPDATE users SET full_name = ? WHERE id = ?');
    $stmt->execute([$name, $userId]);
    $successMessage = 'Profile updated.';
}
?>
<section class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="section-card p-4">
                <h4 class="fw-bold">My Account</h4>
                <p class="text-muted">Welcome back, <?php echo e($user['full_name'] ?? 'guest'); ?>.</p>
                <ul class="list-unstyled text-muted">
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="wishlist.php">Wishlist</a></li>
                    <li><a href="orders.php">Order history</a></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="section-card p-4">
                <h4 class="fw-bold">Edit profile</h4>
                <?php if (!empty($successMessage)): ?><div class="alert alert-success"><?php echo e($successMessage); ?></div><?php endif; ?>
                <form method="post">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3"><input class="form-control" name="name" value="<?php echo e($user['full_name'] ?? ''); ?>" required></div>
                    <div class="mb-3"><input class="form-control" value="<?php echo e($user['email'] ?? ''); ?>" disabled></div>
                    <button class="btn btn-accent" type="submit">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../Includes/footer.php'; ?>
