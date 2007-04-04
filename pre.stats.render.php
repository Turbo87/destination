<?
header ("Content-type: image/png");

/////////////////////////////////////////////
// Languagedingens
/////////////////////////////////////////////
$words['de']['distance'] = "km";
$words['de']['time'] = "Std.";
$words['de']['flights'] = "Flüge";
$words['de']['pilots'] = "Piloten";
$words['de']['total'] = "Gesamt";
$words['en']['distance'] = "km";
$words['en']['time'] = "Hrs.";
$words['en']['flights'] = "Flights";
$words['en']['pilots'] = "Pilots";
$words['en']['total'] = "Total";

/////////////////////////////////////////////
// PNG: Übersicht - Strecke
/////////////////////////////////////////////
$height = count($overview_distance)*30 + 30;
$img = imagecreatetruecolor(430, $height) or die();
$img_greenbar = imagecreatefromgif("templates/green.gif");

$col_white = imagecolorallocate($img, 255, 255, 255);
$col_black = imagecolorallocate($img, 0, 0, 0);
$col_gra = imagecolorallocate($img, 64, 64, 64);
$col_grb = imagecolorallocate($img, 128, 128, 128);
$col_grc = imagecolorallocate($img, 192, 192, 192);
imagefill($img, 0, 0, $col_white);

imageline($img, 74, 5, 74, $height - 5, $col_black);
imageline($img, 75, 5, 75, $height - 5, $col_gra);
imageline($img, 76, 6, 76, $height - 6, $col_grb);
imageline($img, 77, 6, 77, $height - 6, $col_grc);
$i = 0;
foreach ($overview_distance as $olc_year => $distance) {
  imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($distance/max($overview_distance)),14,1,10);
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", round($distance)." ".$words[$competition['lang']]['distance']);
  imagettftext($img, 8, 0, 270*($distance/max($overview_distance)) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", round($distance)." ".$words[$competition['lang']]['distance']);
  $box = imagettfbbox(12, 0, "templates/tahoma.ttf", $olc_year);
  imagettftext($img, 12, 0, 30, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $olc_year);
  $i++;
}
imagepng($img, "prerender/".$competition['id'].".overview_distance.png");
imagedestroy($img);

/////////////////////////////////////////////
// HTM: Übersicht - Strecke
/////////////////////////////////////////////
$temp = new Template("templates/".$competition['lang']."/stats.overview_distance.tpl");
$i = 0;
$temp_rows = "";
foreach ($overview_distance_300 as $olc_year => $distance) {
  $temp2 = new Template("templates/".$competition['lang']."/stats.overview_distance.row.tpl");
  $temp2->addVariable("year", $olc_year);
  $temp2->addVariable("odd", ($i % 2 == 0 ? "e" : "o"));
  $temp2->addVariable("300", $overview_distance_300[$olc_year]);
  $temp2->addVariable("500", $overview_distance_500[$olc_year]);
  $temp2->addVariable("700", $overview_distance_700[$olc_year]);
  $temp2->addVariable("1000", $overview_distance_1000[$olc_year]);
  $temp_rows .= $temp2->getOutput();
  $i++;
}
$temp->addVariable("rows", $temp_rows);
$f = fopen("prerender/".$competition['id'].".overview_distance.htm", "w");
fwrite($f, $temp->getOutput());
fclose($f);

/////////////////////////////////////////////
// PNG: Übersicht - Flüge
/////////////////////////////////////////////
$height = count($overview_flights)*30 + 30;
$img = imagecreatetruecolor(430, $height) or die();
$img_greenbar = imagecreatefromgif("templates/green.gif");

$col_white = imagecolorallocate($img, 255, 255, 255);
$col_black = imagecolorallocate($img, 0, 0, 0);
$col_gra = imagecolorallocate($img, 64, 64, 64);
$col_grb = imagecolorallocate($img, 128, 128, 128);
$col_grc = imagecolorallocate($img, 192, 192, 192);
imagefill($img, 0, 0, $col_white);

imageline($img, 74, 5, 74, $height - 5, $col_black);
imageline($img, 75, 5, 75, $height - 5, $col_gra);
imageline($img, 76, 6, 76, $height - 6, $col_grb);
imageline($img, 77, 6, 77, $height - 6, $col_grc);
$i = 0;
foreach ($overview_flights as $olc_year => $flights) {
  imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($flights/max($overview_flights)),14,1,10);
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $flights." ".$words[$competition['lang']]['flights']);
  imagettftext($img, 8, 0, 270*($flights/max($overview_flights)) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $flights." ".$words[$competition['lang']]['flights']);
  $box = imagettfbbox(12, 0, "templates/tahoma.ttf", $olc_year);
  imagettftext($img, 12, 0, 30, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $olc_year);
  $i++;
}
imagepng($img, "prerender/".$competition['id'].".overview_flights.png");
imagedestroy($img);

/////////////////////////////////////////////
// PNG: Übersicht - Flugzeit
/////////////////////////////////////////////
$height = count($overview_time)*30 + 30;
$img = imagecreatetruecolor(430, $height) or die();
$img_greenbar = imagecreatefromgif("templates/green.gif");

$col_white = imagecolorallocate($img, 255, 255, 255);
$col_black = imagecolorallocate($img, 0, 0, 0);
$col_gra = imagecolorallocate($img, 64, 64, 64);
$col_grb = imagecolorallocate($img, 128, 128, 128);
$col_grc = imagecolorallocate($img, 192, 192, 192);
imagefill($img, 0, 0, $col_white);

