<?php
/**
 * Order management screen in the admin panel.
 */
$pageTitle = 'Orders';
$pageSubtitle = 'Track and review customer orders.';
$activeNav = 'orders';
require_once __DIR__ . '/Includes/header.php';

$orders = db()->query('SELECT id, customer_name, customer_email, total, status, created_at FROM orders ORDER BY created_at DESC LIMIT 20')->fetchAll();
?>
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h5>All Orders</h5>
            <p><?php echo count($orders); ?> most recent orders</p>
        </div>
    </div>
    <div class="admin-table-wrap">
        <?php if (!empty($orders)): ?>
            <table class="admin-table">
                <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td class="row-title">#<?php echo (int) $order['id']; ?></td>
                        <td>
                            <div class="row-title"><?php echo e($order['customer_name']); ?></div>
                            <div class="row-subtle"><?php echo e($order['customer_email']); ?></div>
                        </td>
                        <td class="fw-semibold"><?php echo format_currency((float) $order['total']); ?></td>
                        <td><span class="status-pill status-<?php echo e($order['status']); ?>"><?php echo e($order['status']); ?></span></td>
                        <td class="row-subtle"><?php echo e(date('M j, Y', strtotime($order['created_at']))); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="admin-empty"><i class="bi bi-receipt"></i><h6>No orders yet</h6><p>Orders placed on the storefront will show up here.</p></div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
