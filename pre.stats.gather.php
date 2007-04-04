<?
// OH MY GOD!!
$res_flights = db_query("SELECT * FROM %pre%flights ORDER BY date", $db, $competition['id']);
$temp_total = db_result_count($res_flights);
$i = 0;
while($flight = db_fetch_array($res_flights)) {
  $olc_year = date("Y", $flight['date']);
  if ($flight['date'] >= mktime(0,0,0,11,1,$olc_year))
    $olc_year++;
       
  $overview_distance[$olc_year] += $flight['km'];
  if ($flight['km'] >= 300) $overview_distance_300[$olc_year]++;
  if ($flight['km'] >= 500) $overview_distance_500[$olc_year]++;
  if ($flight['km'] >= 700) $overview_distance_700[$olc_year]++;
  if ($flight['km'] >= 1000) $overview_distance_1000[$olc_year]++;
  $overview_flights[$olc_year]++;
  if ($flight['speed'] > 0) $overview_time[$olc_year] += ($flight['km']/$flight['speed']);
  if (!is_array($overview_pilots[$olc_year]) || array_search($flight['pilot'], $overview_pilots[$olc_year]) === false) $overview_pilots[$olc_year][] = $flight['pilot'];
  $overview_pilots_distance[$olc_year][$flight['pilot']] += $flight['km'];
  $overview_pilots_flights[$olc_year][$flight['pilot']]++;
  if ($flight['speed'] > 0) $overview_pilots_time[$olc_year][$flight['pilot']] += ($flight['km']/$flight['speed']);
  
  if (trim($flight['plane_callsign']) != "") {
    $planes_overview_distance[$flight['plane_callsign']] += $flight['km'];
    $planes_overview_flights[$flight['plane_callsign']]++;
    if ($flight['speed'] > 0) $planes_overview_time[$flight['plane_callsign']] += ($flight['km']/$flight['speed']);
    if (time() - $flight['date'] < (60*60*24*365)) {
      $planes_overview12_distance[$flight['plane_callsign']] += $flight['km'];
      $planes_overview12_flights[$flight['plane_callsign']]++;
      if ($flight['speed'] > 0) $planes_overview12_time[$flight['plane_callsign']] += ($flight['km']/$flight['speed']);
    }
    $planes_distance[$flight['plane_callsign']][$olc_year] += $flight['km'];
    $planes_flights[$flight['plane_callsign']][$olc_year]++;
    if ($flight['speed'] > 0) $planes_time[$flight['plane_callsign']][$olc_year] += ($flight['km']/$flight['speed']);
    $planes_pilots_distance[$flight['plane_callsign']][$olc_year][$flight['pilot']] += $flight['km'];
    $planes_pilots_flights[$flight['plane_callsign']][$olc_year][$flight['pilot']]++;
    if ($flight['speed'] > 0) $planes_pilots_time[$flight['plane_callsign']][$olc_year][$flight['pilot']] += ($flight['km']/$flight['speed']);
    $planes_pilots_distance[$flight['plane_callsign']]['total'][$flight['pilot']] += $flight['km'];
    $planes_pilots_flights[$flight['plane_callsign']]['total'][$flight['pilot']]++;
    if ($flight['speed'] > 0) $planes_pilots_time[$flight['plane_callsign']]['total'][$flight['pilot']] += ($flight['km']/$flight['speed']);
  }
  
  $pilots_overview_distance[$flight['pilot']] += $flight['km'];
  $pilots_overview_flights[$flight['pilot']]++;
  if ($flight['speed'] > 0) $pilots_overview_time[$flight['pilot']] += ($flight['km']/$flight['speed']);
  if (time() - $flight['date'] < (60*60*24*365)) {
    $pilots_overview12_distance[$flight['pilot']] += $flight['km'];
    $pilots_overview12_flights[$flight['pilot']]++;
    if ($flight['speed'] > 0) $pilots_overview12_time[$flight['pilot']] += ($flight['km']/$flight['speed']);
  }
  $pilots_distance[$flight['pilot']][$olc_year] += $flight['km'];
  $pilots_flights[$flight['pilot']][$olc_year]++;
  if ($flight['speed'] > 0) $pilots_time[$flight['pilot']][$olc_year] += ($flight['km']/$flight['speed']);
  if (trim($flight['plane_callsign']) != "") {
    $pilots_planes_distance[$flight['pilot']][$olc_year][$flight['plane_callsign']] += $flight['km'];
    $pilots_planes_flights[$flight['pilot']][$olc_year][$flight['plane_callsign']]++;
    if ($flight['speed'] > 0) $pilots_planes_time[$flight['pilot']][$olc_year][$flight['plane_callsign']] += ($flight['km']/$flight['speed']);
    $pilots_planes_distance[$flight['pilot']]['total'][$flight['plane_callsign']] += $flight['km'];
    $pilots_planes_flights[$flight['pilot']]['total'][$flight['plane_callsign']]++;
    if ($flight['speed'] > 0) $pilots_planes_time[$flight['pilot']]['total'][$flight['plane_callsign']] += ($flight['km']/$flight['speed']);
  }
  $i++;
  updateProgress(2+($i/$temp_total)*0.25, array(0,1), $db, $competition);
}

