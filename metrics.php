<?php
include 'config.php';

// Funktion zum Abrufen des Servernamens per HLSW-Ping
function getServerNameFromHLSW($ip, $port) {
    // A2S_INFO Anfrage für den Servernamen
    $request = "\xFF\xFF\xFF\xFFTSource Engine Query\x00";

    // UDP-Socket erstellen
    $socket = fsockopen("udp://$ip", $port, $errno, $errstr, 3);
    if (!$socket) {
        return "Unbekannter Servername"; // Falls keine Verbindung möglich
    }

    // Timeout für den Socket setzen (in Sekunden)
    stream_set_timeout($socket, 2);

    // Anfrage senden und Antwort lesen
    fwrite($socket, $request);
    $response = fread($socket, 4096);
    fclose($socket);

    // Antwort prüfen und analysieren
    if (!$response) {
        return "Keine Antwort vom Server";
    }

    // Die ersten 4 Bytes der Antwort ignorieren (dies ist der Header)
    $response = substr($response, 4);

    // Funktion zum Extrahieren eines Null-terminierten Strings
    function readString(&$data) {
        $pos = strpos($data, "\x00");
        $string = substr($data, 0, $pos);
        $data = substr($data, $pos + 1);
        return preg_replace('/[^\x20-\x7E]/', '', $string); // Bereinigt nicht-druckbare Zeichen
    }

    // Servername extrahieren und das erste Zeichen entfernen, wenn es unerwünscht ist
    $server_name = readString($response);
    if (strlen($server_name) > 1 && ($server_name[0] === 'I' || !ctype_print($server_name[0]))) {
        $server_name = substr($server_name, 1);
    }
    
    return $server_name;
}

// Servername über HLSW-Ping abrufen
$servername = getServerNameFromHLSW($serverip, $serverport);

function fetch_metrics_data($metricsurl) {
    $data = file_get_contents($metricsurl);
    return $data;
}

function parse_all_metrics($data) {
    $metrics = [];
    $lines = explode("\n", $data);
    foreach ($lines as $line) {
        // Überspringe Kommentare und leere Zeilen
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }

        if (preg_match('/^([a-zA-Z0-9_]+)(\{[^}]*\})?\s+(.+)$/', $line, $matches)) {
            $name = $matches[1];
            $labels = [];
            if (!empty($matches[2])) {
                // Labels parsen
                $labelString = trim($matches[2], '{}');
                $labelPairs = explode(',', $labelString);
                foreach ($labelPairs as $pair) {
                    list($key, $value) = explode('=', $pair);
                    $labels[$key] = trim($value, '"');
                }
                // Verwende die Labels als Schlüssel
                $labelKey = json_encode($labels);
                $metrics[$name][$labelKey] = $matches[3];
            } else {
                $metrics[$name] = $matches[3];
            }
        }
    }
    return $metrics;
}

$metrics_data = fetch_metrics_data($metricsurl);
$metrics = parse_all_metrics($metrics_data);

// Serverinformationen abrufen
$version_major = $metrics['vr_version_major'] ?? 'N/A';
$version_minor = $metrics['vr_version_minor'] ?? 'N/A';
$version_patch = $metrics['vr_version_patch'] ?? 'N/A';
$version_revision = $metrics['vr_version_revision'] ?? 'N/A';

$players_connected = $metrics['vr_users_connected'] ?? 'N/A';
$max_players = $metrics['vr_users_connected_max'] ?? 'N/A';
$uptime_seconds = $metrics['vr_uptime_seconds'] ?? 0;
$uptime_hours = floor($uptime_seconds / 3600);
$active_users = $metrics['vr_users_taken'] ?? 'N/A';

// Server FPS abrufen
$fps = $metrics['vr_fps'] ?? 'N/A';

// Regionen definieren
$regions = [
    'Starter Cave' => 'StartCave',
    'Farbane Woods' => 'FarbaneWoods',
    'Dunley Farmlands' => 'DunleyFarmlands',
    'Hallowed Mountains' => 'HallowedMountains',
    'Silverlight Hills' => 'SilverlightHills',
    'Cursed Forest' => 'CursedForest',
    'Gloomrot South' => 'Gloomrot_South',
    'Gloomrot North' => 'Gloomrot_North',
];

// HTML-Ausgabe starten
echo '<div class="container">';
echo '<h1>Server Information</h1>';
echo '<table>';
echo '<tr>';
echo '<th>Server Name</th>';
echo '<td>' . htmlspecialchars($servername) . '</td>';
echo '</tr>';
echo '<tr>';
echo '<th>Server Address</th>';
echo '<td>' . htmlspecialchars($display_ip) . '</td>';
echo '</tr>';
echo '<tr>';
echo '<th>Server Version</th>';
echo '<td>' . htmlspecialchars($version_major . '.' . $version_minor . '.' . $version_patch . '.' . $version_revision) . '</td>';
echo '</tr>';
echo '<tr>';
echo '<th>Connected Users</th>';
echo '<td><b>' . htmlspecialchars($players_connected) . '</b>/' . htmlspecialchars($max_players) . '</td>';
echo '</tr>';
echo '<tr>';
echo '<th>Uptime (Hours)</th>';
echo '<td>' . htmlspecialchars($uptime_hours) . '</td>';
echo '</tr>';
echo '<tr>';
echo '<th>Total Vampires</th>';
echo '<td>' . htmlspecialchars($active_users) . '</td>';
echo '</tr>';
// Server FPS anzeigen
echo '<tr>';
echo '<th>Server FPS</th>';
echo '<td>' . htmlspecialchars($fps) . '</td>';
echo '</tr>';
echo '</table>';

echo '<h1>World Information</h1>';
echo '<table>';
echo '<tr>';
echo '<th>Territory Name</th>';
echo '<th>Players in region</th>';
echo '<th>Total Claims</th>';
echo '<th>Used</th>';
echo '<th>Free</th>';
echo '</tr>';

foreach ($regions as $displayName => $regionKey) {
    $label = json_encode(['region' => $regionKey]);

    $players_in_region = 'N/A';
    if (isset($metrics['vr_activity_users'][$label])) {
        $players_in_region = $metrics['vr_activity_users'][$label];
    }

    $territory_free = isset($metrics['vr_activity_free_territories'][$label]) ? $metrics['vr_activity_free_territories'][$label] : 0;
    $territory_used = isset($metrics['vr_activity_used_territories'][$label]) ? $metrics['vr_activity_used_territories'][$label] : 0;
    $territory_total = $territory_free + $territory_used;

    echo '<tr>';
    echo '<td>' . htmlspecialchars($displayName) . '</td>';
    echo '<td>' . htmlspecialchars($players_in_region) . '</td>';
    if ($territory_total > 0) {
        echo '<td>' . htmlspecialchars($territory_total) . '</td>';
        echo '<td class="status used">' . htmlspecialchars($territory_used) . '</td>';
        echo '<td class="status free">' . htmlspecialchars($territory_free) . '</td>';
    } else {
        echo '<td>-</td><td>-</td><td>-</td>';
    }
    echo '</tr>';
}

echo '</table>';
echo '</div>';
?>
