</main>

<section class="usp-bar py-4">
    <div class="container">
        <div class="row g-4 text-center text-md-start">
            <div class="col-6 col-md-3 d-flex align-items-center gap-3 justify-content-center justify-content-md-start">
                <i class="bi bi-truck"></i>
                <div>
                    <div class="fw-bold small">Free Shipping</div>
                    <div class="text-muted small">On orders over $75</div>
                </div>
            </div>
            <div class="col-6 col-md-3 d-flex align-items-center gap-3 justify-content-center justify-content-md-start">
                <i class="bi bi-arrow-repeat"></i>
                <div>
                    <div class="fw-bold small">Easy Returns</div>
                    <div class="text-muted small">30-day guarantee</div>
                </div>
            </div>
            <div class="col-6 col-md-3 d-flex align-items-center gap-3 justify-content-center justify-content-md-start">
                <i class="bi bi-shield-check"></i>
                <div>
                    <div class="fw-bold small">Secure Checkout</div>
                    <div class="text-muted small">100% protected payments</div>
                </div>
            </div>
            <div class="col-6 col-md-3 d-flex align-items-center gap-3 justify-content-center justify-content-md-start">
                <i class="bi bi-headset"></i>
                <div>
                    <div class="fw-bold small">Dedicated Support</div>
                    <div class="text-muted small">We're here to help</div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <a class="brand d-inline-block mb-3" href="<?php echo BASE_URL; ?>"><?php echo e($siteName); ?></a>
                <p class="text-muted"><?php echo e($siteTagline); ?> Curated essentials and statement pieces for the modern wardrobe, crafted for comfort, style and effortless confidence.</p>
                <div class="d-flex gap-2 mt-3 footer-social">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" aria-label="Pinterest"><i class="bi bi-pinterest"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold">Shop</h6>
                <ul class="list-unstyled text-muted">
                    <li><a href="<?php echo BASE_URL; ?>shop.php">New Arrivals</a></li>
                    <li><a href="<?php echo BASE_URL; ?>shop.php">Featured</a></li>
                    <li><a href="<?php echo BASE_URL; ?>shop.php">Sale</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php#collections">Collections</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold">Support</h6>
                <ul class="list-unstyled text-muted">
                    <li><a href="<?php echo BASE_URL; ?>index.php#contact">Contact</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php#faq">FAQs</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php#blog">Blog</a></li>
                    <li><a href="<?php echo BASE_URL; ?>cart.php">Track Order</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="fw-bold">Newsletter</h6>
                <p class="text-muted">Get early access to launches and exclusive offers.</p>
                <form class="newsletter-form d-flex gap-2" method="post" action="<?php echo BASE_URL; ?>index.php">
                    <input class="form-control" type="email" name="email" placeholder="Your email" required>
                    <button class="btn btn-accent" type="submit">Join</button>
                </form>
                <div class="d-flex align-items-center gap-2 text-muted small mt-3">
                    <i class="bi bi-envelope"></i><span><?php echo e(SITE_EMAIL); ?></span>
                </div>
                <div class="d-flex align-items-center gap-2 text-muted small mt-1">
                    <i class="bi bi-telephone"></i><span><?php echo e(SITE_PHONE); ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 border-top mt-4 pt-3 text-muted small">
            <span>&copy; <?php echo date('Y'); ?> <?php echo e($siteName); ?>. All rights reserved.</span>
            <div class="d-flex align-items-center gap-2 payment-icons">
                <i class="bi bi-credit-card"></i>
                <i class="bi bi-paypal"></i>
                <i class="bi bi-apple"></i>
                <i class="bi bi-wallet2"></i>
            </div>
        </div>
    </div>
</footer>
<a class="whatsapp-float" href="https://wa.me/15551234567" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>
<button type="button" class="back-to-top" id="backToTop" aria-label="Back to top">
    <i class="bi bi-arrow-up"></i>
</button>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo ASSET_URL; ?>js/app.js"></script>
</body>
</html>
