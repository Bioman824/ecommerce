<?php
/**
 * Global configuration for Spotlight Fashion Store.
 * This file is safe for shared hosting and can be adjusted per environment.
 */

session_start();
date_default_timezone_set('Europe/London');

if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/ecommerce/');
}
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

if (!defined('DB_HOST')) {
    define('DB_HOST', '127.0.0.1');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'spotlight_fashion');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', '');
}
if (!defined('DB_PORT')) {
    define('DB_PORT', '3306');
}

if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'Spotlight Fashion Store');
}
if (!defined('SITE_EMAIL')) {
    define('SITE_EMAIL', 'hello@spotlightfashion.test');
}
if (!defined('SITE_PHONE')) {
    define('SITE_PHONE', '+1 555 123 4567');
}
if (!defined('ADMIN_EMAIL')) {
    define('ADMIN_EMAIL', 'admin@spotlightfashion.test');
}

if (!defined('UPLOAD_DIR')) {
    define('UPLOAD_DIR', ROOT_PATH . '/Uploads/');
}
if (!defined('ASSET_URL')) {
    define('ASSET_URL', BASE_URL . 'Assets/');
}