imageline($img, 74, 5, 74, $height - 5, $col_black);
imageline($img, 75, 5, 75, $height - 5, $col_gra);
imageline($img, 76, 6, 76, $height - 6, $col_grb);
imageline($img, 77, 6, 77, $height - 6, $col_grc);
$i = 0;
foreach ($overview_time as $olc_year => $time) {
  $hours = floor($time);
  $hours .= ":".addZeros(floor(($time-$hours)*60), 2);
    
  imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($time/max($overview_time)),14,1,10);
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $hours." ".$words[$competition['lang']]['time']);
  imagettftext($img, 8, 0, 270*($time/max($overview_time)) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $hours." ".$words[$competition['lang']]['time']);
  $box = imagettfbbox(12, 0, "templates/tahoma.ttf", $olc_year);
  imagettftext($img, 12, 0, 30, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $olc_year);
  $i++;
}
imagepng($img, "prerender/".$competition['id'].".overview_time.png");
imagedestroy($img);

/////////////////////////////////////////////
// PNG: Übersicht - Pilotenzahl
/////////////////////////////////////////////
$height = count($overview_pilots)*30 + 30;
$img = imagecreatetruecolor(430, $height) or die();
$img_greenbar = imagecreatefromgif("templates/green.gif");

$col_white = imagecolorallocate($img, 255, 255, 255);
$col_black = imagecolorallocate($img, 0, 0, 0);
$col_gra = imagecolorallocate($img, 64, 64, 64);
$col_grb = imagecolorallocate($img, 128, 128, 128);
$col_grc = imagecolorallocate($img, 192, 192, 192);
imagefill($img, 0, 0, $col_white);

imageline($img, 74, 5, 74, $height - 5, $col_black);
imageline($img, 75, 5, 75, $height - 5, $col_gra);
imageline($img, 76, 6, 76, $height - 6, $col_grb);
imageline($img, 77, 6, 77, $height - 6, $col_grc);
$i = 0;
foreach ($overview_pilots as $olc_year => $pilots) {    
  imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($pilots/max($overview_pilots)),14,1,10);
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $pilots." ".$words[$competition['lang']]['pilots']);
  imagettftext($img, 8, 0, 270*($pilots/max($overview_pilots)) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $pilots." ".$words[$competition['lang']]['pilots']);
  $box = imagettfbbox(12, 0, "templates/tahoma.ttf", $olc_year);
  imagettftext($img, 12, 0, 30, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $olc_year);
  $i++;
}
imagepng($img, "prerender/".$competition['id'].".overview_pilots.png");
imagedestroy($img);

/////////////////////////////////////////////
// HTM: Übersicht - Pilotenzahl
/////////////////////////////////////////////
$temp = new Template("templates/".$competition['lang']."/stats.overview_pilots.tpl");

$years = null;
foreach ($overview_pilots_distance as $olc_year => $tmp) {
  if (is_numeric($olc_year)) $years[] = $olc_year;
}
rsort($years);
$tabs = "";
$tables = "";

for ($i = 0; $i < count($years) && $i < 5; $i++) {
  if ($i == 0) $temp->addVariable("endyear", $years[$i]);
  if ($i == count($years) - 1 || $i == 4) $temp->addVariable("startyear", $years[$i]);
  
  $temp2 = new Template("templates/".$competition['lang']."/stats.overview_pilots.tab.tpl");
  $temp2->addVariable("year", $years[$i]);
  $temp2->addVariable("year_id", $years[$i]);
  $tabs .=  $temp2->getOutput();
  
  $temp2 = new Template("templates/".$competition['lang']."/stats.overview_pilots.table.tpl");
  $temp2->addVariable("year", $years[$i]);
  $temp2->addVariable("year_id", $years[$i]);
  $rows = "";
  $j = 0;
  foreach ($overview_pilots_distance[$years[$i]] as $pilot => $tmp) {
    $temp3 = new Template("templates/".$competition['lang']."/stats.overview_pilots.table.row.tpl");
    $temp3->addVariable("pilot", $pilot);
    $temp3->addVariable("distance", round($overview_pilots_distance[$years[$i]][$pilot]));
    $temp3->addVariable("flights", $overview_pilots_flights[$years[$i]][$pilot]);
    $hours = floor($overview_pilots_time[$years[$i]][$pilot]);
    $hours .= ":".addZeros(floor(($overview_pilots_time[$years[$i]][$pilot]-$hours)*60), 2);
    $temp3->addVariable("time", $hours);
    $temp3->fillStandard($competition);
    $temp3->addVariable("odd", ($j % 2 == 0 ? "e" : "o"));
    $rows .=  $temp3->getOutput();
    $j++;
  }
  $temp2->addVariable("rows", $rows);
  $tables .=  $temp2->getOutput();
}

$temp->addVariable("tabs", $tabs);
$temp->addVariable("tables", $tables);

$f = fopen("prerender/".$competition['id'].".overview_pilots.htm", "w");
fwrite($f, $temp->getOutput());
fclose($f);

updateProgress(2.5+(1/5)*0.5, array(0,1), $db, $competition);





