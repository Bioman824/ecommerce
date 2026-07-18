<?php
/**
 * Order management screen in the admin panel.
 */
require_once __DIR__ . '/../Includes/functions.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    redirect(BASE_URL . 'Admin/login.php');
}

$orders = db()->query('SELECT id, customer_name, customer_email, total, status, created_at FROM orders ORDER BY created_at DESC LIMIT 20')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-4">
    <h2 class="fw-bold mb-4">Orders</h2>
    <div class="card p-4">
        <table class="table">
            <thead><tr><th>#</th><th>Customer</th><th>Email</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo (int) $order['id']; ?></td>
                    <td><?php echo e($order['customer_name']); ?></td>
                    <td><?php echo e($order['customer_email']); ?></td>
                    <td><?php echo format_currency((float) $order['total']); ?></td>
                    <td><?php echo e($order['status']); ?></td>
                    <td><?php echo e($order['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
