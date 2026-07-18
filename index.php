<?php
/**
 * Homepage for Spotlight Fashion Store.
 */
require_once __DIR__ . '/Includes/header.php';

$categories = get_categories();
$featuredProducts = get_featured_products(6);
$latestProducts = get_latest_products(8);
$testimonials = get_testimonials();
$faqs = get_faqs();
$blogPosts = get_blog_posts(3);

$allProductIds = array_merge(array_column($featuredProducts, 'id'), array_column($latestProducts, 'id'));
$ratings = get_product_ratings($allProductIds);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = db()->prepare('INSERT INTO newsletters (email) VALUES (?) ON DUPLICATE KEY UPDATE email = VALUES(email)');
            $stmt->execute([$email]);
            $message = 'You are now subscribed.';
        } catch (Throwable $exception) {
            $message = 'Subscription saved.';
        }
    }
}

$categoryImages = [
    'https://images.unsplash.com/photo-1490114538077-0a7f8cb49891?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1495121605193-b116b5b9c5fe?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=700&q=80',
];

/**
 * Renders a product card so featured/latest sections stay in sync.
 */
function render_product_card(array $product, array $ratings): void {
    $sale = (float) $product['sale_price'];
    $regular = (float) $product['regular_price'];
    $price = $sale > 0 ? $sale : $regular;
    $discount = $sale > 0 && $regular > $sale ? round((($regular - $sale) / $regular) * 100) : 0;
    $rating = $ratings[(int) $product['id']] ?? null;
    $image = $product['image_url'] ?? 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=900&q=80';
    ?>
    <article class="product-card h-100">
        <div class="product-media">
            <a href="product.php?slug=<?php echo e($product['slug']); ?>">
                <img class="product-image" src="<?php echo e($image); ?>" alt="<?php echo e($product['name']); ?>" loading="lazy">
            </a>
            <div class="product-badges">
                <?php if ($discount > 0): ?><span class="badge-pill badge-sale">-<?php echo (int) $discount; ?>%</span><?php endif; ?>
                <?php if (!empty($product['is_trending'])): ?><span class="badge-pill badge-trending">Trending</span><?php endif; ?>
            </div>
            <form class="wishlist-toggle-form" method="post" action="<?php echo (isset($_SESSION['user_id']) ? '' : 'login.php'); ?>">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                    <?php echo csrf_field(); ?>
                    <button class="wishlist-toggle" type="submit" formaction="User/wishlist.php" aria-label="Add to wishlist" title="Add to wishlist">
                        <i class="bi bi-heart"></i>
                    </button>
                <?php else: ?>
                    <a class="wishlist-toggle" href="login.php" aria-label="Login to add to wishlist" title="Login to add to wishlist">
                        <i class="bi bi-heart"></i>
                    </a>
                <?php endif; ?>
            </form>
            <button class="btn btn-dark quick-add add-to-cart" data-product-id="<?php echo (int) $product['id']; ?>">
                <i class="bi bi-bag-plus me-1"></i>Quick Add
            </button>
        </div>
        <div class="p-3 p-lg-4">
            <div class="product-rating mb-1">
                <?php if ($rating): ?>
                    <span class="stars" style="--rating: <?php echo (float) $rating['avg']; ?>" aria-hidden="true"></span>
                    <span class="text-muted small">(<?php echo (int) $rating['count']; ?>)</span>
                <?php else: ?>
                    <span class="stars" style="--rating: 5" aria-hidden="true"></span>
                    <span class="text-muted small">New</span>
                <?php endif; ?>
            </div>
            <h5 class="fw-bold mb-1 product-title">
                <a href="product.php?slug=<?php echo e($product['slug']); ?>"><?php echo e($product['name']); ?></a>
            </h5>
            <p class="text-muted small mb-3 clamp-2"><?php echo e($product['short_description'] ?? 'Premium fashion essentials'); ?></p>
            <div class="d-flex justify-content-between align-items-center">
                <div class="price-tag">
                    <strong><?php echo format_currency($price); ?></strong>
                    <?php if ($discount > 0): ?><span class="text-muted text-decoration-line-through small ms-1"><?php echo format_currency($regular); ?></span><?php endif; ?>
                </div>
                <a class="btn btn-outline-dark btn-sm d-none d-sm-inline-flex" href="product.php?slug=<?php echo e($product['slug']); ?>">View</a>
            </div>
        </div>
    </article>
    <?php
}
?>
<section class="hero">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="text-accent fw-semibold text-uppercase small mb-2">Spring / Summer 2026</p>
                <h1 class="hero-title fw-bold mb-3">Style that moves with you.</h1>
                <p class="lead text-muted mb-4">Premium essentials, elevated tailoring and statement pieces for modern wardrobes and unforgettable everyday dressing.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a class="btn btn-accent btn-lg px-4" href="shop.php">Shop Collection <i class="bi bi-arrow-right ms-1"></i></a>
                    <a class="btn btn-outline-dark btn-lg px-4" href="#collections">Explore New In</a>
                </div>
                <div class="hero-stats d-flex flex-wrap gap-4 mt-5">
                    <div>
                        <div class="fw-bold fs-4">15K+</div>
                        <div class="text-muted small">Happy customers</div>
                    </div>
                    <div>
                        <div class="fw-bold fs-4">4.8<i class="bi bi-star-fill text-accent small ms-1"></i></div>
                        <div class="text-muted small">Average rating</div>
                    </div>
                    <div>
                        <div class="fw-bold fs-4">200+</div>
                        <div class="text-muted small">Premium styles</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-card position-relative">
                    <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1000&q=80" alt="Fashion model in a premium outfit" class="img-fluid">
                    <div class="hero-badge">
                        <div class="small text-accent fw-semibold">Best Seller</div>
                        <div class="fw-bold">City Light Set</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container section-gap" id="collections">
    <div class="section-heading mb-4">
        <p class="text-accent fw-semibold small mb-1">Shop by Category</p>
        <h2 class="fw-bold">Find your next favorite</h2>
    </div>
    <?php if (!empty($categories)): ?>
        <div class="category-scroll">
            <?php foreach ($categories as $index => $category): ?>
                <a class="category-card" href="shop.php?category=<?php echo e($category['slug']); ?>">
                    <img src="<?php echo e($category['image_url'] ?? $categoryImages[$index % count($categoryImages)]); ?>" alt="<?php echo e($category['name']); ?>" loading="lazy">
                    <div class="category-overlay">
                        <h5 class="fw-bold mb-1"><?php echo e($category['name']); ?></h5>
                        <span class="small">Shop now <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">Categories are coming soon.</p>
    <?php endif; ?>
