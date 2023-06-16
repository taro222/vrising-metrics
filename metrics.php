<?php

include 'config.php';

function fetch_metrics_data($metricsurl) {
    $data = file_get_contents($metricsurl);
    return $data;
}
function parse_metrics_data($data, $servername, $serverip) {
    $version_major = get_value_by_metric($data, 'vr_version_major');
    $version_minor = get_value_by_metric($data, 'vr_version_minor');
    $version_patch = get_value_by_metric($data, 'vr_version_patch');
    $version_revision = get_value_by_metric($data, 'vr_version_revision');
    $players_connected = get_value_by_metric($data, 'vr_users_connected');
    $max_players = get_value_by_metric($data, 'vr_users_connected_max');
    $uptime_seconds = get_value_by_metric($data, 'vr_uptime_seconds');
    $uptime_hours = floor($uptime_seconds / 3600);
    $active_users = get_value_by_metric($data, 'vr_users_taken');
    $players_none = get_value_by_metric($data, 'vr_activity_users{region="None"}');
    $players_other = get_value_by_metric($data, 'vr_activity_users{region="Other"}');
    $players_start_cave = get_value_by_metric($data, 'vr_activity_users{region="StartCave"}');
    $players_farbane_woods = get_value_by_metric($data, 'vr_activity_users{region="FarbaneWoods"}');
    $players_dunley_farmlands = get_value_by_metric($data, 'vr_activity_users{region="DunleyFarmlands"}');
    $players_hallowed_mountains = get_value_by_metric($data, 'vr_activity_users{region="HallowedMountains"}');
    $players_silverlight_hills = get_value_by_metric($data, 'vr_activity_users{region="SilverlightHills"}');
    $players_gloomrot_south = get_value_by_metric($data, 'vr_activity_users{region="Gloomrot_South"}');
    $players_gloomrot_north = get_value_by_metric($data, 'vr_activity_users{region="Gloomrot_North"}');
    $players_cursed = get_value_by_metric($data, 'vr_activity_users{region="CursedForest"}');
    $farbane_territory_free = get_value_by_metric($data, 'vr_activity_free_territories{region="FarbaneWoods"}');
    $farbane_territory_used = get_value_by_metric($data, 'vr_activity_used_territories{region="FarbaneWoods"}');
    $dunley_territory_free = get_value_by_metric($data, 'vr_activity_free_territories{region="DunleyFarmlands"}');
    $dunley_territory_used = get_value_by_metric($data, 'vr_activity_used_territories{region="DunleyFarmlands"}');
    $hallow_territory_free = get_value_by_metric($data, 'vr_activity_free_territories{region="HallowedMountains"}');
    $hallow_territory_used = get_value_by_metric($data, 'vr_activity_used_territories{region="HallowedMountains"}');
    $silver_territory_free = get_value_by_metric($data, 'vr_activity_free_territories{region="SilverlightHills"}');
    $silver_territory_used = get_value_by_metric($data, 'vr_activity_used_territories{region="SilverlightHills"}');
    $south_territory_used = get_value_by_metric($data, 'vr_activity_used_territories{region="Gloomrot_South"}');
    $south_territory_free = get_value_by_metric($data, 'vr_activity_used_territories{region="Gloomrot_South"}');
    $north_territory_free = get_value_by_metric($data, 'vr_activity_free_territories{region="Gloomrot_North"}');
    $north_territory_used = get_value_by_metric($data, 'vr_activity_used_territories{region="Gloomrot_North"}');
    $cursed_territory_free = get_value_by_metric($data, 'vr_activity_free_territories{region="CursedForest"}');
    $cursed_territory_used = get_value_by_metric($data, 'vr_activity_used_territories{region="CursedForest"}');

    echo '<body>';
    echo '<div class="container">';
    echo '<h1>Server Information</h1>';
    echo '<table>';
	echo '<tr>';
    echo '<th>Server Name</th>';
	echo '<td>' . $servername . '</td>';
    echo '</tr>';
	echo '<tr>';
    echo '<th>Server Address</th>';
	echo '<td>' . $serverip . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Server Version</th>';
	echo '<td>' . $version_major . '.' . $version_minor . '.' . $version_patch . '.' . $version_revision . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Connected Users</th>';
    echo '<td><b>' . $players_connected . '</b>/' . $max_players . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Uptime (Hours)</th>';
    echo '<td>' . $uptime_hours . '</td>';
    echo '</tr>';
	echo '<tr>';
    echo '<th>Total Vampires</th>';
    echo '<td>' . $active_users . '</td>';
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
	echo '<tr>';
    echo '<td>Starter Cave</td>';
	echo '<td>' . $players_start_cave . '</td>';
	echo '<td></td>';
	echo '<td></td>';
	echo '<td></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Farbane Woods</td>';
	$farbane_territory_total = $farbane_territory_used + $farbane_territory_free;
	echo '<td>' . $players_farbane_woods . '</td>';
	echo '<td>' . $farbane_territory_total . '</td>';
	echo '<td class="status used">' . $farbane_territory_used . '</td>';
	echo '<td class="status free">' . $farbane_territory_free . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Dunley Farmlands</td>';
	$dunley_territory_total = $dunley_territory_used + $dunley_territory_free;
	echo '<td>' . $players_dunley_farmlands . '</td>';
	echo '<td>' . $dunley_territory_total . '</td>';
	echo '<td class="status used">' . $dunley_territory_used . '</td>';
	echo '<td class="status free">' . $dunley_territory_free . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Cursed Forest</td>';
	$cursed_territory_total = $cursed_territory_used + $cursed_territory_free;
	echo '<td>' . $players_cursed . '</td>';
	echo '<td>' . $cursed_territory_total . '</td>';
	echo '<td class="status used">' . $cursed_territory_used . '</td>';
	echo '<td class="status free">' . $cursed_territory_free . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Hallowed Mountains</td>';
	$hallow_territory_total = $hallow_territory_used + $hallow_territory_free;
	echo '<td>' . $players_hallowed_mountains . '</td>';
	echo '<td>' . $hallow_territory_total . '</td>';
	echo '<td class="status used">' . $hallow_territory_used . '</td>';
	echo '<td class="status free">' . $hallow_territory_free . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Silverlight Hills</td>';
	$silver_territory_total = $silver_territory_used + $silver_territory_free;
	echo '<td>' . $players_silverlight_hills . '</td>';
	echo '<td>' . $silver_territory_total . '</td>';
	echo '<td class="status used">' . $silver_territory_used . '</td>';
	echo '<td class="status free">' . $silver_territory_free . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Gloomrot South</td>';
	$south_territory_total = $south_territory_used + $south_territory_free;
	echo '<td>' . $players_gloomrot_south . '</td>';
	echo '<td>' . $south_territory_total . '</td>';
	echo '<td class="status used">' . $south_territory_used . '</td>';
	echo '<td class="status free">' . $south_territory_free . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>Gloomrot North</td>';
	$north_territory_total = $north_territory_used + $north_territory_free;
	echo '<td>' . $players_gloomrot_north . '</td>';
	echo '<td>' . $north_territory_total . '</td>';
	echo '<td class="status used">' . $north_territory_used . '</td>';
	echo '<td class="status free">' . $north_territory_free . '</td>';
    echo '</tr>';
    echo '</table>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
}

function get_value_by_metric($data, $metric) {
    $lines = explode("\n", $data);
    foreach ($lines as $line) {
        if (strpos($line, $metric) === 0) {
            $values = explode(" ", $line);
            return $values[1];
        }
    }
    return 'N/A';
}

$metrics_data = fetch_metrics_data($metricsurl);
parse_metrics_data($metrics_data, $servername, $serverip);
?>