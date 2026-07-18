<?php
/**
 * Simple installer that imports the SQL schema when the database is empty.
 */
require_once __DIR__ . '/../Configuration/config.php';
require_once __DIR__ . '/../Includes/functions.php';

try {
    db();
    echo 'Database connection established.';
} catch (Throwable $exception) {
    echo 'Database connection failed: ' . e($exception->getMessage());
    exit;
}

$sql = file_get_contents(__DIR__ . '/schema.sql');
if ($sql === false) {
    echo 'Unable to read schema file.';
    exit;
}

try {
    $statements = array_filter(array_map('trim', preg_split('/;\s*\n/', $sql)));
    foreach ($statements as $statement) {
        if ($statement === '') {
            continue;
        }
        db()->exec($statement);
    }
    echo '\nSchema imported successfully.';
} catch (Throwable $exception) {
    echo '\nSchema import failed: ' . $exception->getMessage();
}
