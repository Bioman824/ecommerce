<?php
/**
 * Checkout page with guest checkout and order creation.
 */
require_once __DIR__ . '/Includes/header.php';

$items = get_cart_items();
$subtotal = 0;
foreach ($items as $item) {
    $price = (float) ($item['product']['sale_price'] > 0 ? $item['product']['sale_price'] : $item['product']['regular_price']);
    $subtotal += $price * (int) $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');

    if ($name !== '' && $email !== '' && $items) {
        $stmt = db()->prepare('INSERT INTO orders (customer_name, customer_email, shipping_address, order_notes, subtotal, shipping_fee, tax, total, payment_method, status) VALUES (?, ?, ?, ?, ?, 0.00, 0.00, ?, ?, "pending")');
        $total = $subtotal;
        $stmt->execute([$name, $email, $address, $notes, $subtotal, $total, 'cash']);
        $orderId = (int) db()->lastInsertId();

        foreach ($items as $item) {
            $price = (float) ($item['product']['sale_price'] > 0 ? $item['product']['sale_price'] : $item['product']['regular_price']);
            $orderItem = db()->prepare('INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price) VALUES (?, ?, ?, ?, ?)');
            $orderItem->execute([$orderId, (int) $item['product']['id'], $item['product']['name'], (int) $item['quantity'], $price]);
        }

        $_SESSION['cart'] = [];
        $successMessage = 'Order placed successfully. We will contact you shortly.';
    }
}
?>
<section class="container py-5">
    <h1 class="fw-bold mb-4">Checkout</h1>
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success" data-toast><?php echo e($successMessage); ?></div>
    <?php endif; ?>
    <?php if ($items): ?>
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="section-card p-4">
                    <form method="post">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3"><input class="form-control" type="text" name="name" placeholder="Full name" required></div>
                        <div class="mb-3"><input class="form-control" type="email" name="email" placeholder="Email address" required></div>
                        <div class="mb-3"><textarea class="form-control" name="address" rows="4" placeholder="Shipping address"></textarea></div>
                        <div class="mb-3"><textarea class="form-control" name="notes" rows="3" placeholder="Order notes"></textarea></div>
                        <button class="btn btn-accent" type="submit">Place order</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="section-card p-4">
                    <h5 class="fw-bold">Summary</h5>
                    <div class="d-flex justify-content-between py-2"><span>Subtotal</span><strong><?php echo format_currency($subtotal); ?></strong></div>
                    <div class="d-flex justify-content-between py-2"><span>Shipping</span><strong>Free</strong></div>
                    <div class="d-flex justify-content-between py-2 border-top mt-2 pt-3"><span>Total</span><strong><?php echo format_currency($subtotal); ?></strong></div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="section-card p-5 text-center">
            <h3 class="fw-bold">Add items to checkout</h3>
            <a class="btn btn-accent mt-3" href="shop.php">Browse products</a>
        </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