</section>

<section class="usp-strip">
    <div class="container">
        <div class="row g-3 g-md-4 text-center">
            <div class="col-6 col-md-3">
                <i class="bi bi-truck"></i>
                <div class="fw-semibold small mt-2">Free Shipping</div>
            </div>
            <div class="col-6 col-md-3">
                <i class="bi bi-arrow-repeat"></i>
                <div class="fw-semibold small mt-2">30-Day Returns</div>
            </div>
            <div class="col-6 col-md-3">
                <i class="bi bi-shield-check"></i>
                <div class="fw-semibold small mt-2">Secure Payments</div>
            </div>
            <div class="col-6 col-md-3">
                <i class="bi bi-headset"></i>
                <div class="fw-semibold small mt-2">24/7 Support</div>
            </div>
        </div>
    </div>
</section>

<section class="container section-gap">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div class="section-heading">
            <p class="text-accent fw-semibold small mb-1">Featured Picks</p>
            <h2 class="fw-bold">Latest arrivals</h2>
        </div>
        <a href="shop.php" class="fw-semibold view-all-link">View all <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <?php if (!empty($latestProducts)): ?>
        <div class="row g-4">
            <?php foreach ($latestProducts as $product): ?>
                <div class="col-6 col-lg-3">
                    <?php render_product_card($product, $ratings); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">New arrivals are on the way — check back soon.</p>
    <?php endif; ?>
</section>

<?php if (!empty($featuredProducts)): ?>
<section class="container section-gap">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div class="section-heading">
            <p class="text-accent fw-semibold small mb-1">Bestsellers</p>
            <h2 class="fw-bold">Most loved this season</h2>
        </div>
        <a href="shop.php" class="fw-semibold view-all-link">View all <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="row g-4">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="col-6 col-lg-4">
                <?php render_product_card($product, $ratings); ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="promo-banner section-gap">
    <div class="container">
        <div class="promo-banner-inner">
            <div>
                <p class="text-uppercase small fw-semibold mb-2 opacity-75">Limited Time</p>
                <h2 class="fw-bold mb-2">Up to 40% off selected styles</h2>
                <p class="mb-4 opacity-75">Refresh your wardrobe with premium pieces at unbeatable prices.</p>
                <a href="shop.php" class="btn btn-light btn-lg px-4">Shop the Sale</a>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($testimonials)): ?>