/////////////////////////////////////////////
// HTM: Flugzeuge - Übersicht
/////////////////////////////////////////////
foreach ($planes_overview_distance as $plane => $tmp) {
	$temp = new Template("templates/".$competition['lang']."/stats.planes_overview.tpl");
	$temp->addVariable("plane", $plane);
	$temp->addVariable("distance", round($planes_overview_distance[$plane]));
	$temp->addVariable("flights", round($planes_overview_flights[$plane]));
  $hours = floor($planes_overview_time[$plane]);
  $hours .= ":".addZeros(floor(($planes_overview_time[$plane]-$hours)*60), 2);
	$temp->addVariable("time", $hours);
	$temp->addVariable("distance12", round($planes_overview12_distance[$plane]));
	$temp->addVariable("flights12", round($planes_overview12_flights[$plane]));
  $hours = floor($planes_overview12_time[$plane]);
  $hours .= ":".addZeros(floor(($planes_overview12_time[$plane]-$hours)*60), 2);
	$temp->addVariable("time12", $hours);
  $f = fopen(getFilenameOrNew("prerender/".$competition['id'].".planes_overview.".base64_encode($plane).".htm", $db, $competition), "w");
  fwrite($f, $temp->getOutput());
  fclose($f);
}
updateProgress(2.5+(1/5)*0.5+(1/5)*(1/5)*0.5, array(0,1), $db, $competition);

/////////////////////////////////////////////
// PNG: Flugzeuge - Strecke
/////////////////////////////////////////////
foreach ($planes_distance as $plane => $tmp) {
  $height = count($planes_distance[$plane])*30 + 30;
  $img = imagecreatetruecolor(430, $height) or die();
  $img_greenbar = imagecreatefromgif("templates/green.gif");
  
  $col_white = imagecolorallocate($img, 255, 255, 255);
  $col_black = imagecolorallocate($img, 0, 0, 0);
  $col_gra = imagecolorallocate($img, 64, 64, 64);
  $col_grb = imagecolorallocate($img, 128, 128, 128);
  $col_grc = imagecolorallocate($img, 192, 192, 192);
  imagefill($img, 0, 0, $col_white);
  
  imageline($img, 74, 5, 74, $height - 5, $col_black);
  imageline($img, 75, 5, 75, $height - 5, $col_gra);
  imageline($img, 76, 6, 76, $height - 6, $col_grb);
  imageline($img, 77, 6, 77, $height - 6, $col_grc);
  $i = 0;
  foreach ($planes_distance[$plane] as $olc_year => $distance) {
    imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($distance/max($planes_distance[$plane])),14,1,10);
    $box = imagettfbbox(8, 0, "templates/tahoma.ttf", round($distance)." ".$words[$competition['lang']]['distance']);
    imagettftext($img, 8, 0, 270*($distance/max($planes_distance[$plane])) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", round($distance)." ".$words[$competition['lang']]['distance']);
    $box = imagettfbbox(12, 0, "templates/tahoma.ttf", $olc_year);
    imagettftext($img, 12, 0, 30, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $olc_year);
    $i++;
  }
  imagepng($img, getFilenameOrNew("prerender/".$competition['id'].".planes_distance.".base64_encode($plane).".png", $db, $competition));
  imagedestroy($img);
}
updateProgress(2.5+(1/5)*0.5+(2/5)*(1/5)*0.5, array(0,1), $db, $competition);

/////////////////////////////////////////////
// PNG: Flugzeuge - Flüge
/////////////////////////////////////////////
foreach ($planes_flights as $plane => $tmp) {
  $height = count($planes_flights[$plane])*30 + 30;
  $img = imagecreatetruecolor(430, $height) or die();
  $img_greenbar = imagecreatefromgif("templates/green.gif");
  
  $col_white = imagecolorallocate($img, 255, 255, 255);
  $col_black = imagecolorallocate($img, 0, 0, 0);
  $col_gra = imagecolorallocate($img, 64, 64, 64);
  $col_grb = imagecolorallocate($img, 128, 128, 128);
  $col_grc = imagecolorallocate($img, 192, 192, 192);
  imagefill($img, 0, 0, $col_white);
  
  imageline($img, 74, 5, 74, $height - 5, $col_black);
  imageline($img, 75, 5, 75, $height - 5, $col_gra);
  imageline($img, 76, 6, 76, $height - 6, $col_grb);
  imageline($img, 77, 6, 77, $height - 6, $col_grc);
  $i = 0;
  foreach ($planes_flights[$plane] as $olc_year => $flights) {
    imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($flights/max($planes_flights[$plane])),14,1,10);
    $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $flights." ".$words[$competition['lang']]['flights']);
    imagettftext($img, 8, 0, 270*($flights/max($planes_flights[$plane])) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $flights." ".$words[$competition['lang']]['flights']);
    $box = imagettfbbox(12, 0, "templates/tahoma.ttf", $olc_year);
    imagettftext($img, 12, 0, 30, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $olc_year);
    $i++;
  }
  imagepng($img, getFilenameOrNew("prerender/".$competition['id'].".planes_flights.".base64_encode($plane).".png", $db, $competition));
  imagedestroy($img);
}
updateProgress(2.5+(1/5)*0.5+(3/5)*(1/5)*0.5, array(0,1), $db, $competition);

