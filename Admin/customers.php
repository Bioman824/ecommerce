<?php
/**
 * Customer management screen in the admin panel.
 */
require_once __DIR__ . '/../Includes/functions.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    redirect(BASE_URL . 'Admin/login.php');
}

$customers = db()->query('SELECT id, full_name, email, created_at FROM users WHERE role = "customer" ORDER BY created_at DESC LIMIT 20')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-4">
    <h2 class="fw-bold mb-4">Customers</h2>
    <div class="card p-4">
        <table class="table">
            <thead><tr><th>Name</th><th>Email</th><th>Joined</th></tr></thead>
            <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?php echo e($customer['full_name']); ?></td>
                    <td><?php echo e($customer['email']); ?></td>
                    <td><?php echo e($customer['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
