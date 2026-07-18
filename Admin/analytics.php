<?php
/**
 * Analytics reporting page for the admin dashboard.
 */
require_once __DIR__ . '/../Includes/functions.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    redirect(BASE_URL . 'Admin/login.php');
}

$revenueStmt = db()->query('SELECT COALESCE(SUM(total), 0) as revenue FROM orders');
$ordersStmt = db()->query('SELECT COUNT(*) as count FROM orders');
$customersStmt = db()->query('SELECT COUNT(*) as count FROM users WHERE role = "customer"');
$productsStmt = db()->query('SELECT COUNT(*) as count FROM products');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-4">
    <h2 class="fw-bold mb-4">Analytics</h2>
    <div class="row g-4">
        <div class="col-md-3"><div class="card p-4"><h6 class="text-muted">Revenue</h6><h3 class="fw-bold"><?php echo format_currency((float) $revenueStmt->fetch()['revenue']); ?></h3></div></div>
        <div class="col-md-3"><div class="card p-4"><h6 class="text-muted">Orders</h6><h3 class="fw-bold"><?php echo (int) $ordersStmt->fetch()['count']; ?></h3></div></div>
        <div class="col-md-3"><div class="card p-4"><h6 class="text-muted">Customers</h6><h3 class="fw-bold"><?php echo (int) $customersStmt->fetch()['count']; ?></h3></div></div>
        <div class="col-md-3"><div class="card p-4"><h6 class="text-muted">Products</h6><h3 class="fw-bold"><?php echo (int) $productsStmt->fetch()['count']; ?></h3></div></div>
    </div>
</div>
</body>
</html>
