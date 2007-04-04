<?php
/*
 * index.flight.php
 * Created on 04.04.2007 by Tobias Bieniek
 */
 
$error = "";
// Wenn eine gültige id vorhanden Flug anzeigen
if (array_key_exists("id", $_GET)) {
  $id = $_GET['id'];

  // Template laden
  $temp = new Template("templates/".$competition['lang']."/flight.tpl");
  
  // Flug aus Datenbank auslesen (Fehler anzeigen falls nicht vorhanden)
  $res_flight = db_query("SELECT * FROM %pre%flights WHERE id = '".$id."'", $db, $competition['id']);
  if ($flight = db_fetch_array($res_flight)) {
    // Template mit Variablen füllen    
    $temp->addVariable("date", date("d.m.Y", $flight['date']));
    $temp->addVariable("pilot", $flight['pilot']);
    
    $copilot = "";
    if ($flight['copilot'] != "") {
      $co = new Template("templates/".$competition['lang']."/flight.copilot.tpl");
      $co->fillStandard($competition);
      $co->addVariable("copilot", $flight['copilot']);
      $copilot = $co->getOutput();
      $co = null;
    }
    $temp->addVariable("copilot", $copilot);
    
    if (substr($flight['id'], 0, 4) >= 2007) {
      // http://www.onlinecontest.org/olc-2.0/gliding/flightinfo.html?flightId=
      $flight_link = "http://www.onlinecontest.org/olc-2.0/gliding/flightinfo.html?flightId=".substr($flight['id'], 5);
    } else {
      // http://www2.onlinecontest.org/olcphp/2005/ausw_fluginfo.php?ref3=225011&ueb=N&olc=olc-d&spr=de
      $flight_link = "http://www2.onlinecontest.org/olcphp/".substr($flight['id'], 0, 4)."/ausw_fluginfo.php?ref3=".substr($flight['id'], 5)."&ueb=N&olc=olc-d&spr=de";
    }
    $temp->addVariable("olc_flight_link", $flight_link);
    $temp->addVariable("factor", $flight['factor']);
    $temp->addVariable("club", $flight['club']);
    $temp->addVariable("plane", $flight['plane']);
    $temp->addVariable("plane_callsign", $flight['plane_callsign']);
    $temp->addVariable("plane_index", $flight['plane_index']);
    $temp->addVariable("airfield", $flight['airfield']);
    $temp->addVariable("km", $flight['km']);
    $temp->addVariable("points", round($flight['points']*100)/100);
    $temp->addVariable("olc", $flight['olc_points']);
    $temp->addVariable("speed", $flight['speed']);
    
    $images = "";
    if (file_exists("images/".$competition['id']."/".$id.".jpg")) 
      $images .= "<img src='images/".$competition['id']."/".$id.".jpg' /><br />";
    if (file_exists("images/".$competition['id']."/".$id.".gif")) 
      $images .= "<img src='images/".$competition['id']."/".$id.".gif' /><br />";
    if (file_exists("images/".$competition['id']."/".$id.".png")) 
      $images .= "<img src='images/".$competition['id']."/".$id.".png' /><br />";
      
    $temp->addVariable("images", $images);
    
    $image_weather = "";
    $wstime = mktime(0, 0, 0, date("m", $flight['date']), date("d", $flight['date']), date("Y", $flight['date']));
    $wetime = $wstime + (60*60*24);
    if (db_query_count("SELECT * FROM %pre%weather WHERE time >= $wstime AND time < $wetime", $db, $competition['id']) > 2) {
      $image_weather = "<img src=\"weather.php?c=".$competition['id']."&mode=flight&date=".$flight['date']."\">";
    }
    $temp->addVariable("image_weather", $image_weather);
  } else {
  	$error = "Der Flug konnte nicht in der Datenbank gefunden werden.";
  }
} else {
	$error = "Es wurde kein Flug ausgewaehlt.";
}

// Fehler anzeigen falls aufgetreten
if ($error != "") {
  $temp = new Template("templates/error.tpl");
  $temp->addVariable("msg", $error);
}
?>
