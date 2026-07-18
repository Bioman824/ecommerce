<?php
require_once __DIR__ . '/Includes/header.php';

$slug = sanitize($_GET['slug'] ?? '');
if ($slug !== '') {
    $stmt = db()->prepare('SELECT id, title, slug, content, excerpt, featured_image, author, created_at FROM blog_posts WHERE slug = ? AND status = "published" LIMIT 1');
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
} else {
    $post = null;
}

$posts = get_blog_posts(6);
?>
<section class="container py-5">
    <?php if ($post): ?>
        <h1 class="fw-bold mb-3"><?php echo e($post['title']); ?></h1>
        <p class="text-muted">By <?php echo e($post['author'] ?? 'Spotlight Team'); ?> • <?php echo e($post['created_at']); ?></p>
        <div class="section-card p-4 mt-4">
            <p><?php echo e($post['content']); ?></p>
        </div>
    <?php else: ?>
        <h1 class="fw-bold mb-4">From the journal</h1>
        <div class="row g-4">
            <?php foreach ($posts as $postItem): ?>
                <div class="col-lg-4">
                    <article class="section-card p-4 h-100">
                        <h5 class="fw-bold"><?php echo e($postItem['title']); ?></h5>
                        <p class="text-muted"><?php echo e($postItem['excerpt']); ?></p>
                        <a class="btn btn-outline-dark btn-sm" href="blog.php?slug=<?php echo e($postItem['slug']); ?>">Read article</a>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
