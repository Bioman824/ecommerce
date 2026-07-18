<?php
/**
 * Product listing page with filtering and search support.
 */
require_once __DIR__ . '/Includes/header.php';

$query = $_GET['q'] ?? '';
$categorySlug = $_GET['category'] ?? '';
$search = sanitize($query);

$sql = 'SELECT p.id, p.name, p.slug, p.short_description, p.regular_price, p.sale_price, p.image_url, c.name as category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE p.status = "active"';
$params = [];

if ($search !== '') {
    $sql .= ' AND (p.name LIKE ? OR p.sku LIKE ? OR p.short_description LIKE ?)';
    $like = '%' . $search . '%';
    $params = [$like, $like, $like];
}

if ($categorySlug !== '') {
    $sql .= ' AND c.slug = ?';
    $params[] = $categorySlug;
}

$sql .= ' ORDER BY p.created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<section class="container py-5">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="section-card p-4">
                <h5 class="fw-bold">Search & Filter</h5>
                <form method="get" class="mt-3">
                    <input class="form-control mb-3" type="search" name="q" value="<?php echo e($search); ?>" placeholder="Search products">
                    <select class="form-select mb-3" name="category">
                        <option value="">All Categories</option>
                        <?php foreach (get_categories() as $category): ?>
                            <option value="<?php echo e($category['slug']); ?>" <?php echo $categorySlug === $category['slug'] ? 'selected' : ''; ?>><?php echo e($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-accent w-100" type="submit">Apply</button>
                </form>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="text-accent fw-semibold small mb-1">Shop</p>
                    <h2 class="fw-bold">Curated fashion for every day</h2>
                </div>
                <span class="text-muted"><?php echo count($products); ?> results</span>
            </div>
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="product-card h-100">
                            <img class="product-image" src="<?php echo e($product['image_url'] ?? 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=80'); ?>" alt="<?php echo e($product['name']); ?>">
                            <div class="p-4">
                                <h5 class="fw-bold mb-2"><?php echo e($product['name']); ?></h5>
                                <p class="text-muted small"><?php echo e($product['short_description'] ?? 'Premium fashion essentials'); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong><?php echo format_currency((float) ($product['sale_price'] > 0 ? $product['sale_price'] : $product['regular_price'])); ?></strong>
                                    <a class="btn btn-outline-dark btn-sm" href="product.php?slug=<?php echo e($product['slug']); ?>">View</a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