/////////////////////////////////////////////
// PNG: Flugzeuge - Flugzeit
/////////////////////////////////////////////
foreach ($planes_time as $plane => $tmp) {
  $height = count($planes_time[$plane])*30 + 30;
  $img = imagecreatetruecolor(430, $height) or die();
  $img_greenbar = imagecreatefromgif("templates/green.gif");
  
  $col_white = imagecolorallocate($img, 255, 255, 255);
  $col_black = imagecolorallocate($img, 0, 0, 0);
  $col_gra = imagecolorallocate($img, 64, 64, 64);
  $col_grb = imagecolorallocate($img, 128, 128, 128);
  $col_grc = imagecolorallocate($img, 192, 192, 192);
  imagefill($img, 0, 0, $col_white);
  
  imageline($img, 74, 5, 74, $height - 5, $col_black);
  imageline($img, 75, 5, 75, $height - 5, $col_gra);
  imageline($img, 76, 6, 76, $height - 6, $col_grb);
  imageline($img, 77, 6, 77, $height - 6, $col_grc);
  $i = 0;
  foreach ($planes_time[$plane] as $olc_year => $time) {
    $hours = floor($time);
    $hours .= ":".addZeros(floor(($time-$hours)*60), 2);
      
    imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($time/max($planes_time[$plane])),14,1,10);
    $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $hours." ".$words[$competition['lang']]['time']);
    imagettftext($img, 8, 0, 270*($time/max($planes_time[$plane])) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $hours." ".$words[$competition['lang']]['time']);
    $box = imagettfbbox(12, 0, "templates/tahoma.ttf", $olc_year);
    imagettftext($img, 12, 0, 30, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $olc_year);
    $i++;
  }
  imagepng($img, getFilenameOrNew("prerender/".$competition['id'].".planes_time.".base64_encode($plane).".png", $db, $competition));
  imagedestroy($img);
}
updateProgress(2.5+(1/5)*0.5+(4/5)*(1/5)*0.5, array(0,1), $db, $competition);

/////////////////////////////////////////////
// HTM: Flugzeuge - Piloten
/////////////////////////////////////////////
foreach ($planes_pilots_distance as $plane => $tmp) {
  $temp = new Template("templates/".$competition['lang']."/stats.planes_pilots.tpl");
  
  $years = null;
  foreach ($planes_pilots_distance[$plane] as $olc_year => $tmp) {
    if (is_numeric($olc_year)) $years[] = $olc_year;
  }
  rsort($years);
  $tabs = "";
  $tables = "";
  
  for ($i = 0; $i < count($years) && $i < 5; $i++) {
    if ($i == 0) $temp->addVariable("endyear", $years[$i]);
    if ($i == count($years) - 1 || $i == 4) $temp->addVariable("startyear", $years[$i]);
    
    $temp2 = new Template("templates/".$competition['lang']."/stats.planes_pilots.tab.tpl");
    $temp2->addVariable("year", $years[$i]);
    $temp2->addVariable("year_id", $years[$i]);
    $tabs .=  $temp2->getOutput();
    
    $temp2 = new Template("templates/".$competition['lang']."/stats.planes_pilots.table.tpl");
    $temp2->addVariable("year", $years[$i]);
    $temp2->addVariable("year_id", $years[$i]);
    $rows = "";
    $j = 0;
    foreach ($planes_pilots_distance[$plane][$years[$i]] as $pilot => $tmp) {
      $temp3 = new Template("templates/".$competition['lang']."/stats.planes_pilots.table.row.tpl");
      $temp3->addVariable("pilot", $pilot);
      $temp3->addVariable("distance", round($planes_pilots_distance[$plane][$years[$i]][$pilot]));
      $temp3->addVariable("flights", $planes_pilots_flights[$plane][$years[$i]][$pilot]);
      $hours = floor($planes_pilots_time[$plane][$years[$i]][$pilot]);
      $hours .= ":".addZeros(floor(($planes_pilots_time[$plane][$years[$i]][$pilot]-$hours)*60), 2);
      $temp3->addVariable("time", $hours);
      $temp3->addVariable("odd", ($j % 2 == 0 ? "e" : "o"));
      $temp3->fillStandard($competition);
      $rows .=  $temp3->getOutput();
      $j++;
    }
    $temp2->addVariable("rows", $rows);
    $tables .=  $temp2->getOutput();
  }
  
  $temp2 = new Template("templates/".$competition['lang']."/stats.planes_pilots.tab.tpl");
  $temp2->addVariable("year", $words[$competition['lang']]['total']);
  $temp2->addVariable("year_id", 'total');
  $tabs .=  $temp2->getOutput();
  
  $temp2 = new Template("templates/".$competition['lang']."/stats.planes_pilots.table.tpl");
  $temp2->addVariable("year", $words[$competition['lang']]['total']);
  $temp2->addVariable("year_id", 'total');
  $rows = "";
  $j = 0;
  foreach ($planes_pilots_distance[$plane]['total'] as $pilot => $tmp) {
    $temp3 = new Template("templates/".$competition['lang']."/stats.planes_pilots.table.row.tpl");
    $temp3->addVariable("pilot", $pilot);
    $temp3->addVariable("distance", round($planes_pilots_distance[$plane]['total'][$pilot]));
    $temp3->addVariable("flights", $planes_pilots_flights[$plane]['total'][$pilot]);
    $hours = floor($planes_pilots_time[$plane]['total'][$pilot]);
    $hours .= ":".addZeros(floor(($planes_pilots_time[$plane]['total'][$pilot]-$hours)*60), 2);
    $temp3->addVariable("time", $hours);
    $temp3->addVariable("odd", ($j % 2 == 0 ? "e" : "o"));
    $temp3->fillStandard($competition);
    $rows .=  $temp3->getOutput();
    $j++;
  }
  $temp2->addVariable("rows", $rows);
  $tables .=  $temp2->getOutput();

  $temp->addVariable("tabs", $tabs);
  $temp->addVariable("tables", $tables);
  
  $f = fopen(getFilenameOrNew("prerender/".$competition['id'].".planes_pilots.".base64_encode($plane).".htm", $db, $competition), "w");
  fwrite($f, $temp->getOutput());
  fclose($f);
}

updateProgress(2.5+(2/5)*0.5, array(0,1), $db, $competition);






/////////////////////////////////////////////
// PNG: Flugzeuge(Vergleich) - Strecke
/////////////////////////////////////////////
arsort($planes_overview_distance);
$height = count($planes_overview_distance)*30 + 30;
$img = imagecreatetruecolor(430, $height) or die();
$img_greenbar = imagecreatefromgif("templates/green.gif");

