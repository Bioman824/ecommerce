<?php
/**
 * Newsletter signup endpoint.
 */
require_once __DIR__ . '/../Includes/functions.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new RuntimeException('Only POST requests are allowed.');
    }

    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException('Invalid email address.');
    }

    $stmt = db()->prepare('INSERT INTO newsletters (email) VALUES (?) ON DUPLICATE KEY UPDATE email = VALUES(email)');
    $stmt->execute([$email]);
    echo json_encode(['success' => true]);
} catch (Throwable $exception) {
    echo json_encode(['success' => false, 'message' => $exception->getMessage()]);
}
