<?php
/**
 * Common application helpers.
 */
require_once __DIR__ . '/../Configuration/config.php';

function db(): PDO {
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $exception) {
        throw new RuntimeException('Database connection failed: ' . $exception->getMessage());
    }
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function sanitize(string $value): string {
    return trim(strip_tags($value));
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        throw new RuntimeException('Invalid CSRF token.');
    }
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}

function format_currency(float $amount): string {
    return '$' . number_format($amount, 2);
}

function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text ?: 'item';
}

function get_setting(string $key, string $default = ''): string {
    try {
        $stmt = db()->prepare('SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1');
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row['setting_value'] ?? $default;
    } catch (Throwable $exception) {
        return $default;
    }
}

function get_cart_count(): int {
    return isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
}

function add_to_cart(int $productId, int $quantity = 1, string $variant = ''): void {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $key = $variant !== '' ? $productId . ':' . $variant : (string) $productId;
    if (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$key] = [
            'product_id' => $productId,
            'variant' => $variant,
            'quantity' => $quantity,
        ];
    }
}

function get_cart_items(): array {
    if (empty($_SESSION['cart'])) {
        return [];
    }

    $items = [];
    foreach ($_SESSION['cart'] as $entry) {
        $productId = (int) $entry['product_id'];
        $stmt = db()->prepare('SELECT id, name, slug, sale_price, regular_price, stock_quantity FROM products WHERE id = ? LIMIT 1');
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        if ($product) {
            $items[] = [
                'product' => $product,
                'quantity' => (int) $entry['quantity'],
                'variant' => $entry['variant'],
            ];
        }
    }

    return $items;
}

function get_latest_products(int $limit = 8): array {
    try {
        $stmt = db()->prepare('SELECT id, name, slug, short_description, regular_price, sale_price, discount_percent, stock_quantity, is_featured, is_trending, image_url FROM products WHERE status = "active" ORDER BY created_at DESC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Throwable $exception) {
        return [];
    }
}

function get_featured_products(int $limit = 6): array {
    try {
        $stmt = db()->prepare('SELECT id, name, slug, short_description, regular_price, sale_price, discount_percent, stock_quantity, is_trending, image_url FROM products WHERE status = "active" AND is_featured = 1 ORDER BY created_at DESC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Throwable $exception) {
        return [];
    }
}

function get_product_ratings(array $productIds): array {
    $productIds = array_values(array_unique(array_map('intval', $productIds)));
    if (empty($productIds)) {
        return [];
    }

    try {
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $stmt = db()->prepare("SELECT product_id, AVG(rating) as avg_rating, COUNT(*) as review_count FROM reviews WHERE status = 'approved' AND product_id IN ($placeholders) GROUP BY product_id");
        $stmt->execute($productIds);
        $ratings = [];
        foreach ($stmt->fetchAll() as $row) {
            $ratings[(int) $row['product_id']] = [
                'avg' => round((float) $row['avg_rating'], 1),
                'count' => (int) $row['review_count'],
            ];
        }
        return $ratings;
    } catch (Throwable $exception) {
        return [];
    }
}

function get_categories(): array {
    try {
        $stmt = db()->query('SELECT id, name, slug, image_url FROM categories WHERE status = "active" ORDER BY name ASC LIMIT 8');
        return $stmt->fetchAll();
    } catch (Throwable $exception) {
        return [];
    }
}

function get_testimonials(): array {
    try {
        $stmt = db()->query('SELECT name, role, testimonial FROM testimonials WHERE status = "active" ORDER BY created_at DESC LIMIT 6');
        return $stmt->fetchAll();
    } catch (Throwable $exception) {
        return [];
    }
}

function get_faqs(): array {
    try {
        $stmt = db()->query('SELECT question, answer FROM faqs WHERE status = "active" ORDER BY sort_order ASC LIMIT 8');
        return $stmt->fetchAll();
    } catch (Throwable $exception) {
        return [];
    }
}

function get_blog_posts(int $limit = 3): array {
    try {
        $stmt = db()->prepare('SELECT id, title, slug, excerpt, featured_image, created_at FROM blog_posts WHERE status = "published" ORDER BY created_at DESC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Throwable $exception) {
        return [];
    }
}
