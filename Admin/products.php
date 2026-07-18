<?php
/**
 * Product management interface in the admin panel.
 */
require_once __DIR__ . '/../Includes/functions.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    redirect(BASE_URL . 'Admin/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = sanitize($_POST['name'] ?? '');
    $slug = slugify($_POST['slug'] ?? $name);
    $price = (float) ($_POST['regular_price'] ?? 0);
    $salePrice = (float) ($_POST['sale_price'] ?? 0);
    $stock = (int) ($_POST['stock_quantity'] ?? 0);

    $stmt = db()->prepare('INSERT INTO products (name, slug, short_description, regular_price, sale_price, stock_quantity, status, image_url) VALUES (?, ?, ?, ?, ?, ?, "active", ?)');
    $stmt->execute([$name, $slug, sanitize($_POST['short_description'] ?? ''), $price, $salePrice, $stock, sanitize($_POST['image_url'] ?? '')]);
    $successMessage = 'Product added.';
}

$products = db()->query('SELECT id, name, slug, regular_price, sale_price, stock_quantity FROM products ORDER BY created_at DESC LIMIT 20')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-4">
    <h2 class="fw-bold mb-4">Products</h2>
    <?php if (!empty($successMessage)): ?><div class="alert alert-success"><?php echo e($successMessage); ?></div><?php endif; ?>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card p-4">
                <h5 class="fw-bold">Add Product</h5>
                <form method="post">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3"><input class="form-control" name="name" placeholder="Product Name" required></div>
                    <div class="mb-3"><input class="form-control" name="slug" placeholder="slug"></div>
                    <div class="mb-3"><input class="form-control" name="short_description" placeholder="Short Description"></div>
                    <div class="mb-3"><input class="form-control" type="number" step="0.01" name="regular_price" placeholder="Regular Price"></div>
                    <div class="mb-3"><input class="form-control" type="number" step="0.01" name="sale_price" placeholder="Sale Price"></div>
                    <div class="mb-3"><input class="form-control" type="number" name="stock_quantity" placeholder="Stock"></div>
                    <div class="mb-3"><input class="form-control" name="image_url" placeholder="Image URL"></div>
                    <button class="btn btn-dark" type="submit">Save Product</button>
                </form>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card p-4">
                <h5 class="fw-bold">Existing Products</h5>
                <table class="table">
                    <thead><tr><th>Name</th><th>Slug</th><th>Price</th><th>Stock</th></tr></thead>
                    <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo e($product['name']); ?></td>
                            <td><?php echo e($product['slug']); ?></td>
                            <td><?php echo format_currency((float) $product['sale_price'] ?: (float) $product['regular_price']); ?></td>
                            <td><?php echo (int) $product['stock_quantity']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
