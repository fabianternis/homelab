<?php
require_once dirname(__DIR__) . '/bootstrap.php';

$stats = get_stats($db);

$metric_labels = [
    'immich_photos' => 'Photos (Immich)',
    'immich_videos' => 'Videos (Immich)',
    'pihole_queries_today' => 'DNS Queries Today',
    'pihole_ads_blocked' => 'Ads Blocked Today',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ternis.net — Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>ternis.net</h1>
            <p class="subtitle">Homelab Dashboard</p>
        </header>

        <?php if (!empty($stats)): ?>
        <section class="stats-grid">
            <?php foreach ($stats as $row):
                $key = $row['metric'];
                $label = $metric_labels[$key] ?? $key;
            ?>
            <div class="stat-card">
                <span class="stat-value"><?php echo htmlspecialchars($row['value']); ?></span>
                <span class="stat-label"><?php echo htmlspecialchars($label); ?></span>
                <span class="stat-updated"><?php echo htmlspecialchars($row['updated_at']); ?></span>
            </div>
            <?php endforeach; ?>
        </section>
        <?php else: ?>
        <div class="empty-state">
            <p>No stats collected yet. Run <code>update_stats.php</code> to populate.</p>
        </div>
        <?php endif; ?>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> ternis.net</p>
        </footer>
    </div>
</body>
</html>
