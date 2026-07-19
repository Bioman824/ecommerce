<?php
/**
 * Admin dashboard overview.
 */
$pageTitle = 'Dashboard';
$pageSubtitle = 'Monitor sales, orders and product performance.';
$activeNav = 'dashboard';
require_once __DIR__ . '/Includes/header.php';

$revenue = db()->query('SELECT COALESCE(SUM(total),0) as total FROM orders')->fetch();
$orders = db()->query('SELECT COUNT(*) as count FROM orders')->fetch();
$products = db()->query('SELECT COUNT(*) as count FROM products')->fetch();
$customers = db()->query('SELECT COUNT(*) as count FROM users WHERE role = "customer"')->fetch();
$recentOrders = db()->query('SELECT id, customer_name, total, status, created_at FROM orders ORDER BY created_at DESC LIMIT 5')->fetchAll();
$recentProducts = db()->query('SELECT id, name, image_url, regular_price, sale_price, stock_quantity FROM products ORDER BY created_at DESC LIMIT 5')->fetchAll();
?>
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div>
                <div class="stat-label">Revenue</div>
                <div class="stat-value"><?php echo format_currency((float) $revenue['total']); ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-receipt"></i></div>
            <div>
                <div class="stat-label">Orders</div>
                <div class="stat-value"><?php echo (int) $orders['count']; ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-bag-check"></i></div>
            <div>
                <div class="stat-label">Products</div>
                <div class="stat-value"><?php echo (int) $products['count']; ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-label">Customers</div>
                <div class="stat-value"><?php echo (int) $customers['count']; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <div>
                    <h5>Recent Orders</h5>
                    <p>Latest 5 orders placed on the storefront</p>
                </div>
                <a href="orders.php" class="btn-admin-outline btn-admin-sm">View all</a>
            </div>
            <div class="admin-table-wrap">
                <?php if (!empty($recentOrders)): ?>
                    <table class="admin-table">
                        <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td class="row-title">#<?php echo (int) $order['id']; ?></td>
                                <td><?php echo e($order['customer_name']); ?></td>
                                <td><?php echo format_currency((float) $order['total']); ?></td>
                                <td><span class="status-pill status-<?php echo e($order['status']); ?>"><?php echo e($order['status']); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="admin-empty"><i class="bi bi-receipt"></i><h6>No orders yet</h6></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <div>
                    <h5>New Products</h5>
                    <p>Most recently added to the catalog</p>
                </div>
                <a href="products.php" class="btn-admin-outline btn-admin-sm">Manage</a>
            </div>
            <div class="admin-card-body pt-3">
                <?php if (!empty($recentProducts)): ?>
                    <?php foreach ($recentProducts as $product): ?>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img class="row-thumb" src="<?php echo e(!empty($product['image_url']) ? $product['image_url'] : 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=100&q=60'); ?>" alt="<?php echo e($product['name']); ?>">
                            <div class="flex-grow-1">
                                <div class="row-title"><?php echo e($product['name']); ?></div>
                                <div class="row-subtle"><?php echo (int) $product['stock_quantity']; ?> in stock</div>
                            </div>
                            <div class="fw-bold small"><?php echo format_currency((float) ($product['sale_price'] > 0 ? $product['sale_price'] : $product['regular_price'])); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="admin-empty"><i class="bi bi-bag"></i><h6>No products yet</h6></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
