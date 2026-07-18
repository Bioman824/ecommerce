<?php
/**
 * Product management interface in the admin panel.
 */
$pageTitle = 'Products';
$pageSubtitle = 'Add new products and review your current catalog.';
$activeNav = 'products';
require_once __DIR__ . '/Includes/header.php';

$successMessage = '';
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

$products = db()->query('SELECT id, name, slug, regular_price, sale_price, stock_quantity, image_url FROM products ORDER BY created_at DESC LIMIT 20')->fetchAll();
?>
<?php if ($successMessage !== ''): ?><div class="admin-alert"><i class="bi bi-check-circle-fill me-1"></i><?php echo e($successMessage); ?></div><?php endif; ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h5>Add Product</h5>
                    <p>Publish a new item to the storefront</p>
                </div>
            </div>
            <div class="admin-card-body">
                <form method="post">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Product name</label>
                        <input class="form-control" name="name" placeholder="e.g. Linen Tailored Blazer" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input class="form-control" name="slug" placeholder="auto-generated if left blank">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Short description</label>
                        <input class="form-control" name="short_description" placeholder="One line summary">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Regular price</label>
                            <input class="form-control" type="number" step="0.01" min="0" name="regular_price" placeholder="0.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Sale price</label>
                            <input class="form-control" type="number" step="0.01" min="0" name="sale_price" placeholder="0.00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock quantity</label>
                        <input class="form-control" type="number" min="0" name="stock_quantity" placeholder="0">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Image URL</label>
                        <input class="form-control" name="image_url" placeholder="https://...">
                    </div>
                    <button class="btn-admin-accent w-100" type="submit"><i class="bi bi-plus-lg me-1"></i>Save Product</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h5>Existing Products</h5>
                    <p><?php echo count($products); ?> most recently added</p>
                </div>
            </div>
            <div class="admin-table-wrap">
                <?php if (!empty($products)): ?>
                    <table class="admin-table">
                        <thead><tr><th>Product</th><th>Price</th><th>Stock</th></tr></thead>
                        <tbody>
                        <?php foreach ($products as $product):
                            $price = (float) ($product['sale_price'] > 0 ? $product['sale_price'] : $product['regular_price']);
                            $stock = (int) $product['stock_quantity'];
                            if ($stock <= 0) { $stockClass = 'status-out-of-stock'; $stockLabel = 'Out of stock'; }
                            elseif ($stock <= 5) { $stockClass = 'status-low-stock'; $stockLabel = 'Low stock'; }
                            else { $stockClass = 'status-in-stock'; $stockLabel = 'In stock'; }
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img class="row-thumb" src="<?php echo e($product['image_url'] ?? 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=100&q=60'); ?>" alt="<?php echo e($product['name']); ?>">
                                        <div>
                                            <div class="row-title"><?php echo e($product['name']); ?></div>
                                            <div class="row-subtle"><?php echo e($product['slug']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php echo format_currency($price); ?>
                                    <?php if ((float) $product['sale_price'] > 0): ?><div class="row-subtle text-decoration-line-through"><?php echo format_currency((float) $product['regular_price']); ?></div><?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-pill <?php echo $stockClass; ?>"><?php echo $stockLabel; ?></span>
                                    <div class="row-subtle mt-1"><?php echo $stock; ?> units</div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="admin-empty"><i class="bi bi-bag"></i><h6>No products yet</h6><p>Add your first product using the form on the left.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
