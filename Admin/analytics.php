<?php
/**
 * Analytics reporting page for the admin dashboard.
 */
$pageTitle = 'Analytics';
$pageSubtitle = 'A snapshot of store performance.';
$activeNav = 'analytics';
require_once __DIR__ . '/Includes/header.php';

$revenue = db()->query('SELECT COALESCE(SUM(total), 0) as revenue FROM orders')->fetch();
$ordersCount = db()->query('SELECT COUNT(*) as count FROM orders')->fetch();
$customersCount = db()->query('SELECT COUNT(*) as count FROM users WHERE role = "customer"')->fetch();
$productsCount = db()->query('SELECT COUNT(*) as count FROM products')->fetch();
$avgOrderValue = ((int) $ordersCount['count'] > 0) ? ((float) $revenue['revenue'] / (int) $ordersCount['count']) : 0.0;
$statusBreakdown = db()->query('SELECT status, COUNT(*) as count FROM orders GROUP BY status ORDER BY count DESC')->fetchAll();
$maxStatusCount = 0;
foreach ($statusBreakdown as $row) {
    $maxStatusCount = max($maxStatusCount, (int) $row['count']);
}
?>
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value"><?php echo format_currency((float) $revenue['revenue']); ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-receipt"></i></div>
            <div>
                <div class="stat-label">Orders</div>
                <div class="stat-value"><?php echo (int) $ordersCount['count']; ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-graph-up-arrow"></i></div>
            <div>
                <div class="stat-label">Avg. Order Value</div>
                <div class="stat-value"><?php echo format_currency($avgOrderValue); ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-label">Customers</div>
                <div class="stat-value"><?php echo (int) $customersCount['count']; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h5>Orders by Status</h5>
            <p>Distribution of every order on record</p>
        </div>
    </div>
    <div class="admin-card-body">
        <?php if (!empty($statusBreakdown)): ?>
            <?php foreach ($statusBreakdown as $row):
                $pct = $maxStatusCount > 0 ? round(((int) $row['count'] / $maxStatusCount) * 100) : 0;
            ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="status-pill status-<?php echo e($row['status']); ?>"><?php echo e($row['status']); ?></span>
                        <span class="fw-semibold small"><?php echo (int) $row['count']; ?></span>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 999px;">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo $pct; ?>%; background: var(--admin-accent);"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="admin-empty"><i class="bi bi-graph-up-arrow"></i><h6>No order data yet</h6><p>Charts will populate once orders start coming in.</p></div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