$col_white = imagecolorallocate($img, 255, 255, 255);
$col_black = imagecolorallocate($img, 0, 0, 0);
$col_gra = imagecolorallocate($img, 64, 64, 64);
$col_grb = imagecolorallocate($img, 128, 128, 128);
$col_grc = imagecolorallocate($img, 192, 192, 192);
imagefill($img, 0, 0, $col_white);

imageline($img, 74, 5, 74, $height - 5, $col_black);
imageline($img, 75, 5, 75, $height - 5, $col_gra);
imageline($img, 76, 6, 76, $height - 6, $col_grb);
imageline($img, 77, 6, 77, $height - 6, $col_grc);
$i = 0;
foreach ($planes_overview_distance as $plane => $distance) {
  imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($distance/max($planes_overview_distance)),14,1,10);
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", round($distance)." ".$words[$competition['lang']]['distance']);
  imagettftext($img, 8, 0, 270*($distance/max($planes_overview_distance)) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", round($distance)." ".$words[$competition['lang']]['distance']);
  $box = imagettfbbox(10, 0, "templates/tahoma.ttf", $plane);
  imagettftext($img, 10, 0, 15, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $plane);
  $i++;
}
imagepng($img, "prerender/".$competition['id'].".planes_compare_distance.png");
imagedestroy($img);

/////////////////////////////////////////////
// PNG: Flugzeuge(Vergleich) - Flüge
/////////////////////////////////////////////
arsort($planes_overview_flights);
$height = count($planes_overview_flights)*30 + 30;
$img = imagecreatetruecolor(430, $height) or die();
$img_greenbar = imagecreatefromgif("templates/green.gif");

$col_white = imagecolorallocate($img, 255, 255, 255);
$col_black = imagecolorallocate($img, 0, 0, 0);
$col_gra = imagecolorallocate($img, 64, 64, 64);
$col_grb = imagecolorallocate($img, 128, 128, 128);
$col_grc = imagecolorallocate($img, 192, 192, 192);
imagefill($img, 0, 0, $col_white);

imageline($img, 74, 5, 74, $height - 5, $col_black);
imageline($img, 75, 5, 75, $height - 5, $col_gra);
imageline($img, 76, 6, 76, $height - 6, $col_grb);
imageline($img, 77, 6, 77, $height - 6, $col_grc);
$i = 0;
foreach ($planes_overview_flights as $plane => $flights) {
  imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($flights/max($planes_overview_flights)),14,1,10);
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $flights." ".$words[$competition['lang']]['flights']);
  imagettftext($img, 8, 0, 270*($flights/max($planes_overview_flights)) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $flights." ".$words[$competition['lang']]['flights']);
  $box = imagettfbbox(10, 0, "templates/tahoma.ttf", $plane);
  imagettftext($img, 10, 0, 15, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $plane);
  $i++;
}
imagepng($img, "prerender/".$competition['id'].".planes_compare_flights.png");
imagedestroy($img);

/////////////////////////////////////////////
// PNG: Flugzeuge(Vergleich) - Flugzeit
/////////////////////////////////////////////
arsort($planes_overview_time);
$height = count($planes_overview_time)*30 + 30;
$img = imagecreatetruecolor(430, $height) or die();
$img_greenbar = imagecreatefromgif("templates/green.gif");

$col_white = imagecolorallocate($img, 255, 255, 255);
$col_black = imagecolorallocate($img, 0, 0, 0);
$col_gra = imagecolorallocate($img, 64, 64, 64);
$col_grb = imagecolorallocate($img, 128, 128, 128);
$col_grc = imagecolorallocate($img, 192, 192, 192);
imagefill($img, 0, 0, $col_white);

imageline($img, 74, 5, 74, $height - 5, $col_black);
imageline($img, 75, 5, 75, $height - 5, $col_gra);
imageline($img, 76, 6, 76, $height - 6, $col_grb);
imageline($img, 77, 6, 77, $height - 6, $col_grc);
$i = 0;
foreach ($planes_overview_time as $plane => $time) {
  $hours = floor($time);
  $hours .= ":".addZeros(floor(($time-$hours)*60), 2);
    
  imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($time/max($planes_overview_time)),14,1,10);
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $hours." ".$words[$competition['lang']]['time']);
  imagettftext($img, 8, 0, 270*($time/max($planes_overview_time)) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $hours." ".$words[$competition['lang']]['time']);
  $box = imagettfbbox(10, 0, "templates/tahoma.ttf", $plane);
  imagettftext($img, 10, 0, 15, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $plane);
  $i++;
}
imagepng($img, "prerender/".$competition['id'].".planes_compare_time.png");
imagedestroy($img);

updateProgress(2.5+(3/5)*0.5, array(0,1), $db, $competition);











/////////////////////////////////////////////
// HTM: Piloten - Übersicht
/////////////////////////////////////////////
foreach ($pilots_overview_distance as $pilot => $tmp) {
	$temp = new Template("templates/".$competition['lang']."/stats.pilots_overview.tpl");
	$temp->addVariable("pilot", $pilot);
	$temp->addVariable("distance", round($pilots_overview_distance[$pilot]));
	$temp->addVariable("flights", round($pilots_overview_flights[$pilot]));
  $hours = floor($pilots_overview_time[$pilot]);
  $hours .= ":".addZeros(floor(($pilots_overview_time[$pilot]-$hours)*60), 2);
	$temp->addVariable("time", $hours);
	$temp->addVariable("distance12", round($pilots_overview12_distance[$pilot]));
	$temp->addVariable("flights12", round($pilots_overview12_flights[$pilot]));
  $hours = floor($pilots_overview12_time[$pilot]);
  $hours .= ":".addZeros(floor(($pilots_overview12_time[$pilot]-$hours)*60), 2);
	$temp->addVariable("time12", $hours);
  $f = fopen(getFilenameOrNew("prerender/".$competition['id'].".pilots_overview.".base64_encode($pilot).".htm", $db, $competition), "w");
  fwrite($f, $temp->getOutput());
  fclose($f);
}
updateProgress(2.5+(3/5)*0.5+(1/5)*(1/5)*0.5, array(0,1), $db, $competition);

