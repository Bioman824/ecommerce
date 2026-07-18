<?php
/**
 * Media library placeholder page.
 */
require_once __DIR__ . '/../Includes/functions.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    redirect(BASE_URL . 'Admin/login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Library - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid p-4">
    <h2 class="fw-bold mb-4">Media Library</h2>
    <div class="card p-4">
        <p class="text-muted">Uploads can be managed in this space for future expansion.</p>
    </div>
</div>
</body>
</html>
