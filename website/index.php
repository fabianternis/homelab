<?php
$db_file = __DIR__ . '/stats.db';

// Initialize the SQLite database if it doesn't exist
$db = new PDO('sqlite:' . $db_file);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS stats (
    metric TEXT PRIMARY KEY,
    value TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");



// Fetch stats
$stmt = $db->query("SELECT metric, value, updated_at FROM stats ORDER BY metric ASC");
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ternis.net Dashboard</title>
</head>
<body>
    <h1>ternis.net</h1>
    
    <h2>System Statistics</h2>
    <table>
        <thead>
            <tr>
                <th>Metric</th>
                <th>Value</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stats as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['metric']); ?></td>
                <td><?php echo htmlspecialchars($row['value']); ?></td>
                <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    

</body>
</html>
