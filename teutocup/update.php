<?
error_reporting(E_ALL);
set_time_limit(0);

$start = mktime(11,0,0,4,26,2009);
$end = mktime(13,0,0,8,31,2009);

function getHTTPFile ($host, $port, $filename) {
  $fp = fsockopen ($host, $port, $errno, $errstr, 5);
  if (!$fp) {
    return false;
  } else {
    $file = "";
    $r = "GET /".$filename." HTTP/1.0\r\n";
    $r .= "Host: $host\r\n";
    $r .= "\r\n";
    fputs ($fp, $r);
    while (!feof($fp)) {
      $file = $file.fgets($fp,128);
    }
    fclose($fp);
    if (trim($file) != "") {
      $f = explode("\r\n", $file);
      $x = explode(" ", $f[0]);

      if ($x[1] != "200") {
        return false;
      }
      else return $file;
    } else {
      return false;
    }
  }
}

function fixPlaneCallsign($c) {
  $c = trim($c);
  $c = strtoupper($c);
  $c = str_replace("*", "", $c);
  $c = str_replace(".", "", $c);
  $c = str_replace("_", "-", $c);
  $c = str_replace("+", "-", $c);
  $c = str_replace("D ", "D-", $c);
  while (strpos($c,"--") !== false) {
    $c = str_replace("--", "-", $c);
  }
  if (substr($c, -1) == ")") $c = substr($c, 0, strlen($c) - strlen(strrchr($c, "(")));
  if ($c == "COMPETITION-ID-:") $c = "";
  if ($c == "D-") $c = "";
  if ($c == "UNBEKANNT") $c = "";
  if ($c == "NOTSET") $c = "";
  if (substr($c, -1) == "-") $c = substr($c, 0, strlen($c) - 1);
  if (strlen($c) == 4 && (is_numeric($c) || ($c{0} == "K" && !is_numeric($c{1}) && !is_numeric($c{2}) && !is_numeric($c{3})))) $c = "D-".$c;
  if (substr($c,0,1) == "D" && strlen($c) == 5 && ((is_numeric(substr($c,1)) && $c{1} != "-") || ($c{1} == "K" && !is_numeric($c{2}) && !is_numeric($c{3}) && !is_numeric($c{4})))) $c = "D-".substr($c,1);
  if (substr($c,0,2) == "D-") {
    $x = substr($c, 2);
    if (strlen($x) > 4) {
      $x = str_replace("-", "", $x);
      $c = "D-".$x;
    }
    $x = substr($c, 2);
    if (strlen($x) > 4) {
      $c = "D-".substr($x,0,4);
    }
    
    $x = substr($c, 2);
    
    if (strlen($x) == 4 && !is_numeric($x)) {
      if ($x{0} != "K" || is_numeric($x{1}) || is_numeric($x{2}) || is_numeric($x{3}))
        $c = "";
    }
    if (strlen($x) < 4) $c = "";
  }
  return $c;
}

mysql_connect("localhost", "teutocup", "blablubb");
mysql_select_db("teutocup");

$res_config = mysql_query("SELECT * FROM config WHERE name = 'update_in_progress'");
if (!$res_config) die("error 1");
if (!($update_in_progress = mysql_fetch_array($res_config))) die("error 2");
$update_in_progress = $update_in_progress['value'];
if ($update_in_progress != "false" && !array_key_exists("overwrite", $_GET)) die("update in progress..."); 

mysql_query("UPDATE config SET value = 'true' WHERE name = 'update_in_progress'");



/*******************\
* Flugliste abrufen *
\*******************/

$file = getHTTPFile("www.onlinecontest.org", 80, "olc-2.0/gliding/flightsOfClub.html?cc=3320&st=olc&rt=olc&c=C0&paging=100000&sc=&sp=2009");
if ($file == false) return false;
$file = utf8_decode($file);

// Is halt so...
$file = stristr($file, '<table class="list"');
$file = stristr($file, '<tbody>');
$file = str_replace("\r\n", "\n", $file);
$file = str_replace("\r", "\n", $file);
$file = str_replace("\n", "", $file);
$file = str_replace("&nbsp;", " ", $file);
while (strpos($file, "  ") != false) {
  $file = str_replace("  ", " ", $file);
}
$file = str_replace(">", ">\r\n", $file);
$file = str_replace("</td>", "|~|", $file);
$file = str_replace("</th>", "|~|", $file);
$file = substr($file, 0, strlen($file) - strlen(stristr($file, '</tbody>')));
if (strpos($file, "Nothing found to display.") != false) $file = "";
$file = str_replace("href=\"flightinfo.html", "href=\"\">flightinfo.html", $file);
$file = str_replace("</tr>", "|~~~||~|", $file);
$file = strip_tags($file);

