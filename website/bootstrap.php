<?php
// Prevent direct access via web browser just in case
if (php_sapi_name() !== 'cli' && basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    exit('Direct access not permitted.');
}

$base_dir = __DIR__;
$db_file = $base_dir . '/stats.db';

try {
    $db = new PDO('sqlite:' . $db_file);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS stats (
        metric TEXT PRIMARY KEY,
        value TEXT,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

function get_stats($db) {
    $stmt = $db->query("SELECT metric, value, updated_at FROM stats ORDER BY metric ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
