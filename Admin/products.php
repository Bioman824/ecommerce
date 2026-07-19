<?php
/**
 * Product management interface in the admin panel.
 */
$pageTitle = 'Products';
$pageSubtitle = 'Add, edit and remove items in your catalog.';
$activeNav = 'products';
require_once __DIR__ . '/Includes/header.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product_id'])) {
    verify_csrf();
    $deleteId = (int) $_POST['delete_product_id'];
    $stmt = db()->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$deleteId]);
    $successMessage = 'Product deleted.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $name = sanitize($_POST['name'] ?? '');
        $slugInput = sanitize($_POST['slug'] ?? '');
        $slug = slugify($slugInput !== '' ? $slugInput : $name);
        $shortDescription = sanitize($_POST['short_description'] ?? '');
        $price = (float) ($_POST['regular_price'] ?? 0);
        $salePrice = (float) ($_POST['sale_price'] ?? 0);
        $stock = (int) ($_POST['stock_quantity'] ?? 0);
        $imageUrl = handle_image_upload('image_file') ?? sanitize($_POST['image_url'] ?? '');

        if ($productId > 0) {
            $stmt = db()->prepare('UPDATE products SET name = ?, slug = ?, short_description = ?, regular_price = ?, sale_price = ?, stock_quantity = ?, image_url = ? WHERE id = ?');
            $stmt->execute([$name, $slug, $shortDescription, $price, $salePrice, $stock, $imageUrl, $productId]);
            $successMessage = 'Product updated.';
        } else {
            $stmt = db()->prepare('INSERT INTO products (name, slug, short_description, regular_price, sale_price, stock_quantity, status, image_url) VALUES (?, ?, ?, ?, ?, ?, "active", ?)');
            $stmt->execute([$name, $slug, $shortDescription, $price, $salePrice, $stock, $imageUrl]);
            $successMessage = 'Product added.';
        }
    } catch (RuntimeException $exception) {
        $errorMessage = $exception->getMessage();
    }
}

$editProduct = null;
if (isset($_GET['edit'])) {
    $stmt = db()->prepare('SELECT id, name, slug, short_description, regular_price, sale_price, stock_quantity, image_url FROM products WHERE id = ? LIMIT 1');
    $stmt->execute([(int) $_GET['edit']]);
    $editProduct = $stmt->fetch() ?: null;
}

$products = db()->query('SELECT id, name, slug, regular_price, sale_price, stock_quantity, image_url FROM products ORDER BY created_at DESC LIMIT 20')->fetchAll();
?>
<?php if ($successMessage !== ''): ?><div class="admin-alert"><i class="bi bi-check-circle-fill me-1"></i><?php echo e($successMessage); ?></div><?php endif; ?>
<?php if ($errorMessage !== ''): ?><div class="admin-alert admin-alert-error"><i class="bi bi-exclamation-triangle-fill me-1"></i><?php echo e($errorMessage); ?></div><?php endif; ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h5><?php echo $editProduct ? 'Edit Product' : 'Add Product'; ?></h5>
                    <p><?php echo $editProduct ? 'Update "' . e($editProduct['name']) . '"' : 'Publish a new item to the storefront'; ?></p>
                </div>
                <?php if ($editProduct): ?><a href="products.php" class="btn-admin-outline btn-admin-sm">Cancel</a><?php endif; ?>
            </div>
            <div class="admin-card-body">
                <form method="post" action="products.php" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo $editProduct ? (int) $editProduct['id'] : 0; ?>">
                    <div class="mb-3">
                        <label class="form-label">Product name</label>
                        <input class="form-control" name="name" placeholder="e.g. Linen Tailored Blazer" value="<?php echo e($editProduct['name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input class="form-control" name="slug" placeholder="auto-generated if left blank" value="<?php echo e($editProduct['slug'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Short description</label>
                        <input class="form-control" name="short_description" placeholder="One line summary" value="<?php echo e($editProduct['short_description'] ?? ''); ?>">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Regular price</label>
                            <input class="form-control" type="number" step="0.01" min="0" name="regular_price" placeholder="0.00" value="<?php echo e($editProduct['regular_price'] ?? ''); ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Sale price</label>
                            <input class="form-control" type="number" step="0.01" min="0" name="sale_price" placeholder="0.00" value="<?php echo e($editProduct['sale_price'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock quantity</label>
                        <input class="form-control" type="number" min="0" name="stock_quantity" placeholder="0" value="<?php echo e($editProduct['stock_quantity'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product image</label>
                        <input class="form-control" type="file" name="image_file" accept="image/png,image/jpeg,image/webp,image/gif">
                        <div class="form-text">Upload from your computer (JPG, PNG, WEBP or GIF, up to 5MB).</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">or Image URL</label>
                        <input class="form-control" name="image_url" placeholder="https://... (used if no file is uploaded)" value="<?php echo e($editProduct['image_url'] ?? ''); ?>">
                        <?php if ($editProduct): ?><div class="form-text">Leave the file field empty to keep the current image.</div><?php endif; ?>
                    </div>
                    <button class="btn-admin-accent w-100" type="submit">
                        <i class="bi <?php echo $editProduct ? 'bi-check-lg' : 'bi-plus-lg'; ?> me-1"></i>
                        <?php echo $editProduct ? 'Update Product' : 'Save Product'; ?>
                    </button>
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
                        <thead><tr><th>Product</th><th>Price</th><th>Stock</th><th></th></tr></thead>
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
                                        <img class="row-thumb" src="<?php echo e(!empty($product['image_url']) ? $product['image_url'] : 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=100&q=60'); ?>" alt="<?php echo e($product['name']); ?>">
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
                                <td>
                                    <div class="row-actions">
                                        <a class="row-action-btn" href="products.php?edit=<?php echo (int) $product['id']; ?>" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <form method="post" action="products.php" data-confirm="Delete &quot;<?php echo e($product['name']); ?>&quot;? This cannot be undone.">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="delete_product_id" value="<?php echo (int) $product['id']; ?>">
                                            <button type="submit" class="row-action-btn danger" title="Delete"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </div>
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
