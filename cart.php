<?php
/**
 * Cart overview page.
 */
require_once __DIR__ . '/Includes/header.php';

$items = get_cart_items();
$subtotal = 0;
foreach ($items as $item) {
    $price = (float) ($item['product']['sale_price'] > 0 ? $item['product']['sale_price'] : $item['product']['regular_price']);
    $subtotal += $price * (int) $item['quantity'];
}
?>
<section class="container py-5">
    <h1 class="fw-bold mb-4">Your cart</h1>
    <?php if ($items): ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="section-card p-4">
                    <?php foreach ($items as $item): ?>
                        <?php $price = (float) ($item['product']['sale_price'] > 0 ? $item['product']['sale_price'] : $item['product']['regular_price']); ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                            <div>
                                <h5 class="fw-bold mb-1"><?php echo e($item['product']['name']); ?></h5>
                                <div class="text-muted small">Qty: <?php echo (int) $item['quantity']; ?></div>
                            </div>
                            <div class="fw-bold"><?php echo format_currency($price * (int) $item['quantity']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="section-card p-4">
                    <h5 class="fw-bold">Order summary</h5>
                    <div class="d-flex justify-content-between py-2"><span>Subtotal</span><strong><?php echo format_currency($subtotal); ?></strong></div>
                    <div class="d-flex justify-content-between py-2"><span>Shipping</span><strong>Free</strong></div>
                    <div class="d-flex justify-content-between py-2 border-top mt-2 pt-3"><span>Total</span><strong><?php echo format_currency($subtotal); ?></strong></div>
                    <a class="btn btn-accent w-100 mt-3" href="checkout.php">Proceed to checkout</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="section-card p-5 text-center">
            <h3 class="fw-bold">Your cart is empty</h3>
            <p class="text-muted">Browse our latest arrivals to add your first pieces to the bag.</p>
            <a class="btn btn-accent" href="shop.php">Continue shopping</a>
        </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/Includes/footer.php'; ?>
