<?php
/**
 * Order history page for a logged-in customer.
 */
require_once __DIR__ . '/../Includes/header.php';

if (!isset($_SESSION['user_id'])) {
    redirect(BASE_URL . 'login.php');
}

$userId = (int) $_SESSION['user_id'];
$stmt = db()->prepare('SELECT id, total, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
?>
<section class="container py-5">
    <h2 class="fw-bold mb-4">Order history</h2>
    <?php if ($orders): ?>
        <div class="section-card p-4">
            <table class="table">
                <thead><tr><th>Order #</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo (int) $order['id']; ?></td>
                        <td><?php echo format_currency((float) $order['total']); ?></td>
                        <td><?php echo e($order['status']); ?></td>
                        <td><?php echo e($order['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="section-card p-5 text-center">
            <p class="text-muted">No orders yet.</p>
        </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../Includes/footer.php'; ?>