$file = str_replace('">"', "", $file);
$file = html_entity_decode($file);
$file = explode("|~|", $file);

$flights = null;
for ($i = 0; $i < count($file); $i++) {
  if (trim($file[$i]) != "")
    $flights[] = trim($file[$i]);
}

if (count($flights) > 0) {
  array_pop($flights);

  $f = implode("\r\n", $flights);
  $flights = null;
  $f = explode("|~~~|", $f);
  for ($i = 0; $i < count($f); $i++) {
    if (trim($f[$i]) != "")
      $flights[] = trim($f[$i]);
  }
  $flights = array_reverse($flights);
}

$ids = null;
for ($i = 0; $i < count($flights); $i++) {
  $date = explode("-", substr($flights[$i],0,10));
  if (count($date) == 3) {
    $date = mktime(12,0,0,$date[1],$date[2],$date[0]);
    $weekday = date("D", $date);
    if (($weekday == "Sun" || $weekday == "Sat") && ($date > $start && $date < $end)) {
      $id = stristr($flights[$i], "?flightId=");
      $id = str_replace("?flightId=", "", $id);
      $id = str_replace("\">", "", $id);
		  $ids[] = $id;
    }
  }
}

$flights = null;
$file = null;




/************************\
* Einzelne Flüge abrufen *
\************************/

