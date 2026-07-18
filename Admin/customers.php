<?php
/**
 * Customer management screen in the admin panel.
 */
$pageTitle = 'Customers';
$pageSubtitle = 'Everyone who has created a storefront account.';
$activeNav = 'customers';
require_once __DIR__ . '/Includes/header.php';

$customers = db()->query('SELECT id, full_name, email, created_at FROM users WHERE role = "customer" ORDER BY created_at DESC LIMIT 20')->fetchAll();
?>
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h5>All Customers</h5>
            <p><?php echo count($customers); ?> most recently joined</p>
        </div>
    </div>
    <div class="admin-table-wrap">
        <?php if (!empty($customers)): ?>
            <table class="admin-table">
                <thead><tr><th>Customer</th><th>Email</th><th>Joined</th></tr></thead>
                <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="admin-avatar"><?php echo e(strtoupper(substr($customer['full_name'], 0, 1))); ?></div>
                                <div class="row-title"><?php echo e($customer['full_name']); ?></div>
                            </div>
                        </td>
                        <td><?php echo e($customer['email']); ?></td>
                        <td class="row-subtle"><?php echo e(date('M j, Y', strtotime($customer['created_at']))); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="admin-empty"><i class="bi bi-people"></i><h6>No customers yet</h6><p>New storefront sign-ups will appear here.</p></div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
