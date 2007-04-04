<?
if (!function_exists("ripOLC2")) {
  function ripOLC2($competition, $db, $club, $clubname, $year) {
    GLOBAL $DEBUG;
    GLOBAL $divider;
    
    // ID-Liste erstellen
    if ($DEBUG) log2file(">> Flugliste laden...");
    $flight_ids = ripOLC2List($competition, $club, $year);
    
    updateProgress(0, $divider, $db, $competition);
    
    // Flüge einzeln runterladen
    if ($DEBUG) log2file(">> Flugdetails laden...");
    for ($i = 0; $i < count($flight_ids); $i++) {
      $load_flight = false;
      
      $res_flight = db_query("SELECT * FROM %pre%flights WHERE id = '".$year."-".$flight_ids[$i]."' OR (id = '".($year-1)."-".$flight_ids[$i]."' AND MONTH(FROM_UNIXTIME(date)) > 9)", $db, $competition['id']);
      if ($temp_flight = db_fetch_array($res_flight)) {
        if ($DEBUG) log2file(">> Flug ".$temp_flight['id']." existiert bereits in Datenbank");
		if ($temp_flight['airfield'] == "")
		  $load_flight = true;
        if (time() - $temp_flight['date'] < 9*24*60*60) { // Jünger als 9 Tage
          if ($DEBUG) log2file(">>> Flug ist jünger als 9 Tage");
          if (rand(1,10) == 10) {
            if ($DEBUG) log2file(">>> Flug wird geupdated!");  
            $load_flight = true;
          }
        }
      } else {
        $load_flight = true;
      }

      if ($load_flight) {
        if ($DEBUG) log2file(">> Flug ".$flight_ids[$i]." laden...");
    	  $flight = ripOLC2Flight($competition, $db, $clubname, $flight_ids[$i]);
      }
      
      /*
      // Falls Bilder vorhanden ebenfalls speichern
      if (is_array($flight) && array_key_exists("pics", $flight) && count($flight['pics']) > 0)
        if ($DEBUG) log2file(">> Bilder zu Flug ".$flight_ids[$i]." laden...");
        if (is_array($flight['pics']))
      	  foreach ($flight['pics'] as $picurl) {
      		  //ripOLC2Pics($competition, $picurl, $flight['id'].strstr($picurl, "."), $flight['date']);
      	  }
      */	  
      updateProgress(($i+1)/count($flight_ids), $divider, $db, $competition);
    }
  }
}

if (!function_exists("ripOLC2List")) {
  function ripOLC2List($competition, $club, $year) {
    GLOBAL $DEBUG;
    
    //http://www.onlinecontest.org/olc-2.0/gliding/flightsOfClub.html?cc=834&st=olc&rt=olc&c=C0&sc=&sp=2009

    if ($DEBUG) log2file(">>> HTTP Request (http://www.onlinecontest.org/olc-2.0/gliding/flightsOfClub.html?cc=".$club."&st=olc&rt=olc&c=C0&paging=100000&sc=&sp=".$year.")");
    //$file = getHTTPFile("www.onlinecontest.org", 80, "olc-2.0/gliding/getScoring.html?clubId=".$club."&scoringId=201&paging=100000&scoringPeriod=".$year);
    $file = getHTTPFile("www.onlinecontest.org", 80, "olc-2.0/gliding/flightsOfClub.html?cc=".$club."&st=olc&rt=olc&c=C0&paging=100000&sc=&sp=".$year);
    if ($file == false) return false;
    $file = utf8_decode($file);

    // Is halt so...
    if ($DEBUG) log2file(">>> Parsing HTML...");
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
      $id = stristr($flights[$i], "?flightId=");
      $id = str_replace("?flightId=", "", $id);
      $id = str_replace("\">", "", $id);
		  $ids[] = $id;
  	}

  	$flights = null;
  	$file = null;

    // ID-Liste zurückgeben
    if ($DEBUG) log2file(">>> Found ".count($ids)." Flights");
    return $ids;
  }
}

