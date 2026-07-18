<?php
/**
 * Admin dashboard overview.
 */
require_once __DIR__ . '/../Includes/functions.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    redirect(BASE_URL . 'Admin/login.php');
}

$ordersStmt = db()->query('SELECT COUNT(*) as count FROM orders');
$orders = $ordersStmt->fetch();
$productsStmt = db()->query('SELECT COUNT(*) as count FROM products');
$products = $productsStmt->fetch();
$customersStmt = db()->query('SELECT COUNT(*) as count FROM users WHERE role = "customer"');
$customers = $customersStmt->fetch();
$revenueStmt = db()->query('SELECT COALESCE(SUM(total),0) as total FROM orders');
$revenue = $revenueStmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Spotlight Fashion Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #f7f7fb; }
        .sidebar { min-height: 100vh; background: #111; color: white; }
        .card { border-radius: 1rem; border: 0; box-shadow: 0 15px 30px rgba(0,0,0,0.06); }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <aside class="col-lg-2 sidebar p-4">
            <h4 class="fw-bold mb-4">Spotlight Admin</h4>
            <ul class="nav flex-column gap-2">
                <li><a class="text-white-50" href="index.php">Dashboard</a></li>
                <li><a class="text-white-50" href="products.php">Products</a></li>
                <li><a class="text-white-50" href="orders.php">Orders</a></li>
                <li><a class="text-white-50" href="customers.php">Customers</a></li>
                <li><a class="text-white-50" href="analytics.php">Analytics</a></li>
                <li><a class="text-white-50" href="media.php">Media</a></li>
                <li><a class="text-white-50" href="settings.php">Settings</a></li>
                <li class="mt-3 pt-3 border-top border-secondary"><a class="text-white-50" href="<?php echo BASE_URL; ?>logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
            </ul>
        </aside>
        <main class="col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold">Dashboard</h2>
                    <p class="text-muted">Monitor sales, orders and product performance.</p>
                </div>
                <a class="btn btn-dark" href="../index.php">View Storefront</a>
            </div>
            <div class="row g-4">
                <div class="col-md-3"><div class="card p-4"><h6 class="text-muted">Revenue</h6><h3 class="fw-bold"><?php echo format_currency((float) $revenue['total']); ?></h3></div></div>
                <div class="col-md-3"><div class="card p-4"><h6 class="text-muted">Orders</h6><h3 class="fw-bold"><?php echo (int) $orders['count']; ?></h3></div></div>
                <div class="col-md-3"><div class="card p-4"><h6 class="text-muted">Products</h6><h3 class="fw-bold"><?php echo (int) $products['count']; ?></h3></div></div>
                <div class="col-md-3"><div class="card p-4"><h6 class="text-muted">Customers</h6><h3 class="fw-bold"><?php echo (int) $customers['count']; ?></h3></div></div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