<section class="container section-gap" id="testimonials">
    <div class="section-heading text-center mb-5">
        <p class="text-accent fw-semibold small mb-1">Trusted by style lovers</p>
        <h2 class="fw-bold">What our customers say</h2>
    </div>
    <div class="row g-4">
        <?php foreach (array_slice($testimonials, 0, 3) as $testimonial): ?>
            <div class="col-md-4">
                <div class="testimonial-card h-100">
                    <div class="stars mb-3" style="--rating: 5" aria-hidden="true"></div>
                    <p class="mb-4">"<?php echo e($testimonial['testimonial']); ?>"</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="testimonial-avatar"><?php echo e(strtoupper(substr($testimonial['name'], 0, 1))); ?></div>
                        <div>
                            <div class="fw-bold small"><?php echo e($testimonial['name']); ?></div>
                            <?php if (!empty($testimonial['role'])): ?><div class="text-muted small"><?php echo e($testimonial['role']); ?></div><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($faqs)): ?>
<section class="container section-gap" id="faq">
    <div class="row g-5">
        <div class="col-lg-5">
            <p class="text-accent fw-semibold small mb-1">Got Questions?</p>
            <h2 class="fw-bold mb-3">Why shoppers keep returning</h2>
            <p class="text-muted">Thoughtful service, premium quality and seamless delivery make the Spotlight experience feel effortless. Still have questions? <a href="#contact" class="text-accent fw-semibold">Get in touch</a>.</p>
        </div>
        <div class="col-lg-7">
            <div class="accordion" id="faqAccordion">
                <?php foreach ($faqs as $index => $faq): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?php echo $index === 0 ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $index; ?>" aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                                <?php echo e($faq['question']); ?>
                            </button>
                        </h2>
                        <div id="faq<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted"><?php echo e($faq['answer']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($blogPosts)): ?>
<section class="container section-gap" id="blog">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div class="section-heading">
            <p class="text-accent fw-semibold small mb-1">Journal</p>
            <h2 class="fw-bold">From the blog</h2>
        </div>
        <a href="blog.php" class="fw-semibold view-all-link">View all <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="row g-4">
        <?php foreach ($blogPosts as $post): ?>
            <div class="col-md-6 col-lg-4">
                <article class="blog-card h-100">
                    <a href="blog.php?slug=<?php echo e($post['slug']); ?>">
                        <img src="<?php echo e($post['featured_image'] ?? 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=800&q=80'); ?>" alt="<?php echo e($post['title']); ?>" loading="lazy">
                    </a>
                    <div class="p-4">
                        <div class="text-muted small mb-2"><?php echo e(date('F j, Y', strtotime($post['created_at']))); ?></div>
                        <h5 class="fw-bold mb-2"><a href="blog.php?slug=<?php echo e($post['slug']); ?>"><?php echo e($post['title']); ?></a></h5>
                        <p class="text-muted small clamp-2"><?php echo e($post['excerpt']); ?></p>
                        <a href="blog.php?slug=<?php echo e($post['slug']); ?>" class="fw-semibold view-all-link">Read more <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="container section-gap" id="contact">
    <div class="contact-card">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <p class="text-accent fw-semibold small mb-1">Contact</p>
                <h2 class="fw-bold mb-3">Need help styling your next look?</h2>
                <p class="text-muted mb-4">Our team is available for fit advice, gifting and partnership requests.</p>
                <ul class="list-unstyled d-flex flex-column gap-3 text-muted">
                    <li class="d-flex align-items-center gap-2"><i class="bi bi-envelope text-accent"></i><?php echo e(SITE_EMAIL); ?></li>
                    <li class="d-flex align-items-center gap-2"><i class="bi bi-telephone text-accent"></i><?php echo e(SITE_PHONE); ?></li>
                    <li class="d-flex align-items-center gap-2"><i class="bi bi-geo-alt text-accent"></i>Available worldwide, ships fast</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <form method="post" action="index.php" class="contact-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input class="form-control" type="text" name="name" placeholder="Your name" required>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="email" name="email" placeholder="Your email" required>
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" name="message" rows="4" placeholder="How can we help?"></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-accent px-4" type="submit">Send Message</button>
                        </div>
                    </div>
                </form>
                <hr class="my-4">
                <form id="newsletter-form" class="d-flex gap-2" data-newsletter-form>
                    <input class="form-control" type="email" name="email" placeholder="Join our newsletter" required>
                    <button class="btn btn-dark" type="submit">Subscribe</button>
                </form>
                <div class="form-text mt-2" data-newsletter-message><?php echo e($message); ?></div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
