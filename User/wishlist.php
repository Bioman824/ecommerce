<?php
/**
 * Wishlist page for logged-in customers.
 */
require_once __DIR__ . '/../Includes/header.php';

if (!isset($_SESSION['user_id'])) {
    redirect(BASE_URL . 'login.php');
}

$userId = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    verify_csrf();
    $productId = (int) $_POST['product_id'];
    $stmt = db()->prepare('INSERT INTO wishlists (user_id, product_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE product_id = product_id');
    $stmt->execute([$userId, $productId]);
}

$wishlistStmt = db()->prepare('SELECT p.id, p.name, p.slug, p.regular_price, p.sale_price, p.image_url FROM wishlists w JOIN products p ON p.id = w.product_id WHERE w.user_id = ? ORDER BY w.created_at DESC');
$wishlistStmt->execute([$userId]);
$items = $wishlistStmt->fetchAll();
?>
<section class="container py-5">
    <h2 class="fw-bold mb-4">My Wishlist</h2>
    <div class="row g-4">
        <?php foreach ($items as $item): ?>
            <div class="col-md-4">
                <div class="section-card p-4">
                    <h5 class="fw-bold"><?php echo e($item['name']); ?></h5>
                    <p class="text-muted"><?php echo format_currency((float) ($item['sale_price'] > 0 ? $item['sale_price'] : $item['regular_price'])); ?></p>
                    <a class="btn btn-outline-dark btn-sm" href="<?php echo BASE_URL; ?>product.php?slug=<?php echo e($item['slug']); ?>">View</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../Includes/footer.php'; ?>
