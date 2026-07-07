<?php
require_once __DIR__ . '/bootstrap.php';

// 1. Get Pi-hole stats from pihole-FTL.db
$pihole_db_path = '/etc/pihole/pihole-FTL.db';
$pihole_queries_today = 0;
$pihole_ads_blocked = 0;

try {
    $pihole_db = new PDO('sqlite:' . $pihole_db_path);
    $pihole_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get start of today timestamp
    $start_of_day = strtotime("today");
    
    // Queries today
    $stmt = $pihole_db->query("SELECT COUNT(*) FROM queries WHERE timestamp >= $start_of_day");
    $pihole_queries_today = $stmt->fetchColumn();
    
    // Ads blocked today (status 1, 4, 5, 9, 10, 11 etc. in pihole are blocked, typically status in (1,4,5,9,10,11))
    $stmt = $pihole_db->query("SELECT COUNT(*) FROM queries WHERE timestamp >= $start_of_day AND status IN (1,4,5,9,10,11,15,16,17)");
    $pihole_ads_blocked = $stmt->fetchColumn();
} catch (Exception $e) {
    echo "Pi-hole DB error: " . $e->getMessage() . "\n";
}

// 2. Get Immich stats via API
$immich_photos = 0;
$immich_videos = 0;
$immich_url = "http://192.168.1.21:2283";
$env = parse_ini_file(__DIR__ . '/.env');
$login_payload = json_encode([
    'email' => $env['IMMICH_USER'],
    'password' => $env['IMMICH_PASS']
]);

$ch = curl_init($immich_url . '/api/auth/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $login_payload);
$login_resp = curl_exec($ch);
curl_close($ch);

$login_data = json_decode($login_resp, true);

if (isset($login_data['accessToken'])) {
    $token = $login_data['accessToken'];
    
    $ch = curl_init($immich_url . '/api/server/statistics');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ]);
    $stats_resp = curl_exec($ch);
    curl_close($ch);
    
    $stats_data = json_decode($stats_resp, true);
    if (isset($stats_data['photos'])) {
        $immich_photos = $stats_data['photos'];
        $immich_videos = $stats_data['videos'];
    }
} else {
    echo "Immich login failed.\n";
}

// 3. Update stats.db
try {
    
    $updateStmt = $db->prepare("INSERT INTO stats (metric, value, updated_at) VALUES (:metric, :value, CURRENT_TIMESTAMP) ON CONFLICT(metric) DO UPDATE SET value=excluded.value, updated_at=excluded.updated_at");
    
    $updateStmt->execute([':metric' => 'immich_photos', ':value' => $immich_photos]);
    $updateStmt->execute([':metric' => 'immich_videos', ':value' => $immich_videos]);
    $updateStmt->execute([':metric' => 'pihole_queries_today', ':value' => $pihole_queries_today]);
    $updateStmt->execute([':metric' => 'pihole_ads_blocked', ':value' => $pihole_ads_blocked]);
    
    echo "Stats updated successfully.\n";
} catch (Exception $e) {
    echo "Stats DB error: " . $e->getMessage() . "\n";
}
?>
