<?
if (!function_exists("ripOLC1")) {
  function ripOLC1($competition, $db, $club, $clubname, $year) {
    // ID-Liste erstellen
    $flight_ids = ripOLC1List($competition, $club, $year);
    
    // Flüge einzeln runterladen
    for ($i = 0; $i < count($flight_ids); $i++) {
    	$flight = ripOLC1Flight($competition, $db, $clubname, $flight_ids[$i], $year);
      
      // Falls Bilder vorhanden ebenfalls speichern
      if (is_array($flight) && array_key_exists("pics", $flight) && count($flight['pics']) > 0)
      	foreach ($flight['pics'] as $picurl) {
      		//ripOLC1Pics($competition, $picurl, $flight['id'].strstr($picurl, "."), $flight['date']);
      	}
    }
  }
}

if (!function_exists("ripOLC1List")) {
  function ripOLC1List($competition, $club, $year) {
    //http://www2.onlinecontest.org/olcphp/2005/ausw_wertung.php?olc=olc-i&spr=de&ein_verein=834
    $file = getHTTPFile("www2.onlinecontest.org", 80, "/olcphp/".$year."/ausw_wertung.php?olc=olc-i&spr=de&ein_verein=".$club);
	  if ($file == false) return false;

	  // Is halt so...
	  $file = strstr($file, "\r\n\r\n");
  	$file = str_replace("\r\n", "\n", $file);
  	$file = str_replace("\r", "\n", $file);
  	$file = str_replace("\n", "", $file);
  	$file = str_replace("&nbsp;", " ", $file);
  	$file = str_replace(">", ">\r\n", $file);
  	$file = stristr($file, "<FONT size=+2>");
  	$file = str_replace("</td>", "|~|", $file);
  	$file = substr($file, 0, strlen($file) - strlen(stristr($file, "<td colspan=\"11\" align=\"center\">")));
  	$file = str_replace("href=\"ausw_fluginfo.php", "href=\"\">ausw_fluginfo.php", $file);
  	$file = str_replace("\" target=\"_blank\">\r\ninfo", "", $file);
  	$file = str_replace("</tr>", "|~~~|", $file);
  	$file = strip_tags($file);
  	$file = html_entity_decode($file);
  	$file = explode("|~|", $file);
  	for ($i = 11; $i < count($file); $i++) {
  	  $flights[] = trim($file[$i]);
  	}
    $flights = array_reverse($flights);    

    $ids = null;
  	for ($i = 0; $i < count($flights); $i++) {
  		if (substr($flights[$i], 0, strlen("ausw_fluginfo.php?ref3=")) == "ausw_fluginfo.php?ref3=") {
  		  $flights[$i] = str_replace("ausw_fluginfo.php?ref3=", "", $flights[$i]);
  		  $flights[$i] = substr($flights[$i], 0, strlen($flights[$i]) - strlen(strstr($flights[$i], "&")));
  		  $ids[] = $flights[$i];
  		}
  	}

  	$flights = null;
  	$file = null;

    // ID-Liste zurückgeben
    return $ids;
  }
}

