<?php
require_once __DIR__ . '/Includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    if ($name !== '' && $email !== '' && $message !== '') {
        $stmt = db()->prepare('INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, $message]);
        $successMessage = 'Message received. We will be in touch soon.';
    }
}
?>
<section class="container py-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <h1 class="fw-bold mb-3">Contact spotlight</h1>
            <p class="text-muted">We are available for styling support, wholesale requests and collaboration opportunities.</p>
        </div>
        <div class="col-lg-6">
            <div class="section-card p-4">
                <?php if (!empty($successMessage)): ?><div class="alert alert-success"><?php echo e($successMessage); ?></div><?php endif; ?>
                <form method="post">
                    <div class="mb-3"><input class="form-control" name="name" placeholder="Your name" required></div>
                    <div class="mb-3"><input class="form-control" name="email" type="email" placeholder="Your email" required></div>
                    <div class="mb-3"><textarea class="form-control" name="message" rows="5" placeholder="How can we help?"></textarea></div>
                    <button class="btn btn-accent" type="submit">Send request</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
