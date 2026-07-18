<?php
/**
 * Review submission endpoint.
 */
require_once __DIR__ . '/../Includes/functions.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new RuntimeException('Only POST requests are allowed.');
    }

    if (!isset($_SESSION['user_id'])) {
        throw new RuntimeException('Please sign in first.');
    }

    $productId = (int) ($_POST['product_id'] ?? 0);
    $rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
    $reviewText = sanitize($_POST['review_text'] ?? '');

    $stmt = db()->prepare('INSERT INTO reviews (product_id, user_id, rating, review_text, status) VALUES (?, ?, ?, ?, "approved")');
    $stmt->execute([$productId, $_SESSION['user_id'], $rating, $reviewText]);

    echo json_encode(['success' => true]);
} catch (Throwable $exception) {
    echo json_encode(['success' => false, 'message' => $exception->getMessage()]);
}
