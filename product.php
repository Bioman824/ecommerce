<?php
/**
 * Product detail page with schema-friendly output and purchase actions.
 */
require_once __DIR__ . '/Includes/header.php';

$slug = sanitize($_GET['slug'] ?? '');
$stmt = db()->prepare('SELECT * FROM products WHERE slug = ? AND status = "active" LIMIT 1');
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    echo '<section class="container py-5"><h1>Product not found.</h1></section>';
    require_once __DIR__ . '/Includes/footer.php';
    exit;
}

$relatedStmt = db()->prepare('SELECT id, name, slug, regular_price, sale_price, short_description, image_url FROM products WHERE status = "active" AND id != ? ORDER BY created_at DESC LIMIT 3');
$relatedStmt->execute([(int) $product['id']]);
$relatedProducts = $relatedStmt->fetchAll();

$reviewStmt = db()->prepare('SELECT r.rating, r.review_text, u.full_name FROM reviews r LEFT JOIN users u ON u.id = r.user_id WHERE r.product_id = ? AND r.status = "approved" ORDER BY r.created_at DESC LIMIT 10');
$reviewStmt->execute([(int) $product['id']]);
$reviews = $reviewStmt->fetchAll();
?>
<section class="container py-5">
    <div class="row g-5 align-items-start">
        <div class="col-lg-6">
            <div class="hero-card">
                <img src="<?php echo e($product['image_url'] ?? 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=80'); ?>" alt="<?php echo e($product['name']); ?>" style="height: 500px;">
            </div>
        </div>
        <div class="col-lg-6">
            <p class="text-accent fw-semibold"><?php echo e($product['brand'] ?? 'Spotlight'); ?></p>
            <h1 class="fw-bold mb-3"><?php echo e($product['name']); ?></h1>
            <p class="text-muted lead"><?php echo e($product['short_description'] ?? 'Premium fashion essentials'); ?></p>
            <div class="d-flex align-items-center gap-3 mb-4">
                <h3 class="fw-bold mb-0"><?php echo format_currency((float) ($product['sale_price'] > 0 ? $product['sale_price'] : $product['regular_price'])); ?></h3>
                <?php if ((float) $product['sale_price'] > 0): ?><span class="text-muted text-decoration-line-through"><?php echo format_currency((float) $product['regular_price']); ?></span><?php endif; ?>
            </div>
            <p><?php echo e($product['description'] ?? ''); ?></p>
            <div class="d-flex gap-3 mt-4">
                <button class="btn btn-accent add-to-cart" data-product-id="<?php echo (int) $product['id']; ?>">Add to Cart</button>
                <form method="post" action="<?php echo BASE_URL; ?>User/wishlist.php" class="d-inline">
                    <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline-dark" type="submit">Add to Wishlist</button>
                </form>
                <a class="btn btn-outline-dark" href="cart.php">View Cart</a>
            </div>
            <div class="mt-4 text-muted small">SKU: <?php echo e($product['sku'] ?? 'N/A'); ?> • Stock: <?php echo (int) $product['stock_quantity']; ?></div>
        </div>
    </div>
</section>

<section class="container py-5">
    <h3 class="fw-bold mb-4">Reviews</h3>
    <?php if (!empty($reviews)): ?>
        <div class="row g-3">
            <?php foreach ($reviews as $review): ?>
                <div class="col-md-6">
                    <div class="section-card p-4">
                        <div class="fw-bold"><?php echo e($review['full_name'] ?? 'Customer'); ?></div>
                        <div class="text-accent small">Rating: <?php echo (int) $review['rating']; ?>/5</div>
                        <p class="text-muted mt-2"><?php echo e($review['review_text']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">No reviews yet. Be the first to share your experience.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="section-card p-4 mt-4">
            <h5 class="fw-bold">Leave a review</h5>
            <form id="review-form" data-product-id="<?php echo (int) $product['id']; ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Rating</label>
                    <select class="form-select" name="rating">
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" name="review_text" rows="4" placeholder="Share your experience"></textarea>
                </div>
                <button class="btn btn-accent" type="submit">Submit review</button>
            </form>
        </div>
    <?php endif; ?>
</section>

<section class="container py-5">
    <h3 class="fw-bold mb-4">Related products</h3>
    <div class="row g-4">
        <?php foreach ($relatedProducts as $related): ?>
            <div class="col-md-4">
                <article class="product-card h-100">
                    <img class="product-image" src="<?php echo e($related['image_url'] ?? 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80'); ?>" alt="<?php echo e($related['name']); ?>">
                    <div class="p-4">
                        <h5 class="fw-bold"><?php echo e($related['name']); ?></h5>
                        <p class="text-muted small"><?php echo e($related['short_description'] ?? 'Premium fashion essentials'); ?></p>
                        <a class="btn btn-outline-dark btn-sm" href="product.php?slug=<?php echo e($related['slug']); ?>">View</a>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