/////////////////////////////////////////////
// PNG: Piloten - Strecke
/////////////////////////////////////////////
foreach ($pilots_distance as $pilot => $tmp) {
  $height = count($pilots_distance[$pilot])*30 + 30;
  $img = imagecreatetruecolor(430, $height) or die();
  $img_greenbar = imagecreatefromgif("templates/green.gif");
  
  $col_white = imagecolorallocate($img, 255, 255, 255);
  $col_black = imagecolorallocate($img, 0, 0, 0);
  $col_gra = imagecolorallocate($img, 64, 64, 64);
  $col_grb = imagecolorallocate($img, 128, 128, 128);
  $col_grc = imagecolorallocate($img, 192, 192, 192);
  imagefill($img, 0, 0, $col_white);
  
  imageline($img, 74, 5, 74, $height - 5, $col_black);
  imageline($img, 75, 5, 75, $height - 5, $col_gra);
  imageline($img, 76, 6, 76, $height - 6, $col_grb);
  imageline($img, 77, 6, 77, $height - 6, $col_grc);
  $i = 0;
  foreach ($pilots_distance[$pilot] as $olc_year => $distance) {
    imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($distance/max($pilots_distance[$pilot])),14,1,10);
    $box = imagettfbbox(8, 0, "templates/tahoma.ttf", round($distance)." ".$words[$competition['lang']]['distance']);
    imagettftext($img, 8, 0, 270*($distance/max($pilots_distance[$pilot])) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", round($distance)." ".$words[$competition['lang']]['distance']);
    $box = imagettfbbox(12, 0, "templates/tahoma.ttf", $olc_year);
    imagettftext($img, 12, 0, 30, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $olc_year);
    $i++;
  }
  imagepng($img, getFilenameOrNew("prerender/".$competition['id'].".pilots_distance.".base64_encode($pilot).".png", $db, $competition));
  imagedestroy($img);
}
updateProgress(2.5+(3/5)*0.5+(2/5)*(1/5)*0.5, array(0,1), $db, $competition);

/////////////////////////////////////////////
// PNG: Piloten - Flüge
/////////////////////////////////////////////
foreach ($pilots_flights as $pilot => $tmp) {
  $height = count($pilots_flights[$pilot])*30 + 30;
  $img = imagecreatetruecolor(430, $height) or die();
  $img_greenbar = imagecreatefromgif("templates/green.gif");
  
  $col_white = imagecolorallocate($img, 255, 255, 255);
  $col_black = imagecolorallocate($img, 0, 0, 0);
  $col_gra = imagecolorallocate($img, 64, 64, 64);
  $col_grb = imagecolorallocate($img, 128, 128, 128);
  $col_grc = imagecolorallocate($img, 192, 192, 192);
  imagefill($img, 0, 0, $col_white);
  
  imageline($img, 74, 5, 74, $height - 5, $col_black);
  imageline($img, 75, 5, 75, $height - 5, $col_gra);
  imageline($img, 76, 6, 76, $height - 6, $col_grb);
  imageline($img, 77, 6, 77, $height - 6, $col_grc);
  $i = 0;
  foreach ($pilots_flights[$pilot] as $olc_year => $flights) {
    imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($flights/max($pilots_flights[$pilot])),14,1,10);
    $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $flights." ".$words[$competition['lang']]['flights']);
    imagettftext($img, 8, 0, 270*($flights/max($pilots_flights[$pilot])) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $flights." ".$words[$competition['lang']]['flights']);
    $box = imagettfbbox(12, 0, "templates/tahoma.ttf", $olc_year);
    imagettftext($img, 12, 0, 30, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $olc_year);
    $i++;
  }
  imagepng($img, getFilenameOrNew("prerender/".$competition['id'].".pilots_flights.".base64_encode($pilot).".png", $db, $competition));
  imagedestroy($img);
}
updateProgress(2.5+(3/5)*0.5+(3/5)*(1/5)*0.5, array(0,1), $db, $competition);