if (!function_exists("ripOLC1Flight")) {
  function ripOLC1Flight($competition, $db, $club, $id, $year) {
    //http://www2.onlinecontest.org/olcphp/2005/ausw_fluginfo.php?ref3=225011&ueb=N&olc=olc-d&spr=de
	  $file = getHTTPFile("www2.onlinecontest.org", 80, "/olcphp/".$year."/ausw_fluginfo.php?ref3=".$id."&ueb=N&olc=olc-i&spr=de");
	  if ($file == false) return false;

	  // Is halt so...
  	$file = strstr($file, "\r\n\r\n");
 	  $file = str_replace("\r\n", "\n", $file);
  	$file = str_replace("\r", "\n", $file);
  	$file = str_replace("\n", "", $file);
  	$file = str_replace("&nbsp;", " ", $file);
  	$file = str_replace(">", ">\r\n", $file);
  	$file = stristr($file, "Fluginformationen");
  	$file = str_replace("</td>", "|~|", $file);
  	$file = str_replace("src=\"/olc/".$year."/map/", ">/olc/".$year."/map/", $file);
  	$file = str_replace("src=\"/olc/".$year."/ENL/", ">/olc/".$year."/ENL/", $file);
  	$file = str_replace("<a href=\"dumpigc.php?olc=olc&igc=", "dumpigc.php?olc=olc&igc=", $file);
  	$file = html_entity_decode($file);
  	$file = strip_tags($file);
  	$file = explode("|~|", $file);
  	for ($i = 0; $i < count($file); $i++) {
  	  $infos[] = trim($file[$i]);
  	}

  	$flight = null;
  	$flight['year'] = $year;
  	// Array nach Daten durchsuchen und aufbereiten
    echo "<!--";
    print_r($infos);
    echo "//-->";
  	for ($i = 0; $i < count($infos); $i++) {
  	  if (substr($infos[$i],0,strlen("Fluginformationen")) == "Fluginformationen") {
  	    $flight['date'] = str_replace("Fluginformationen ", "", $infos[$i]);
  	    $flight['date_timestamp'] = mktime(12,0,0,substr($flight['date'],3,2),substr($flight['date'],0,2),substr($flight['date'],6,4));
  	  }
  	  if (strtolower($infos[$i]) == "pilot:") {
  	    $i++;
  	    $flight['pilot'] = $infos[$i];
  	  }
  	  if (strtolower($infos[$i]) == "co-pilot:") {
  	    $i++;
  	    $flight['copilot'] = $infos[$i];
  	  }
  	  if (strtolower($infos[$i]) == "kennzeichen:") {
  	    $i++;
  	    $flight['plane_callsign'] = $infos[$i];
  	    $flight['plane_callsign'] = fixPlaneCallsign($flight['plane_callsign']);
  	  }
  	  if (strtolower($infos[$i]) == "flugzeugtyp:") {
  	    $i++;
  	    $flight['plane'] = $infos[$i];
  	  }
  	  if (strtolower($infos[$i]) == "daec index:") {
  	    $i++;
  	    $flight['plane_index'] = $infos[$i];
  	  }
  	  if (strtolower($infos[$i]) == "startplatz:") {
  	    $i++;
  	    $flight['airfield'] = $infos[$i];
  	  }
  	  if (strtolower($infos[$i]) == "wertungsstrecke:") {
  	    $i++;
  	    $flight['km'] = str_replace(" km", "", $infos[$i]);
  	  }
  	  if (substr(strtolower($infos[$i]), strlen("punkte fur ")) == "den flug (mit index):") {
  	    $i++;
  	    $temp = explode("(", $infos[$i]);
  	    $flight['olc_points'] = trim($temp[0]);
  	    $flight['speed'] = str_replace(" km/h)", "", $temp[1]);
  	  }
  	  if (substr($infos[$i],0,5) == "/olc/") {
  	    $flight['pics'][] = str_replace("\">", "", $infos[$i]);
  	  }
  	}
    $flight['id'] = date("Y", $flight['date_timestamp'])."-".$id;
    if ($flight['km'] < 10) return;

    // Pilotenalias suchen und eintragen
    $flight['pilot'] = getAlias($flight['pilot'], $db, $competition);
    if ($flight['copilot'] != "") $flight['copilot'] = getAlias($flight['copilot'], $db, $competition);
        
    // In Datenbank einfügen/updaten
    $res_flight = db_query("SELECT * FROM %pre%flights WHERE id = '".$flight['id']."'", $db, $competition['id']);
    if (!($temp = db_fetch_array($res_flight))) {
      db_query("INSERT INTO %pre%flights VALUES('".$flight['id']."', ".
      $flight['date_timestamp'].", '".$flight['pilot']."', '".$flight['copilot']."', '".$club."', '".
      $flight['plane']."', '".$flight['plane_callsign']."', ".$flight['plane_index'].
      ", '".$flight['airfield']."', ".$flight['km'].", ".$flight['olc_points'].", ".
      $flight['speed'].", 0, 0, 'new')", $db, $competition['id']);
    } else {
      db_query('UPDATE %pre%flights SET date = '.$flight['date_timestamp'].', pilot = "'.$flight['pilot'].'",
        copilot = "'.$flight['copilot'].'", club = "'.$club.'", plane = "'.$flight['plane'].'", 
        plane_callsign = "'.$flight['plane_callsign'].'", plane_index = "'.$flight['plane_index'].'",
        airfield = "'.$flight['airfield'].'", km = "'.$flight['km'].'", olc_points = "'.$flight['olc_points'].'", 
        speed = "'.$flight['speed'].'" WHERE id = "'.$flight['id'].'"', $db, $competition['id']);
    }
    
    return $flight;
  }
}

if (!function_exists("ripOLC1Pics")) {
  function ripOLC1Pics($competition, $url, $filename, $flightdate) {
    // Wenn Bild zeitlich KEIN update benötigt abbrechen
  	if (file_exists("images/".$competition['id']."/".$filename) && filesize("images/".$competition['id']."/".$filename) > 0) {
  		$mtime = filemtime("images/".$competition['id']."/".$filename);
 		  if (time() - $flightdate > 60*60*24*7 || time() - $mtime < 60*60*6)
  			return false;
  	}
    
    // Bild runterladen
		$file = getHTTPFile("www2.onlinecontest.org", 80, $url);
		$file = explode("\r\n\r\n", $file);
		$fp = fopen("images/".$competition['id']."/".$filename, "w");
		for ($i = 1; $i < count($file); $i++) {
			fwrite($fp, $file[$i]);
		}
		fclose($fp);
  }
}
?>