if (!function_exists("ripOLC2Flight")) {
  function ripOLC2Flight($competition, $db, $club, $id) {
    GLOBAL $DEBUG;
    
    // http://www.onlinecontest.org/olc-2.0/gliding/flightinfo.html?flightId=-1379345583
    if ($DEBUG) log2file(">>>> HTTP Request (http://www.onlinecontest.org/olc-2.0/gliding/flightinfo.html?flightId=".$id.")");
    $file = getHTTPFile("www.onlinecontest.org", 80, "olc-2.0/gliding/flightinfo.html?flightId=".$id);
    if ($file == false) return false;
    $file = utf8_decode($file);

    // Is halt so...
    if ($DEBUG) log2file(">>>> Parsing HTML...");
    $file = strstr($file, "\r\n\r\n");
    $file = strstr($file, "<td id=\"frame_content\">");
    $file = str_replace("\r\n", "\n", $file);
    $file = str_replace("\r", "\n", $file);
    $file = str_replace("\n", "", $file);
    $file = str_replace("&nbsp;", " ", $file);
    $file = str_replace("ß", "ss", $file);
    $file = str_replace(">", ">\r\n", $file);
    //$file = str_replace("<div id=\"tabbar\">", "ID: ", $file);
    //$file = str_replace("\" class=\"OLC-Classic\">", "", $file);
    $file = substr($file, 0, strlen($file) - strlen(stristr($file, '<div id="frame_bottom">')));
    $file = html_entity_decode($file);
    $file = strip_tags($file);

    $file = explode("\r\n", $file);
  	for ($i = 0; $i < count($file); $i++) {
  	  if (trim($file[$i]) != "")
    	  $infos[] = trim($file[$i]);
  	}

    $flight = null;
  	
    $flight['pilot'] = "";
    $flight['copilot'] = "";
    $flight['km'] = 0;
    $flight['speed'] = 0;
    $flight['olc_points'] = 0;
    $flight['plane_index'] = 0;
    $flight['plane_callsign'] = "";
    $flight['pics'] = null;
    // Array nach Daten durchsuchen und aufbereiten
    for ($i = 0; $i < count($infos); $i++) {
      $infos[$i] = trim($infos[$i]);
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
      if (strtolower($infos[$i]) == "takeoff location:") {
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
        if ($flight['olc_points'] == 0) {
          $i++;
          while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
          $olc_points = trim($infos[$i]);
          if (!is_numeric($olc_points)) $olc_points = 0;
          $flight['olc_points'] = $olc_points;
        }
      }
      if (strtolower($infos[$i]) == "scoring distance:") {
        if ($flight['km'] == 0) {
          $i++;
          while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
          $km = trim($infos[$i]);
          $flight['km'] = trim(str_replace("km", "", $infos[$i]));;
        }
      }
      if (strtolower($infos[$i]) == "speed:") {
        if ($flight['speed'] == 0) {
          $i++;
          while (trim($infos[$i]) == "" && $i < count($infos)) $i++;
          $speed = trim(str_replace("km/h", "", $infos[$i]));
          $flight['speed'] = $speed;
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
    
    // http://www.onlinecontest.org/olc-2.0/gliding/dataprov.html?id=331756&nature=dsstat
    /*
    if ($DEBUG) log2file(">>>> HTTP-XML Request");
	  $file = getHTTPFile("www.onlinecontest.org", 80, "olc-2.0/gliding/dataprov.html?id=".$flight['olc_c_id']."&nature=dsstat");
	  if ($file == false) return false;

	  // Is halt so...
	  if ($DEBUG) log2file(">>>> Parsing HTML-XML...");
    $file = strstr($file, "\r\n\r\n");
    $file = strstr($file, "<scoring");
    $file = str_replace("<scoring ", "", $file);
    $file = substr($file, 0, strlen($file) - strlen(stristr($file, '/>')));
    $file = str_replace("\r\n", "\n", $file);
    $file = str_replace("\r", "\n", $file);
    $file = str_replace("\n", "", $file);
    $file = str_replace("&nbsp;", " ", $file);
    $file = str_replace("ß", "ss", $file);
    $file = str_replace("=\"", "=", $file);
    $file = str_replace("\"", "\r\n", $file);

    $file = explode("\r\n", $file);
    $infos = null;
  	for ($i = 0; $i < count($file); $i++) {
  	  if (trim($file[$i]) != "")
    	  $infos[] = trim($file[$i]);
  	}
  	$file = "";
  	
  	// Array nach Daten durchsuchen und aufbereiten
    for ($i = 0; $i < count($infos); $i++) {
      $infos[$i] = trim($infos[$i]);
      if (strtolower(substr($infos[$i],0,9)) == "distance=") {
        $flight['km'] = substr(trim($infos[$i]),9);
      }
      if (strtolower(substr($infos[$i],0,7)) == "points=") {
        $flight['olc_points'] = substr(trim($infos[$i]),7);
      }
      if (strtolower(substr($infos[$i],0,6)) == "speed=") {
        $flight['speed'] = substr(trim($infos[$i]),6);
      }
    }
    */
    
    if ($flight['km'] < 10) return;
    
    // Pilotenalias suchen und eintragen
    $flight['pilot'] = getAlias($flight['pilot'], $db, $competition);
    if ($flight['copilot'] != "") $flight['copilot'] = getAlias($flight['copilot'], $db, $competition);

    // In Datenbank einfügen/updaten
    $res_flight = db_query("SELECT * FROM %pre%flights WHERE id = '".$flight['id']."'", $db, $competition['id']);
    if (!($temp = db_fetch_array($res_flight))) {
      if ($DEBUG) log2file(">>>> Adding Flight ".$flight['id']." to DB");
      if ($DEBUG) log2file(">>>> MYSQL: INSERT INTO %pre%flights VALUES('".$flight['id']."', ".
      $flight['date_timestamp'].", '".$flight['pilot']."', '".$flight['copilot']."', '".$club."', '".
      $flight['plane']."', '".$flight['plane_callsign']."', ".$flight['plane_index'].
      ", '".$flight['airfield']."', ".$flight['km'].", ".$flight['olc_points'].", ".
      $flight['speed'].", 0, 0, 'new')");
      db_query("INSERT INTO %pre%flights VALUES('".$flight['id']."', ".
      $flight['date_timestamp'].", '".$flight['pilot']."', '".$flight['copilot']."', '".$club."', '".
      $flight['plane']."', '".$flight['plane_callsign']."', ".$flight['plane_index'].
      ", '".$flight['airfield']."', ".$flight['km'].", ".$flight['olc_points'].", ".
      $flight['speed'].", 0, 0, 'new')", $db, $competition['id']);
    } else {
      if ($DEBUG) log2file(">>>> Updating Flight ".$flight['id']." in DB");
      db_query('UPDATE %pre%flights SET date = '.$flight['date_timestamp'].', pilot = "'.$flight['pilot'].'",
        copilot = "'.$flight['copilot'].'", club = "'.$club.'", plane = "'.$flight['plane'].'", 
        plane_callsign = "'.$flight['plane_callsign'].'", plane_index = "'.$flight['plane_index'].'",
        airfield = "'.$flight['airfield'].'", km = "'.$flight['km'].'", olc_points = "'.$flight['olc_points'].'", 
        speed = "'.$flight['speed'].'" WHERE id = "'.$flight['id'].'"', $db, $competition['id']);
    }
    
    return $flight;
  }
}

if (!function_exists("ripOLC2Pics")) {
  function ripOLC2Pics($competition, $url, $filename, $flightdate) {
    // Wenn Bild zeitlich KEIN update benÃ¶tigt abbrechen
  	if (file_exists("images/".$competition['id']."/".$filename) && filesize("images/".$competition['id']."/".$filename) > 0) {
  		$mtime = filemtime("images/".$competition['id']."/".$filename);
 		  if (time() - $flightdate > 60*60*24*7 || time() - $mtime < 60*60*6)
  			return false;
  	}

    // Bild runterladen
		$file = getHTTPFile("www.onlinecontest.org", 80, $url);
		$file = explode("\r\n\r\n", $file);
		$fp = fopen("images/".$competition['id']."/".$filename, "w");
		for ($i = 1; $i < count($file); $i++) {
			fwrite($fp, $file[$i]);
		}
		fclose($fp);
  }
}
?>