<?
$output = "";

foreach ($overview_distance as $olc_year => $distance) {
  $output .= "destination_stats_distance['$olc_year'] = '$distance';\r\n";
  if ($olc_year == max(array_keys($overview_distance)))
    $output .= "destination_stats_distance['now'] = '$distance';\r\n";
}
$output .= "\r\n";

$i = 0;
$res_ranks = db_query("SELECT * FROM %pre%rankings ORDER BY year DESC, points DESC, km DESC LIMIT 5", $db, $competition['id']);
while($rank = db_fetch_array($res_ranks)) {
  if ($i == 0)
    $output .= "destination_ranking['year'] = '".$rank['year']."';\r\n";
  $output .= "destination_ranking[$i]['place'] = '".($i+1)."';\r\n";
  $output .= "destination_ranking[$i]['pilot'] = '".$rank['id']."';\r\n";
  $output .= "destination_ranking[$i]['km'] = '".$rank['km']."';\r\n";
  $output .= "destination_ranking[$i]['points'] = '".$rank['points']."';\r\n";
  $output .= "\r\n";
  $i++;
}

$i = 0;
$res_flights = db_query("SELECT * FROM %pre%flights WHERE status = 'ready' ORDER BY date DESC LIMIT 5", $db, $competition['id']);
while($flight = db_fetch_array($res_flights)) {
  $output .= "destination_lastflights[$i]['date'] = '".$flight['date']."';\r\n";
  $output .= "destination_lastflights[$i]['date_readable'] = '".date("d.m.Y",$flight['date'])."';\r\n";
  $output .= "destination_lastflights[$i]['pilot'] = '".$flight['pilot']."';\r\n";
  $output .= "destination_lastflights[$i]['copilot'] = '".$flight['copilot']."';\r\n";
  $output .= "destination_lastflights[$i]['pilot_copilot'] = '".$flight['pilot'].($flight['copilot'] != "" ? " / ".$flight['copilot'] : "")."';\r\n";
  $output .= "destination_lastflights[$i]['club'] = '".$flight['club']."';\r\n";
  $output .= "destination_lastflights[$i]['plane'] = '".$flight['plane']."';\r\n";
  $output .= "destination_lastflights[$i]['plane_callsign'] = '".$flight['plane_callsign']."';\r\n";
  $output .= "destination_lastflights[$i]['plane_index'] = '".$flight['plane_index']."';\r\n";
  $output .= "destination_lastflights[$i]['airfield'] = '".$flight['airfield']."';\r\n";
  $output .= "destination_lastflights[$i]['km'] = '".$flight['km']."';\r\n";
  $output .= "destination_lastflights[$i]['olc_points'] = '".$flight['olc_points']."';\r\n";
  $output .= "destination_lastflights[$i]['points'] = '".$flight['points']."';\r\n";
  $output .= "destination_lastflights[$i]['speed'] = '".$flight['speed']."';\r\n";
  $output .= "destination_lastflights[$i]['factor'] = '".$flight['factor']."';\r\n";
  $output .= "\r\n";
  $i++;
}

$f = fopen("prerender/".$competition['id'].".js", "w");
fwrite($f, $output);
fclose($f)
?>