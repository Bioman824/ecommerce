<?php
/**
 * Shared chrome for every admin screen: auth guard, sidebar and topbar.
 * Pages set $pageTitle / $activeNav / $pageSubtitle before requiring this.
 */
require_once __DIR__ . '/../../Includes/functions.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    redirect(BASE_URL . 'Admin/login.php');
}

$pageTitle = $pageTitle ?? 'Dashboard';
$pageSubtitle = $pageSubtitle ?? '';
$activeNav = $activeNav ?? '';
$adminName = $_SESSION['user_name'] ?? 'Administrator';

$navGroups = [
    'Overview' => [
        'dashboard' => ['label' => 'Dashboard', 'href' => 'index.php', 'icon' => 'bi-grid-1x2-fill'],
        'analytics' => ['label' => 'Analytics', 'href' => 'analytics.php', 'icon' => 'bi-graph-up-arrow'],
    ],
    'Catalog' => [
        'products' => ['label' => 'Products', 'href' => 'products.php', 'icon' => 'bi-bag-fill'],
        'media' => ['label' => 'Media Library', 'href' => 'media.php', 'icon' => 'bi-images'],
    ],
    'Sales' => [
        'orders' => ['label' => 'Orders', 'href' => 'orders.php', 'icon' => 'bi-receipt'],
        'customers' => ['label' => 'Customers', 'href' => 'customers.php', 'icon' => 'bi-people-fill'],
    ],
    'Configuration' => [
        'payment' => ['label' => 'Payment Gateways', 'href' => 'payment-gateways.php', 'icon' => 'bi-credit-card-2-front-fill'],
        'settings' => ['label' => 'Settings', 'href' => 'settings.php', 'icon' => 'bi-gear-fill'],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - Spotlight Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo ASSET_URL; ?>css/admin.css">
</head>
<body>
<div class="admin-shell">
    <div class="admin-overlay" id="adminOverlay"></div>
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-brand">
            <i class="bi bi-lightning-charge-fill"></i>
            <span>Spotlight Admin</span>
        </div>
        <nav class="admin-nav">
            <?php foreach ($navGroups as $groupLabel => $items): ?>
                <div class="admin-nav-group">
                    <p class="admin-nav-label"><?php echo e($groupLabel); ?></p>
                    <?php foreach ($items as $key => $item): ?>
                        <a href="<?php echo e($item['href']); ?>" class="admin-nav-link <?php echo $activeNav === $key ? 'active' : ''; ?>">
                            <i class="bi <?php echo e($item['icon']); ?>"></i>
                            <span><?php echo e($item['label']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </nav>
        <div class="admin-sidebar-footer">
            <a href="<?php echo BASE_URL; ?>" class="admin-nav-link"><i class="bi bi-shop"></i><span>View Storefront</span></a>
            <a href="<?php echo BASE_URL; ?>logout.php" class="admin-nav-link text-danger-subtle"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
        </div>
    </aside>
    <div class="admin-main">
        <header class="admin-topbar">
            <button class="admin-sidebar-toggle" type="button" id="adminSidebarToggle" aria-label="Toggle menu">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <h1 class="admin-page-title"><?php echo e($pageTitle); ?></h1>
                <?php if ($pageSubtitle !== ''): ?><p class="admin-page-subtitle"><?php echo e($pageSubtitle); ?></p><?php endif; ?>
            </div>
            <div class="admin-topbar-right">
                <div class="admin-avatar"><?php echo e(strtoupper(substr($adminName, 0, 1))); ?></div>
                <div class="d-none d-sm-block">
                    <div class="admin-topbar-name"><?php echo e($adminName); ?></div>
                    <div class="admin-topbar-role">Administrator</div>
                </div>
            </div>
        </header>
        <main class="admin-content">