/////////////////////////////////////////////
// PNG: Piloten - Flugzeit
/////////////////////////////////////////////
foreach ($pilots_time as $pilot => $tmp) {
  $height = count($pilots_time[$pilot])*30 + 30;
  $img = imagecreatetruecolor(430, $height) or die();
  $img_greenbar = imagecreatefromgif("templates/green.gif");
  
  $col_white = imagecolorallocate($img, 255, 255, 255);
  $col_black = imagecolorallocate($img, 0, 0, 0);
  $col_gra = imagecolorallocate($img, 64, 64, 64);
  $col_grb = imagecolorallocate($img, 128, 128, 128);
  $col_grc = imagecolorallocate($img, 192, 192, 192);
  imagefill($img, 0, 0, $col_white);
  
  imageline($img, 74, 5, 74, $height - 5, $col_black);
  imageline($img, 75, 5, 75, $height - 5, $col_gra);
  imageline($img, 76, 6, 76, $height - 6, $col_grb);
  imageline($img, 77, 6, 77, $height - 6, $col_grc);
  $i = 0;
  foreach ($pilots_time[$pilot] as $olc_year => $time) {
    $hours = floor($time);
    $hours .= ":".addZeros(floor(($time-$hours)*60), 2);
      
    imagecopyresampled($img, $img_greenbar, 76,($i*30)+23,0,0,270*($time/max($pilots_time[$pilot])),14,1,10);
    $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $hours." ".$words[$competition['lang']]['time']);
    imagettftext($img, 8, 0, 270*($time/max($pilots_time[$pilot])) + 80, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $hours." ".$words[$competition['lang']]['time']);
    $box = imagettfbbox(12, 0, "templates/tahoma.ttf", $olc_year);
    imagettftext($img, 12, 0, 30, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $olc_year);
    $i++;
  }
  imagepng($img, getFilenameOrNew("prerender/".$competition['id'].".pilots_time.".base64_encode($pilot).".png", $db, $competition));
  imagedestroy($img);
}
updateProgress(2.5+(3/5)*0.5+(4/5)*(1/5)*0.5, array(0,1), $db, $competition);

/////////////////////////////////////////////
// HTM: Piloten - Flugzeuge
/////////////////////////////////////////////
foreach ($pilots_planes_distance as $pilot => $tmp) {
  $temp = new Template("templates/".$competition['lang']."/stats.pilots_planes.tpl");
  
  $years = null;
  foreach ($pilots_planes_distance[$pilot] as $olc_year => $tmp) {
    if (is_numeric($olc_year)) $years[] = $olc_year;
  }
  rsort($years);
  $tabs = "";
  $tables = "";
  
  for ($i = 0; $i < count($years) && $i < 5; $i++) {
    if ($i == 0) $temp->addVariable("endyear", $years[$i]);
    if ($i == count($years) - 1 || $i == 4) $temp->addVariable("startyear", $years[$i]);
    
    $temp2 = new Template("templates/".$competition['lang']."/stats.pilots_planes.tab.tpl");
    $temp2->addVariable("year", $years[$i]);
    $temp2->addVariable("year_id", $years[$i]);
    $tabs .=  $temp2->getOutput();
    
    $temp2 = new Template("templates/".$competition['lang']."/stats.pilots_planes.table.tpl");
    $temp2->addVariable("year", $years[$i]);
    $temp2->addVariable("year_id", $years[$i]);
    $rows = "";
    $j = 0;
    foreach ($pilots_planes_distance[$pilot][$years[$i]] as $plane => $tmp) {
      $temp3 = new Template("templates/".$competition['lang']."/stats.pilots_planes.table.row.tpl");
      $temp3->addVariable("plane", $plane);
      $temp3->addVariable("distance", round($pilots_planes_distance[$pilot][$years[$i]][$plane]));
      $temp3->addVariable("flights", $pilots_planes_flights[$pilot][$years[$i]][$plane]);
      $hours = floor($pilots_planes_time[$pilot][$years[$i]][$plane]);
      $hours .= ":".addZeros(floor(($pilots_planes_time[$pilot][$years[$i]][$plane]-$hours)*60), 2);
      $temp3->addVariable("time", $hours);
      $temp3->addVariable("odd", ($j % 2 == 0 ? "e" : "o"));
      $temp3->fillStandard($competition);
      $rows .=  $temp3->getOutput();
      $j++;
    }
    $temp2->addVariable("rows", $rows);
    $tables .=  $temp2->getOutput();
  }
  
  $temp2 = new Template("templates/".$competition['lang']."/stats.pilots_planes.tab.tpl");
  $temp2->addVariable("year", $words[$competition['lang']]['total']);
  $temp2->addVariable("year_id", 'total');
  $tabs .=  $temp2->getOutput();
  
  $temp2 = new Template("templates/".$competition['lang']."/stats.pilots_planes.table.tpl");
  $temp2->addVariable("year", $words[$competition['lang']]['total']);
  $temp2->addVariable("year_id", 'total');
  $rows = "";
  $j = 0;
  foreach ($pilots_planes_distance[$pilot]['total'] as $plane => $tmp) {
    $temp3 = new Template("templates/".$competition['lang']."/stats.pilots_planes.table.row.tpl");
    $temp3->addVariable("plane", $plane);
    $temp3->addVariable("distance", round($pilots_planes_distance[$pilot]['total'][$plane]));
    $temp3->addVariable("flights", $pilots_planes_flights[$pilot]['total'][$plane]);
    $hours = floor($pilots_planes_time[$pilot]['total'][$plane]);
    $hours .= ":".addZeros(floor(($pilots_planes_time[$pilot]['total'][$plane]-$hours)*60), 2);
    $temp3->addVariable("time", $hours);
    $temp3->addVariable("odd", ($j % 2 == 0 ? "e" : "o"));
    $temp3->fillStandard($competition);
    $rows .=  $temp3->getOutput();
    $j++;
  }
  $temp2->addVariable("rows", $rows);
  $tables .=  $temp2->getOutput();

  $temp->addVariable("tabs", $tabs);
  $temp->addVariable("tables", $tables);
  
  $f = fopen(getFilenameOrNew("prerender/".$competition['id'].".pilots_planes.".base64_encode($pilot).".htm", $db, $competition), "w");
  fwrite($f, $temp->getOutput());
  fclose($f);
}

updateProgress(2.5+(4/5)*0.5, array(0,1), $db, $competition);










/////////////////////////////////////////////
// PNG: Piloten(Vergleich) - Strecke
/////////////////////////////////////////////
arsort($pilots_overview_distance);
$height = count($pilots_overview_distance)*30 + 30;
$img = imagecreatetruecolor(430, $height) or die();
$img_greenbar = imagecreatefromgif("templates/green.gif");