foreach ($overview_pilots as $olc_year => $tmp) {
	$overview_pilots[$olc_year] = count($tmp);
}
updateProgress(2.25+(1/10)*0.25, array(0,1), $db, $competition);
foreach ($overview_pilots_distance as $olc_year => $tmp) {
  ksort($tmp);
	$overview_pilots_distance[$olc_year] = $tmp;
}
updateProgress(2.25+(2/10)*0.25, array(0,1), $db, $competition);
foreach ($overview_pilots_flights as $olc_year => $tmp) {
  ksort($tmp);
	$overview_pilots_flights[$olc_year] = $tmp;
}
updateProgress(2.25+(3/10)*0.25, array(0,1), $db, $competition);
foreach ($overview_pilots_time as $olc_year => $tmp) {
  ksort($tmp);
	$overview_pilots_time[$olc_year] = $tmp;
}
updateProgress(2.25+(4/10)*0.25, array(0,1), $db, $competition);


foreach ($planes_pilots_distance as $plane => $tmp) {
  foreach ($tmp as $olc_year => $tmp2) {
    ksort($tmp2);
  	$planes_pilots_distance[$plane][$olc_year] = $tmp2;
  }
}
updateProgress(2.25+(5/10)*0.25, array(0,1), $db, $competition);
foreach ($planes_pilots_flights as $plane => $tmp) {
  foreach ($tmp as $olc_year => $tmp2) {
    ksort($tmp2);
  	$planes_pilots_flights[$plane][$olc_year] = $tmp2;
  }
}
updateProgress(2.25+(6/10)*0.25, array(0,1), $db, $competition);
foreach ($planes_pilots_time as $plane => $tmp) {
  foreach ($tmp as $olc_year => $tmp2) {
    ksort($tmp2);
  	$planes_pilots_time[$plane][$olc_year] = $tmp2;
  }
}
updateProgress(2.25+(7/10)*0.25, array(0,1), $db, $competition);


ksort($pilots_overview_distance);
ksort($pilots_overview_flights);
ksort($pilots_overview_time);
ksort($pilots_overview12_distance);
ksort($pilots_overview12_flights);
ksort($pilots_overview12_time);
ksort($pilots_distance);
ksort($pilots_flights);
ksort($pilots_time);
ksort($pilots_planes_distance);
ksort($pilots_planes_flights);
ksort($pilots_planes_time);
foreach ($pilots_planes_distance as $pilot => $tmp) {
  foreach ($tmp as $olc_year => $tmp2) {
    ksort($tmp2);
  	$pilots_planes_distance[$pilot][$olc_year] = $tmp2;
  }
}
updateProgress(2.25+(8/10)*0.25, array(0,1), $db, $competition);
foreach ($pilots_planes_flights as $pilot => $tmp) {
  foreach ($tmp as $olc_year => $tmp2) {
    ksort($tmp2);
  	$pilots_planes_flights[$pilot][$olc_year] = $tmp2;
  }
}
updateProgress(2.25+(9/10)*0.25, array(0,1), $db, $competition);
foreach ($pilots_planes_time as $pilot => $tmp) {
  foreach ($tmp as $olc_year => $tmp2) {
    ksort($tmp2);
  	$pilots_planes_time[$pilot][$olc_year] = $tmp2;
  }
}
updateProgress(2.5, array(0,1), $db, $competition);
?>