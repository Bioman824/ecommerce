-- Spotlight Fashion Store database schema
-- Compatible with MySQL 8+ / MariaDB 10.4+

CREATE DATABASE IF NOT EXISTS spotlight_fashion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE spotlight_fashion;

CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','customer') NOT NULL DEFAULT 'customer',
    is_active TINYINT(1) DEFAULT 1,
    email_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_email (email)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(140) NOT NULL UNIQUE,
    parent_id INT UNSIGNED NULL,
    description TEXT NULL,
    image_url VARCHAR(255) NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    is_featured TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_categories_status (status),
    INDEX idx_categories_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    name VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    short_description TEXT NULL,
    description LONGTEXT NULL,
    sku VARCHAR(80) NULL,
    brand VARCHAR(100) NULL,
    material VARCHAR(120) NULL,
    regular_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    sale_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    discount_percent INT DEFAULT 0,
    stock_quantity INT DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    is_trending TINYINT(1) DEFAULT 0,
    is_popular TINYINT(1) DEFAULT 0,
    status ENUM('active','inactive','draft') NOT NULL DEFAULT 'active',
    image_url VARCHAR(255) NULL,
    video_url VARCHAR(255) NULL,
    meta_title VARCHAR(180) NULL,
    meta_description VARCHAR(255) NULL,
    meta_keywords VARCHAR(255) NULL,
    canonical_url VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_products_slug (slug),
    INDEX idx_products_status (status),
    INDEX idx_products_featured (is_featured)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    alt_text VARCHAR(180) NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_images_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_images_product (product_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_variants (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    color VARCHAR(80) NULL,
    size VARCHAR(40) NULL,
    sku VARCHAR(80) NULL,
    stock_quantity INT DEFAULT 0,
    price_adjustment DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_variants_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_variants_product (product_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NULL,
    rating INT NOT NULL DEFAULT 5,
    review_text TEXT NULL,
    status ENUM('approved','pending') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS testimonials (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    role VARCHAR(120) NULL,
    testimonial TEXT NOT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS faqs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS blog_posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    excerpt TEXT NULL,
    content LONGTEXT NULL,
    featured_image VARCHAR(255) NULL,
    author VARCHAR(120) NULL,
    status ENUM('published','draft') NOT NULL DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_blog_posts_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS newsletters (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(180) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contacts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS coupons (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(80) NOT NULL UNIQUE,
    discount_type ENUM('percent','fixed') NOT NULL DEFAULT 'percent',
    discount_value DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    is_active TINYINT(1) DEFAULT 1,
    expires_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    customer_name VARCHAR(150) NOT NULL,
    customer_email VARCHAR(180) NOT NULL,
    shipping_address TEXT NULL,
    billing_address TEXT NULL,
    order_notes TEXT NULL,
    coupon_code VARCHAR(80) NULL,
    subtotal DECIMAL(10,2) DEFAULT 0.00,
    shipping_fee DECIMAL(10,2) DEFAULT 0.00,
    tax DECIMAL(10,2) DEFAULT 0.00,
    total DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('pending','paid','processing','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'pending',
    payment_method VARCHAR(80) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_orders_status (status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS activity_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    action VARCHAR(255) NOT NULL,
    details TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_activity_logs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS password_resets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(180) NOT NULL,
    token VARCHAR(80) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_password_resets_token (token)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS wishlists (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    product_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_wishlists_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_wishlists_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY uq_wishlist_user_product (user_id, product_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payment_gateways (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    gateway_name VARCHAR(80) NOT NULL UNIQUE,
    public_key VARCHAR(255) NULL,
    secret_key VARCHAR(255) NULL,
    encryption_key VARCHAR(255) NULL,
    webhook_secret VARCHAR(255) NULL,
    environment ENUM('sandbox','production') NOT NULL DEFAULT 'sandbox',
    is_enabled TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO settings (setting_key, setting_value) VALUES
('site_name','Spotlight Fashion Store'),
('site_tagline','Premium clothing for modern living'),
('currency_symbol','$'),
('currency_code','USD'),
('maintenance_mode','0')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO users (full_name, email, password_hash, role, is_active, email_verified) VALUES
('System Administrator', 'admin@spotlightfashion.test', '$2y$10$6HdbLUgZUQw8ra7z0Jdfcua6gW0Zf7z38YaH3emE0HcXGzhTJ6N3u', 'admin', 1, 1)
ON DUPLICATE KEY UPDATE email = VALUES(email);

INSERT INTO testimonials (name, role, testimonial, status) VALUES
('Mina R.', 'Creative Director', 'Every piece feels premium and polished. The service is thoughtful and incredibly fast.', 'active'),
('Jordan T.', 'Product Designer', 'The styling is effortless and the site experience is beautiful and intuitive.', 'active')
ON DUPLICATE KEY UPDATE testimonial = VALUES(testimonial);

INSERT INTO faqs (question, answer, status, sort_order) VALUES
('Do you offer free shipping?', 'Yes, orders over $150 qualify for free express shipping.', 'active', 1),
('Can I return a purchase?', 'We offer easy returns within 14 days for unworn items with original packaging.', 'active', 2),
('Do you support wholesale?', 'Yes, we welcome wholesale and partnership inquiries through the contact page.', 'active', 3)
ON DUPLICATE KEY UPDATE answer = VALUES(answer);

INSERT INTO categories (name, slug, description, is_featured, sort_order) VALUES
('Women''s Wear', 'womens-wear', 'Signature staples for polished everyday dressing.', 1, 1),
('Men''s Wear', 'mens-wear', 'Sharp essentials with timeless structure and comfort.', 1, 2),
('Accessories', 'accessories', 'Elevated details and finishing touches.', 1, 3)
ON DUPLICATE KEY UPDATE description = VALUES(description);

INSERT INTO products (category_id, name, slug, short_description, description, sku, brand, material, regular_price, sale_price, discount_percent, stock_quantity, is_featured, is_trending, is_popular, status, image_url, meta_title, meta_description) VALUES
(1, 'Linen Tailored Blazer', 'linen-tailored-blazer', 'Soft structure with elegant movement.', 'A polished blazer crafted from premium linen for refined layering and effortless versatility.', 'SPF-001', 'Aurelia', 'Linen', 129.00, 109.00, 16, 18, 1, 1, 1, 'active', 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=80', 'Linen Tailored Blazer', 'Premium linen blazer for polished dressing.'),
(1, 'Contour Knit Set', 'contour-knit-set', 'Luxury comfort with a sculpted silhouette.', 'An elevated knit set designed for movement, softness and modern lounge-to-street dressing.', 'SPF-002', 'Aurelia', 'Cotton Blend', 94.00, 79.00, 16, 12, 1, 1, 1, 'active', 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=80', 'Contour Knit Set', 'Comfort-first knit set with sculpted detail.'),
(2, 'Structured Utility Jacket', 'structured-utility-jacket', 'Modern outerwear with clean technical details.', 'A versatile jacket blending sharp tailoring with practical everyday functionality.', 'SPF-003', 'Noir House', 'Cotton', 112.00, 89.00, 20, 10, 1, 1, 1, 'active', 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80', 'Structured Utility Jacket', 'Technical utility jacket with a refined silhouette.')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, author, status) VALUES
('Why Elevated Essentials Matter', 'why-elevated-essentials-matter', 'Our design philosophy focuses on versatile staples that feel effortless and luxurious.', 'A deeper look into how thoughtful design meets daily comfort in our capsule wardrobe collections.', 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80', 'Mina R.', 'published'),
('How to Style Seasonal Layers', 'how-to-style-seasonal-layers', 'Layering is more than temperature control. It is a signal of poise and intent.', 'Discover how to combine lightweight pieces, bold textures and tailoring to create polished looks.', 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=80', 'Jordan T.', 'published')
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO payment_gateways (gateway_name, public_key, secret_key, encryption_key, webhook_secret, environment, is_enabled, sort_order) VALUES
('flutterwave', 'pk_test_x', 'sk_test_x', 'enc_test_x', 'whsec_x', 'sandbox', 1, 1),
('paystack', 'pk_test_y', 'sk_test_y', '', '', 'sandbox', 1, 2)
ON DUPLICATE KEY UPDATE gateway_name = VALUES(gateway_name);
