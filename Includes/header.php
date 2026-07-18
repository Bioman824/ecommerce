<?php
require_once __DIR__ . '/functions.php';
$cartCount = get_cart_count();
$siteName = get_setting('site_name', SITE_NAME);
$siteTagline = get_setting('site_tagline', 'Premium fashion for modern living.');
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$navLinks = [
    'shop.php' => 'Shop',
    'index.php#collections' => 'Collections',
    'index.php#blog' => 'Journal',
    'index.php#contact' => 'Contact',
];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($siteName); ?> - Premium Fashion</title>
    <meta name="description" content="Premium clothing, curated fashion drops, fast delivery and elegant shopping experience for modern wardrobes.">
    <meta name="robots" content="index, follow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo ASSET_URL; ?>css/style.css">
</head>
<body>
<div class="announce-bar">
    <div class="container d-flex justify-content-center justify-content-md-between align-items-center flex-wrap gap-1 py-2">
        <span class="d-none d-md-inline"><i class="bi bi-truck me-1"></i>Free shipping on orders over $75</span>
        <span><i class="bi bi-arrow-repeat me-1"></i>Easy 30-day returns</span>
        <span class="d-none d-md-inline"><i class="bi bi-shield-check me-1"></i>Secure checkout</span>
    </div>
</div>
<header class="topbar">
    <div class="container py-3 d-flex justify-content-between align-items-center gap-3">
        <button class="btn nav-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-label="Open menu">
            <i class="bi bi-list"></i>
        </button>
        <a class="brand" href="<?php echo BASE_URL; ?>">
            <?php echo e($siteName); ?>
        </a>
        <nav class="d-none d-lg-flex gap-4 align-items-center main-nav">
            <?php foreach ($navLinks as $href => $label): ?>
                <a href="<?php echo BASE_URL . $href; ?>" class="<?php echo (strpos($href, '#') === false && $currentPage === $href) ? 'active' : ''; ?>"><?php echo e($label); ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="d-flex align-items-center gap-1 gap-md-2">
            <form class="search-form d-none d-md-flex" action="<?php echo BASE_URL; ?>shop.php" method="get" role="search">
                <input class="form-control" type="search" name="q" placeholder="Search products…" aria-label="Search products">
                <button type="submit" aria-label="Search"><i class="bi bi-search"></i></button>
            </form>
            <a class="icon-btn d-md-none" href="<?php echo BASE_URL; ?>shop.php" aria-label="Search" title="Search"><i class="bi bi-search"></i></a>
            <a class="icon-btn d-none d-sm-inline-flex" href="<?php echo BASE_URL; ?>User/wishlist.php" aria-label="Wishlist" title="Wishlist"><i class="bi bi-heart"></i></a>
            <a class="icon-btn" href="<?php echo isset($_SESSION['user_id']) ? BASE_URL . 'User/profile.php' : BASE_URL . 'login.php'; ?>" aria-label="Account" title="Account">
                <i class="bi bi-person"></i>
                <span class="d-none d-lg-inline ms-1 small fw-semibold"><?php echo isset($_SESSION['user_name']) ? e(explode(' ', $_SESSION['user_name'])[0]) : 'Account'; ?></span>
            </a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="icon-btn d-none d-sm-inline-flex" href="<?php echo BASE_URL; ?>logout.php" aria-label="Logout" title="Logout"><i class="bi bi-box-arrow-right"></i></a>
            <?php endif; ?>
            <a class="icon-btn cart-btn" href="<?php echo BASE_URL; ?>cart.php" aria-label="Cart">
                <i class="bi bi-bag"></i>
                <span class="cart-count" data-cart-count><?php echo (int) $cartCount; ?></span>
            </a>
        </div>
    </div>
</header>

<div class="offcanvas offcanvas-start mobile-nav" tabindex="-1" id="mobileNav" aria-labelledby="mobileNavLabel">
    <div class="offcanvas-header">
        <span class="brand" id="mobileNavLabel"><?php echo e($siteName); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <form class="search-form mb-4" action="<?php echo BASE_URL; ?>shop.php" method="get" role="search">
            <input class="form-control" type="search" name="q" placeholder="Search products…" aria-label="Search products">
            <button type="submit" aria-label="Search"><i class="bi bi-search"></i></button>
        </form>
        <nav class="d-flex flex-column gap-1 mobile-nav-links">
            <?php foreach ($navLinks as $href => $label): ?>
                <a href="<?php echo BASE_URL . $href; ?>"><?php echo e($label); ?></a>
            <?php endforeach; ?>
        </nav>
        <hr>
        <nav class="d-flex flex-column gap-1 mobile-nav-links">
            <a href="<?php echo BASE_URL; ?>User/wishlist.php"><i class="bi bi-heart me-2"></i>Wishlist</a>
            <a href="<?php echo isset($_SESSION['user_id']) ? BASE_URL . 'User/profile.php' : BASE_URL . 'login.php'; ?>"><i class="bi bi-person me-2"></i><?php echo isset($_SESSION['user_name']) ? 'My Account' : 'Login / Register'; ?></a>
            <a href="<?php echo BASE_URL; ?>cart.php"><i class="bi bi-bag me-2"></i>Cart (<?php echo (int) $cartCount; ?>)</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</div>

<main class="pb-5">
