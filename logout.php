<?php
/**
 * Ends the current session for both customer and admin logins.
 */
require_once __DIR__ . '/Includes/functions.php';

$wasAdmin = ($_SESSION['user_role'] ?? '') === 'admin';
$_SESSION = [];
session_destroy();

redirect(BASE_URL . ($wasAdmin ? 'Admin/login.php' : 'login.php'));