$col_white = imagecolorallocate($img, 255, 255, 255);
$col_black = imagecolorallocate($img, 0, 0, 0);
$col_gra = imagecolorallocate($img, 64, 64, 64);
$col_grb = imagecolorallocate($img, 128, 128, 128);
$col_grc = imagecolorallocate($img, 192, 192, 192);
imagefill($img, 0, 0, $col_white);

imageline($img, 99, 5, 99, $height - 5, $col_black);
imageline($img, 100, 5, 100, $height - 5, $col_gra);
imageline($img, 101, 6, 101, $height - 6, $col_grb);
imageline($img, 102, 6, 102, $height - 6, $col_grc);
$i = 0;
foreach ($pilots_overview_distance as $pilot => $distance) {
  imagecopyresampled($img, $img_greenbar, 101,($i*30)+23,0,0,240*($distance/max($pilots_overview_distance)),14,1,10);
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", round($distance)." ".$words[$competition['lang']]['distance']);
  imagettftext($img, 8, 0, 240*($distance/max($pilots_overview_distance)) + 105, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", round($distance)." ".$words[$competition['lang']]['distance']);
  $pilotx = $pilot;
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $pilotx);
  while ($box[2] > 80) {
    $pilotx = substr($pilotx, 0, strlen($pilotx) - 4)."...";
    $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $pilotx);
  }
  imagettftext($img, 8, 0, 8, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $pilotx);
  $i++;
}
imagepng($img, "prerender/".$competition['id'].".pilots_compare_distance.png");
imagedestroy($img);

/////////////////////////////////////////////
// PNG: Piloten(Vergleich) - Flüge
/////////////////////////////////////////////
arsort($pilots_overview_flights);
$height = count($pilots_overview_flights)*30 + 30;
$img = imagecreatetruecolor(430, $height) or die();
$img_greenbar = imagecreatefromgif("templates/green.gif");

$col_white = imagecolorallocate($img, 255, 255, 255);
$col_black = imagecolorallocate($img, 0, 0, 0);
$col_gra = imagecolorallocate($img, 64, 64, 64);
$col_grb = imagecolorallocate($img, 128, 128, 128);
$col_grc = imagecolorallocate($img, 192, 192, 192);
imagefill($img, 0, 0, $col_white);

imageline($img, 99, 5, 99, $height - 5, $col_black);
imageline($img, 100, 5, 100, $height - 5, $col_gra);
imageline($img, 101, 6, 101, $height - 6, $col_grb);
imageline($img, 102, 6, 102, $height - 6, $col_grc);
$i = 0;
foreach ($pilots_overview_flights as $pilot => $flights) {
  imagecopyresampled($img, $img_greenbar, 101,($i*30)+23,0,0,240*($flights/max($pilots_overview_flights)),14,1,10);
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $flights." ".$words[$competition['lang']]['flights']);
  imagettftext($img, 8, 0, 240*($flights/max($pilots_overview_flights)) + 105, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $flights." ".$words[$competition['lang']]['flights']);
  $pilotx = $pilot;
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $pilotx);
  while ($box[2] > 80) {
    $pilotx = substr($pilotx, 0, strlen($pilotx) - 4)."...";
    $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $pilotx);
  }
  imagettftext($img, 8, 0, 8, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $pilotx);
  $i++;
}
imagepng($img, "prerender/".$competition['id'].".pilots_compare_flights.png");
imagedestroy($img);

/////////////////////////////////////////////
// PNG: Piloten(Vergleich) - Flugzeit
/////////////////////////////////////////////
arsort($pilots_overview_time);
$height = count($pilots_overview_time)*30 + 30;
$img = imagecreatetruecolor(430, $height) or die();
$img_greenbar = imagecreatefromgif("templates/green.gif");

$col_white = imagecolorallocate($img, 255, 255, 255);
$col_black = imagecolorallocate($img, 0, 0, 0);
$col_gra = imagecolorallocate($img, 64, 64, 64);
$col_grb = imagecolorallocate($img, 128, 128, 128);
$col_grc = imagecolorallocate($img, 192, 192, 192);
imagefill($img, 0, 0, $col_white);

imageline($img, 99, 5, 99, $height - 5, $col_black);
imageline($img, 100, 5, 100, $height - 5, $col_gra);
imageline($img, 101, 6, 101, $height - 6, $col_grb);
imageline($img, 102, 6, 102, $height - 6, $col_grc);
$i = 0;
foreach ($pilots_overview_time as $pilot => $time) {
  $hours = floor($time);
  $hours .= ":".addZeros(floor(($time-$hours)*60), 2);
    
  imagecopyresampled($img, $img_greenbar, 101,($i*30)+23,0,0,240*($time/max($pilots_overview_time)),14,1,10);
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $hours." ".$words[$competition['lang']]['time']);
  imagettftext($img, 8, 0, 240*($time/max($pilots_overview_time)) + 105, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $hours." ".$words[$competition['lang']]['time']);
  $pilotx = $pilot;
  $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $pilotx);
  while ($box[2] > 80) {
    $pilotx = substr($pilotx, 0, strlen($pilotx) - 4)."...";
    $box = imagettfbbox(8, 0, "templates/tahoma.ttf", $pilotx);
  }
  imagettftext($img, 8, 0, 8, ($i*30)+30-ceil($box[5]/2), $col_black, "templates/tahoma.ttf", $pilotx);
  $i++;
}
imagepng($img, "prerender/".$competition['id'].".pilots_compare_time.png");
imagedestroy($img);

?>