foreach ($ids as $id) {
  // http://www.onlinecontest.org/olc-2.0/gliding/flightinfo.html?flightId=-1873372423
  $file = getHTTPFile("www.onlinecontest.org", 80, "olc-2.0/gliding/flightinfo.html?flightId=".$id);
  if ($file == false) return false;
  $file = utf8_decode($file);

  $file = strstr($file, "\r\n\r\n");
  $file = strstr($file, "<td id=\"frame_content\">");
  $file = str_replace("\r\n", "\n", $file);
  $file = str_replace("\r", "\n", $file);
  $file = str_replace("\n", "", $file);
  $file = str_replace("&nbsp;", " ", $file);
  $file = str_replace("ß", "ss", $file);
  $file = str_replace(">", ">\r\n", $file);
  $file = str_replace(" class=\"OLC-League\">", ">OLC-LEAGUE", $file);
  $file = str_replace("<div id=\"viewcontent_", "<div>ajax_id_", $file);
  
  $file = substr($file, 0, strlen($file) - strlen(stristr($file, '<div id="frame_bottom">')));
  $file = html_entity_decode($file);
  $file = strip_tags($file);

  $file = explode("\r\n", $file);
  $infos = null;
  for ($i = 0; $i < count($file); $i++) {
    if (trim($file[$i]) != "")
      $infos[] = trim($file[$i]);
  }
  
  $flight = null;
  
  $league_found = false;
  $flight['ajax_id'] = -1;
  $flight['pilot'] = "";
  $flight['copilot'] = "";
  $flight['km'] = 0;
  $flight['speed'] = 0;
  $flight['olc_points'] = 0;
  $flight['plane_index'] = 0;
  $flight['plane_callsign'] = "";
  // Array nach Daten durchsuchen und aufbereiten
  for ($i = 0; $i < count($infos); $i++) {
    $infos[$i] = trim($infos[$i]);
    if (strtolower($infos[$i]) == "olc-league") 
      $league_found = true;
    if (strtolower($infos[$i]) == "flight information") {
      $i++;
      while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
      if (strtolower($infos[$i]) == "-") {
        $i++;
        while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
        if ($flight['pilot'] == "") {
          $flight['pilot'] = $infos[$i];
          if (stristr($flight['pilot'], "(") != false) {
            $flight['pilot'] = substr($flight['pilot'], 0, strlen($flight['pilot']) - strlen(stristr($flight['pilot'], "(")) - 1);
          }
        }
        $i++;
        while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
        if (strtolower($infos[$i]) == "-") {
          $i++;
          while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
          $flight['date'] = trim($infos[$i]);
          $flight['date_timestamp'] = explode(".",$flight['date']);
          $flight['date_timestamp'] = mktime(12, 0, 0, $flight['date_timestamp'][1], $flight['date_timestamp'][0], $flight['date_timestamp'][2]);
          $flight['date_limit_timestamp'] = explode(".",$flight['date']);
          $flight['date_limit_timestamp'] = mktime(8, 0, 0, $flight['date_limit_timestamp'][1], $flight['date_limit_timestamp'][0] + 1, $flight['date_limit_timestamp'][2]);
        }
      }
    } 
    if (strtolower($infos[$i]) == "copilot:") {
      $i++;
      while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
      if ($flight['copilot'] == "") {
        $flight['copilot'] = $infos[$i];
        if (stristr($flight['copilot'], "(") != false) {
          $flight['copilot'] = substr($flight['copilot'], 0, strlen($flight['copilot']) - strlen(stristr($flight['copilot'], "(")) - 1);
        }
      }
    } 
    if (strtolower($infos[$i]) == "take-off location:") {
      $i++;
      while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
      if (strtolower($infos[$i]) != "type of glider:") {
        $flight['airfield'] = trim($infos[$i]);
        while (strpos($flight['airfield'], "  ") != false) $flight['airfield'] = str_replace("  ", " ", $flight['airfield']);
        $flight['airfield'] = str_replace("/ ", "/", $flight['airfield']);
      }
    } 
    if (strtolower($infos[$i]) == "type of glider:") {
      $i++;
      while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
      if (strtolower($infos[$i]) != "callsign:") {
        $flight['plane'] = trim($infos[$i]);
      }
    } 
    if (strtolower($infos[$i]) == "callsign:") {
      $i++;
      while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
      if (strtolower($infos[$i]) != "competition-id:") {
        $flight['plane_callsign'] = trim($infos[$i]);
        $flight['plane_callsign'] = fixPlaneCallsign($flight['plane_callsign']);
      }
    } 
    if (strtolower($infos[$i]) == "points for the flight:") {
      if ($flight['olc_points'] == 0 && $league_found) {
        $i++;
        while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
        $olc_points = trim($infos[$i]);
        $flight['olc_points'] = $olc_points;
      }
    }
    if (strtolower($infos[$i]) == "scoring distance:") {
      if ($flight['km'] == 0 && $league_found) {
        $i++;
        while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
        $km = trim($infos[$i]);
        $flight['km'] = trim(str_replace("km", "", $infos[$i]));;
      }
    }
    if (strtolower($infos[$i]) == "speed:") {
      if ($flight['speed'] == 0 && $league_found) {
        $i++;
        while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
        $speed = trim(str_replace("km/h", "", $infos[$i]));
        $flight['speed'] = $speed;
      }
    }
      if (strtolower($infos[$i]) == "club:") {
      $i++;
      while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
      $flight['club'] = $infos[$i];
    }
    if (substr($infos[$i], 0, 8) == "ajax_id_") {
      if ($flight['ajax_id'] == -1 && $league_found) {
        $flight['ajax_id'] = substr($infos[$i], 8);
        $flight['ajax_id'] = substr($flight['ajax_id'], 0, strlen($flight['ajax_id']) - strlen(stristr($flight['ajax_id'], "\"")));
      }
    }
    if (strtolower($infos[$i]) == "index:") {
      if ($flight['plane_index'] == 0) {
        $i++;
        while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
        $index = trim($infos[$i]);
        $flight['plane_index'] = $index;
      }
    }
  }    
  $flight['id'] = date("Y", $flight['date_timestamp'])."-".$id;
  
  $file = getHTTPFile("www.onlinecontest.org", 80, "olc-2.0/gliding/dataprov.html?id=".$flight["ajax_id"]."&nature=dsstat");
  if ($file == false) return false;
  $file = utf8_decode($file);

  $file = strstr($file, "\r\n\r\n");
  $file = str_replace("\r\n", "\n", $file);
  $file = str_replace("\r", "\n", $file);
  $file = str_replace("\n", "", $file);
  $file = str_replace("&nbsp;", " ", $file);
  $file = str_replace("ß", "ss", $file);
  $file = strstr($file, "<scoring");
  $file = str_replace("<scoring ", "", $file);
  $file = substr($file, 0, strlen($file) - strlen(stristr($file, '/>')));
  $file = str_replace("\" ", "\"\r\n", $file);
  
  $file = explode("\r\n", $file);
  $infos = null;
  for ($i = 0; $i < count($file); $i++) {
    if (trim($file[$i]) != "")
      $infos[] = trim($file[$i]);
  }
  
  for ($i = 0; $i < count($infos); $i++) {
    $infos[$i] = trim($infos[$i]);
    if (substr($infos[$i], 0, 8) == "igcvalid") {
      $flight['valid'] = substr($infos[$i], 10, strlen($infos[$i])-11);
    }
    if (substr($infos[$i], 0, 11) == "claimeddate") {
      $flight['claimeddate'] = substr($infos[$i], 13, strlen($infos[$i])-14);
      
      $flight['claimeddate_timestamp'] = explode(" ", str_replace(".", " ", str_replace(":", " ", $flight['claimeddate'])));
      $flight['claimeddate_timestamp'] = mktime($flight['claimeddate_timestamp'][3], $flight['claimeddate_timestamp'][4], $flight['claimeddate_timestamp'][5], $flight['claimeddate_timestamp'][1], $flight['claimeddate_timestamp'][0], $flight['claimeddate_timestamp'][2]);
    }
  }
  
  if ($flight['claimeddate_timestamp'] <= $flight['date_limit_timestamp'] && $flight['valid'] == "o") {
    // In Datenbank einfügen/updaten
    $res_flight = mysql_query("SELECT * FROM flights WHERE id = '".$flight['id']."'");
    if (!($temp = mysql_fetch_array($res_flight))) {
      mysql_query("INSERT INTO flights VALUES('".$flight['id']."', ".
      $flight['date_timestamp'].", '".$flight['pilot']."', '".$flight['copilot']."', '".$flight['club']."', '".
      $flight['plane']."', '".$flight['plane_callsign']."', ".$flight['plane_index'].
      ", '".$flight['airfield']."', ".$flight['km'].", ".$flight['olc_points'].", ".
      $flight['speed'].")");
    } else {
      mysql_query('UPDATE flights SET date = '.$flight['date_timestamp'].', pilot = "'.$flight['pilot'].'",
        copilot = "'.$flight['copilot'].'", club = "'.$flight['club'].'", plane = "'.$flight['plane'].'", 
        plane_callsign = "'.$flight['plane_callsign'].'", plane_index = "'.$flight['plane_index'].'",
        airfield = "'.$flight['airfield'].'", km = "'.$flight['km'].'", olc_points = "'.$flight['olc_points'].'", 
        speed = "'.$flight['speed'].'" WHERE id = "'.$flight['id'].'"');
    }
  }
}



/*****************\
* Flüge auswerten *
\*****************/

mysql_query("DELETE FROM ranking");
$res_flights = mysql_query("SELECT *, YEAR(FROM_UNIXTIME(date)) AS year, WEEKOFYEAR(FROM_UNIXTIME(date)) AS week FROM flights");
while ($flight = mysql_fetch_array($res_flights)) {  
  $res_rank = mysql_query("SELECT * FROM ranking WHERE year = ".$flight['year']." AND week = ".$flight['week']." AND pilot = '".$flight['pilot']."'");
  if (!($temp = mysql_fetch_array($res_rank))) {
    mysql_query("INSERT INTO ranking VALUES(".$flight['year'].",".$flight['week'].",-1,'".$flight['pilot']."','".$flight['id']."',".$flight['olc_points'].",-1)");
  } else {
    if ($temp['speed'] < $flight['speed'])
      mysql_query("UPDATE ranking SET speed = ".$flight['olc_points'].", id = '".$flight['id']."' WHERE year = ".$flight['year']." AND week = ".$flight['week']." AND pilot = '".$flight['pilot']."'");

  }
}



/***********************\
* Organisatoren löschen *
\***********************/

$res_weeks = mysql_query("DELETE FROM ranking WHERE pilot = 'Wolli Beyer' OR pilot = 'Christian S. Lang'");




/************************\
* Langsame Flüge löschen *
\************************/

$res_weeks = mysql_query("DELETE FROM ranking WHERE speed < 40");



/*******************\
* Ranking berechnen *
\*******************/

$res_weeks = mysql_query("SELECT MIN(week) as min, MAX(week) as max FROM ranking WHERE year = 2009");
if ($weeks = mysql_fetch_array($res_weeks)) {
  $min = $weeks['min'];
  $max = $weeks['max'];
} else {
  echo "ups";
}

for ($week = $min; $week <= $max; $week++) {
  $res_rankings = mysql_query("SELECT * FROM ranking WHERE year = 2009 AND week = ".$week." ORDER BY speed DESC");
  $i = 0;
  while ($rank = mysql_fetch_array($res_rankings)) {
    switch ($i) {
      case 0: $points = 10; break;
      case 1: $points = 8; break;
      case 2: $points = 7; break;
      case 3: $points = 6; break;
      case 4: $points = 5; break;
      case 5: $points = 4; break;
      case 6: $points = 3; break;
      case 7: $points = 2; break;
      case 8: $points = 1; break;
      default: $points = 0; break;
    }
    mysql_query("UPDATE ranking SET points = $points, raceweek = ".($week-$min+1)." WHERE year = ".$rank['year']." AND week = ".$rank['week']." AND pilot = '".$rank['pilot']."'");
    $i++;
  }
}

mysql_query("UPDATE config SET value = 'false' WHERE name = 'update_in_progress'");
mysql_close();
?>b