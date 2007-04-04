<?php
/*
 * index.pilots.php
 * Created on 03.04.2007 by Tobias Bieniek
 */

// Pilotendetails anzeigen oder Liste?
$id = -1;
if (array_key_exists("id", $_GET)) $id = $_GET['id'];

// Liste
if ($id == -1) {
  // Template öffnen
  $temp = new Template("templates/".$competition['lang']."/pilots.tpl");
  
  // Piloten auslesen und mit Meilensteinen in Tabelle darstellen
  $i = 0;
  $res_pilots = db_query("SELECT * FROM %pre%pilots ORDER BY id", $db, $competition['id']);
  $rows = "";
  while ($pilot = db_fetch_array($res_pilots)) {
    $row = new Template("templates/".$competition['lang']."/pilots.row.tpl");
    $row->fillStandard($competition);
    
    $row->addVariable("odd", ($i % 2 == 0 ? "o" : "e"));
    
    $row->addVariable("name", $pilot['id']);
    
    $milestones = explode("|", $pilot['milestones']);
    $row->addVariable("milestone1", ($milestones[0] != "-" ? "X" : ""));
    $row->addVariable("milestone2", ($milestones[1] != "-" ? "X" : ""));
    $row->addVariable("milestone3", ($milestones[2] != "-" ? "X" : ""));
    $row->addVariable("milestone4", ($milestones[3] != "-" ? "X" : ""));
    $row->addVariable("milestone5", ($milestones[4] != "-" ? "X" : ""));
    $row->addVariable("milestone6", ($milestones[5] != "-" ? "X" : ""));
  
    if (isActivePilot($pilot['id'], $db, $competition)) {
      $rows .= $row->getOutput();
      $i++;
    }
  }
  if (trim($rows) == "") {
    $row = new Template("templates/".$competition['lang']."/pilots.row.empty.tpl");
    $row->fillStandard($competition);
    $rows = $row->getOutput();
  }
  $temp->addVariable("rows", $rows);
} else {
  // Piloten suchen
  $res_pilot = db_query("SELECT * FROM %pre%pilots WHERE id = '".$id."'", $db, $competition['id']);
  if (!($pilot = db_fetch_array($res_pilot))) {
    $temp = new Template("templates/error.tpl");
    $temp->addVariable("msg", "Pilot konnte nicht gefunden werden.");
  } else {
    // Template öffnen
    $temp = new Template("templates/".$competition['lang']."/pilot.tpl");
    $temp->addVariable("id", $pilot['id']);
    $temp->addVariable("idx", base64_encode($pilot['id']));
    
    // Meilensteine einfügen
    $milestones = explode("|", $pilot['milestones']);
    
    $temp->addVariable("milestone1", "");
    if ($milestones[0] != "" && $milestones[0] != "-")
      $temp->addVariable("milestone1", date("d.m.Y", $milestones[0]));
    if ($milestones[0] == "1")
      $temp->addVariable("milestone1", "??.??.????");
      
    $temp->addVariable("milestone2", "");
    if ($milestones[1] != "" && $milestones[1] != "-")
      $temp->addVariable("milestone2", date("d.m.Y", $milestones[1]));
    if ($milestones[1] == "1")
      $temp->addVariable("milestone2", "??.??.????");
      
    $temp->addVariable("milestone3", "");
    if ($milestones[2] != "" && $milestones[2] != "-")
      $temp->addVariable("milestone3", date("d.m.Y", $milestones[2]));
    if ($milestones[2] == "1")
      $temp->addVariable("milestone3", "??.??.????");
      
    $temp->addVariable("milestone4", "");
    if ($milestones[3] != "" && $milestones[3] != "-")
      $temp->addVariable("milestone4", date("d.m.Y", $milestones[3]));
    if ($milestones[3] == "1")
      $temp->addVariable("milestone4", "??.??.????");
      
    $temp->addVariable("milestone5", "");
    if ($milestones[4] != "" && $milestones[4] != "-")
      $temp->addVariable("milestone5", date("d.m.Y", $milestones[4]));
    if ($milestones[4] == "1")
      $temp->addVariable("milestone5", "??.??.????");
      
    $temp->addVariable("milestone6", "");
    if ($milestones[5] != "" && $milestones[5] != "-")
      $temp->addVariable("milestone6", date("d.m.Y", $milestones[5]));
    if ($milestones[5] == "1")
      $temp->addVariable("milestone6", "??.??.????");
  
    // Ranking: Jahre durchlaufen und nach Pilotem durchsuchen
    $sranks = "";
    $year = date("Y");
    if (time() >= mktime(0,0,0,11,1,$year))
      $year++;
    
    for ($y = $year; $y > 2003; $y--) {
      $res_ranks = db_query("SELECT * FROM %pre%rankings WHERE year = ".$y." ORDER BY points DESC, f1_points DESC, f2_points DESC, f3_points DESC", $db, $competition['id']);
      $pos = 0;
      while ($rank = db_fetch_array($res_ranks)) {
      	$pos++;
        if ($rank['id'] == $id) {
          $rtemp = "";
          $rtemp = new Template("templates/".$competition['lang']."/pilot.rank.tpl");
          $rtemp->fillStandard($competition);
          $rtemp->addVariable("year", $rank['year']);
          $rtemp->addVariable("pos", $pos);
          $sranks .= $rtemp->getOutput();
        }
      }
    }
    $temp->addVariable("ranks", $sranks);
    
    // Flüge einfügen
    $sflights = "";
    $km = 0;
    $points = 0;
    $total = 0;
    $olc = 0;
    $i = 0;
    $res_flights = db_query("SELECT * FROM %pre%flights WHERE pilot = '".$pilot['id']."' OR copilot = '".$pilot['id']."' ORDER BY date DESC", $db, $competition['id']);
    while ($flight = db_fetch_array($res_flights)) {
      $co = true;
      if ($flight['pilot'] == $pilot['id']) $co = false;
      
	    $ftemp = "";
	    
	    if ($co)
	      $ftemp = new Template("templates/".$competition['lang']."/pilot.flight_co.tpl");
	    else
        $ftemp = new Template("templates/".$competition['lang']."/pilot.flight.tpl");
      
      $ftemp->fillStandard($competition);
      $ftemp->addVariable("id", $flight['id']);
      $ftemp->addVariable("date", date("d.m.Y", $flight['date']));
      $ftemp->addVariable("factor", $flight['factor']);
      $ftemp->addVariable("plane", $flight['plane']);
      $ftemp->addVariable("plane_callsign", $flight['plane_callsign']);
      $ftemp->addVariable("plane_index", $flight['plane_index']);
      $ftemp->addVariable("points", round($flight['points']*100)/100);
      $ftemp->addVariable("olc", $flight['olc_points']);
      $ftemp->addVariable("km", $flight['km']);
      $ftemp->addVariable("airfield", $flight['airfield']);
      $ftemp->addVariable("odd", ($i % 2 == 0 ? "o" : "e"));
      
      if (substr($flight['id'], 0, 4) >= 2007) {
        // http://www.onlinecontest.org/olc-2.0/gliding/flightinfo.html?flightId=
        $flight_link = "http://www.onlinecontest.org/olc-2.0/gliding/flightinfo.html?flightId=".substr($flight['id'], 5);
      } else {
        // http://www2.onlinecontest.org/olcphp/2005/ausw_fluginfo.php?ref3=225011&ueb=N&olc=olc-d&spr=de
        $flight_link = "http://www2.onlinecontest.org/olcphp/".substr($flight['id'], 0, 4)."/ausw_fluginfo.php?ref3=".substr($flight['id'], 5)."&ueb=N&olc=olc-d&spr=de";
      }
      $ftemp->addVariable("olc_link", $flight_link);
      
      $sflights .= $ftemp->getOutput();
      
      if (!$co) {
        $total++;
        $points += $flight['points'];
        $olc += $flight['olc_points'];
        $km += $flight['km'];
      }
      $i++;
    }
    if ($total < 1) {
      $temp = null;
      $temp = new Template("templates/error.tpl");
      $temp->addVariable("msg", "Pilot konnte nicht gefunden werden.<span style=\"FONT-SIZE: 10px;\"><br><br>(Keine Flüge als Pilot-in-Command gefunden)</span>");
    } else {
      $temp->addVariable("flights", $sflights);
      
      $temp->addVariable("total", $total);
      $temp->addVariable("km", $km);
      $temp->addVariable("km_avg",  round($km*100/$total)/100);
      $temp->addVariable("points", round($points*100)/100);
      $temp->addVariable("points_avg",  round($points*100/$total)/100);
      $temp->addVariable("olc", $olc);
    }
  }
}
?>
