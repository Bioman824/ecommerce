<?php
/**
 * AJAX cart handler for adding products to session cart.
 */
require_once __DIR__ . '/../Includes/functions.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new RuntimeException('Only POST requests are allowed.');
    }

    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

    if ($productId <= 0) {
        throw new RuntimeException('Invalid product.');
    }

    add_to_cart($productId, $quantity);

    echo json_encode([
        'success' => true,
        'count' => get_cart_count(),
    ]);
} catch (Throwable $exception) {
    echo json_encode([
        'success' => false,
        'message' => $exception->getMessage(),
    ]);
}
