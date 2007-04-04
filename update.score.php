<?
include_once("templates.php");
include_once("update.news.php");

if (!function_exists("scoreFlights")) {
  function scoreFlights($competition, $db) {
    // News vorbereiten
    $s_flights = "";

    // Flüge einzeln von hinten durchgehen (frühster zuerst) und Punkte berechnen
    $res_flights = db_query("SELECT * FROM %pre%flights ORDER BY date ASC", $db, $competition['id']);
    while ($flight = db_fetch_array($res_flights)) {
      // Pilotennamen korrigieren falls durch Alias nötig
      if (strpos($flight['pilot'], " DE (") != false) $flight['pilot'] = substr($flight['pilot'], 0, strpos($flight['pilot']," DE ("));
      /*
      $res_pilots = db_query("SELECT * FROM %pre%pilots WHERE id = '".$flight['pilot']."'", $db, $competition['id']);
      if (!($pilot = db_fetch_array($res_pilots))) {
        $res_alias = db_query("SELECT * FROM %pre%pilots_alias WHERE alias = '".$flight['pilot']."'", $db, $competition['id']);
        if ($alias = db_fetch_array($res_alias)) {
          $flight['pilot'] = $alias['id'];
        } else {
          db_query("INSERT INTO %pre%pilots VALUES('".$flight['pilot']."', '-|-|-|-|-|-')", $db, $competition['id']);
        }
      }
      */
      $flight['pilot'] = getAlias($flight['pilot'], $db, $competition);

      // CoPilotennamen korrigieren falls durch Alias nötig
      /*
      $res_alias = db_query("SELECT * FROM %pre%pilots_alias WHERE alias = '".$flight['copilot']."'", $db, $competition['id']);
      if ($alias = db_fetch_array($res_alias)) {
        $flight['copilot'] = $alias['id'];
      }
      */
      if ($flight['copilot'] != "") $flight['copilot'] = getAlias($flight['copilot'], $db, $competition);

      // Flugzeugfaktor ausrechnen
      $planefactor = Pow($flight['plane_index'], 2) / 10000;
      if ($competition['id'] == "meiersberg" && $flight['date'] < mktime(0,0,0,11,1,2006) && $flight['date'] > mktime(0,0,0,10,31,2003) && $flight['olc_points'] > 0) {
        $planefactor =  Pow(($flight['km'] * 100) / $flight['olc_points'], 2) / 10000;
      }
      
      // Callsign korrigieren
      $flight['plane_callsign'] = fixPlaneCallsign($flight['plane_callsign']);

      // Flugplatzfaktor ermitteln
      $airfieldfactor = $competition['config']['homefactor'];
      $bases = explode("|", $competition['config']['homebase']);
      foreach ($bases as $base) {
      	if (strpos(strtolower($flight['airfield']), strtolower($base)) > 0 || substr(strtolower($flight['airfield']), 0, strlen($base)) == strtolower($base)) {
      	  $airfieldfactor = 1.0;
        }
      }

      // Pilotenfaktor ermitteln und falls nötig Meilenstein Tabelle updaten
      $flight['factor'] = getPilotFactor($flight['date'], $flight['pilot'], $competition, $db);
      // Hat der CoPilot kleineren Faktor?
      $cofactor = getPilotFactor($flight['date'], $flight['copilot'], $competition, $db);

      if ($competition['config']['alternative_dosi'] == "true") {
        if ($flight['factor'] <= $cofactor || $flight['km'] > 500)
          updatePilotFactor($flight['date'], $flight['pilot'], $flight['km'], $competition, $db);
      } else 
        updatePilotFactor($flight['date'], $flight['pilot'], $flight['km'], $competition, $db);

      if ($flight['factor'] > $cofactor && $cofactor != false) $flight['factor'] = $cofactor;

      // Faktoren zusammenrechnen
      $factor = ($flight['factor'] * $airfieldfactor) / $planefactor;
      $flight['points'] = $flight['km'] * $factor;

      // Geschwindigkeit korrigieren falls falsch im OLC ausgelesen
      if ($flight['speed'] > 350) $flight['speed'] = $flight['speed'] / 10;

      // Datenbank updaten
      $res_flight = db_query("SELECT * FROM %pre%flights WHERE id = '".$flight['id']."'", $db, $competition['id']);
      if (!($temp = db_fetch_array($res_flight))) {
        db_query("INSERT INTO %pre%flights VALUES('".$flight['id']."', ".$flight['date'].", '".$flight['pilot']."', '".
        $flight['copilot']."', '".$flight['club']."', '".$flight['plane']."', '".$flight['plane_callsign']."', '".
        $flight['plane_index'].", '".$flight['airfield']."', ".$flight['km'].", ".
        $flight['olc_points'].", ".$flight['speed'].", ".$flight['points'].", ".$flight['factor'].", 'ready')", $db, $competition['id']);
      } else {
        db_query('UPDATE %pre%flights SET date = '.$flight['date'].', pilot = "'.$flight['pilot'].'",
          copilot = "'.$flight['copilot'].'", club = "'.$flight['club'].'", plane = "'.$flight['plane'].'", plane_callsign = "'.$flight['plane_callsign'].'",
          plane_index = "'.$flight['plane_index'].'", airfield = "'.$flight['airfield'].'",
          km = "'.$flight['km'].'", olc_points = "'.$flight['olc_points'].'", speed = "'.$flight['speed'].'",
          points = "'.$flight['points'].'", factor = "'.$flight['factor'].'" WHERE id = "'.$flight['id'].'"', $db, $competition['id']);
      }
      
      // News aktualisieren falls neuer Flug
      if ($flight['status'] == "new") {
        $temp_flight = new Template("templates/".$competition['lang']."/news.msg.flights.row.tpl");
        $temp_flight->addVariable("id", $flight['id']);
        $temp_flight->addVariable("sdate", date("d.m.", $flight['date']));
        $temp_flight->addVariable("ldate", date("d.m.Y", $flight['date']));
        $temp_flight->addVariable("pilot", $flight['pilot']);
        $temp_flight->addVariable("copilot", $flight['copilot']);
        $temp_flight->addVariable("pilots", ($flight['copilot'] != "" ? $flight['pilot'] . " / " .$flight['copilot'] : $flight['pilot']));
        $temp_flight->addVariable("club", $flight['club']);
        $temp_flight->addVariable("plane", $flight['plane']);
        $temp_flight->addVariable("plane_callsign", $flight['plane_callsign']);
        $temp_flight->addVariable("plane_index", $flight['plane_index']);
        $temp_flight->addVariable("airfield", $flight['airfield']);
        $temp_flight->addVariable("km", $flight['km']);
        $temp_flight->addVariable("olc_points", $flight['speed']);
        $temp_flight->addVariable("speed", $flight['speed']);
        $temp_flight->addVariable("points", round($flight['points']*100)/100);
        $temp_flight->addVariable("factor", $flight['factor']);
        $s_flights .= $temp_flight->getOutput();

        db_query('UPDATE %pre%flights SET status = "ready" WHERE id = "'.$flight['id'].'"', $db, $competition['id']);
      }
    }
    if ($s_flights != "") {
      $temp_news = new Template("templates/".$competition['lang']."/news.msg.flights.tpl");
      $temp_news->fillStandard($competition);
      $temp_news->addVariable("flights", $s_flights);
      $temp_news_h = str_replace(strstr($temp_news->getOutput(), "\n"), "", $temp_news->getOutput());
      $temp_news_t = trim(strstr($temp_news->getOutput(), "\n"));
      addNews(trim($temp_news_h), trim($temp_news_t), "System", $competition, $db);
    }
  }
}
    
if (!function_exists("calcRanking")) {
  function calcRanking($competition, $db) {
    // Aktuelles OLC Jahr berechnen
    $year = date("Y");
    if (time() >= mktime(0,0,0,11,1,$year))
      $year++;

    // Alte #1 auslesen
    $res_pilot = db_query("SELECT * FROM %pre%rankings WHERE year = $year ORDER BY points DESC, f1_points DESC, f2_points DESC, f3_points DESC LIMIT 1", $db, $competition['id']);
    if ($leader = db_fetch_array($res_pilot)) {
      $leader = $leader['id'];
    }

    // Löschen des alten Rankings
    db_query("DELETE FROM %pre%rankings", $db, $competition['id']);

    // Pilotenliste auslesen
    $res_pilots = db_query("SELECT pilot FROM %pre%flights GROUP BY pilot ORDER BY pilot", $db, $competition['id']);
    
    // Piloten durchgehen und Ranking berechnen
    while ($pilot = db_fetch_array($res_pilots)) {
      // Jahre durchgehen
      for ($i = 2004; $i <= $year; $i++) {
        $rank = null;
        $rank['pilot'] = $pilot['pilot'];
        $rank['f1_points'] = 0;
        $rank['f2_points'] = 0;
        $rank['f3_points'] = 0;
        $rank['f1_id'] = "";
        $rank['f2_id'] = "";
        $rank['f3_id'] = "";
        
        // Flüge des Jahres und Piloten auslesen nach Punkten sortiert
        $res_flights = db_query("SELECT * FROM %pre%flights WHERE pilot = '".$pilot['pilot']."' AND date < ".mktime(0,0,0,11,1,$i)." AND date >= ".mktime(0,0,0,11,1,($i-1))." AND status = 'ready' ORDER BY points DESC LIMIT 3", $db, $competition['id']);
        
        $rank['km'] = 0;
        $rank['points'] = 0;
        $j = 0;
        
        // Beste 3 Fluege speichern
        while ($flight = db_fetch_array($res_flights)) {
          $rank["f".($j + 1)."_id"] = $flight['id'];
          $rank["f".($j + 1)."_points"] = $flight['points'];
          $rank['km'] += $flight['km'];
          $rank['points'] += $flight['points'];
          $j++;
        }
                
        // Datenbank updaten wenn Pilot min. einen Flug hat
        if ($rank['points'] > 0) {
          $res_ranks = db_query("SELECT * FROM %pre%rankings WHERE id = '".$rank['pilot']."' AND year = ".$i, $db, $competition['id']);
          if (!($temp = db_fetch_array($res_ranks))) {
            db_query("INSERT INTO %pre%rankings VALUES('".$rank['pilot']."', ".$i.", ".$rank['points'].", ".$rank['km'].
              ", ".$rank['f1_points'].", '".$rank['f1_id']."', ".$rank['f2_points'].", '".$rank['f2_id']."', ".$rank['f3_points'].
              ", '".$rank['f3_id']."')", $db, $competition['id']);
          } else {
            db_query('UPDATE %pre%rankings SET points = '.$rank['points'].', km = '.$rank['km'].', f1_points = '.$rank['f1_points'].
              ', f1_id = "'.$rank['f1_id'].'", f2_points = '.$rank['f2_points'].', f2_id = "'.$rank['f2_id'].'", f3_points = '.$rank['f3_points'].
              ', f3_id = "'.$rank['f3_id'].'" WHERE id = "'.$rank['pilot'].'" AND year = '.$i, $db, $competition['id']);
          }
        }
      }
    }
    
    // Neue #1 auslesen und vergleichen
    $res_pilot = db_query("SELECT * FROM %pre%rankings WHERE year = $year ORDER BY points DESC, f1_points DESC, f2_points DESC, f3_points DESC LIMIT 1", $db, $competition['id']);
    if ($nleader = db_fetch_array($res_pilot)) {
      if (strtolower($nleader['id']) != strtolower($leader)) {
        $temp_news = new Template("templates/".$competition['lang']."/news.msg.leader.tpl");
        $temp_news->fillStandard($competition);
        $temp_news->addVariable("new_leader", $nleader['id']);
        $temp_news->addVariable("old_leader", $leader);
        $temp_news->addVariable("km", $nleader['km']);
        $temp_news->addVariable("points", $nleader['points']);
        $temp_news_h = str_replace(strstr($temp_news->getOutput(), "\r\n"), "", $temp_news->getOutput());
        $temp_news_t = trim(strstr($temp_news->getOutput(), "\r\n"));
        addNews($temp_news_h, $temp_news_t, "System", $competition, $db);
        log2file($competition['config']['name'].' - Leader changed ('.$leader.' -> '.$nleader['id'].')');
      }
    }
  }
}

if (!function_exists("getPilotFactor")) {
  function getPilotFactor($date, $pilot, $competition, $db) {
    // Meilensteine auslesen
    $res_pilots = db_query("SELECT * FROM %pre%pilots WHERE id = '".$pilot."'", $db, $competition['id']);
    if (!($xpilot = db_fetch_array($res_pilots))) {
      return $competition['config']['factors'][0];
    }
    
    // Faktor für größte Strecke zurückgeben    
    $milestones = explode("|", $xpilot['milestones']);
    if ($milestones[5] != "" && $milestones[5] != "-") {
      if ($date - (60*60*12) > $milestones[5]) return $competition['config']['factors'][6];
    }
    if ($milestones[4] != "" && $milestones[4] != "-") {
      if ($date - (60*60*12) > $milestones[4]) return $competition['config']['factors'][5];
    }
    if ($milestones[3] != "" && $milestones[3] != "-") {
      if ($date - (60*60*12) > $milestones[3]) return $competition['config']['factors'][4];
    }
    if ($milestones[2] != "" && $milestones[2] != "-") {
      if ($date - (60*60*12) > $milestones[2]) return $competition['config']['factors'][3];
    }
    if ($milestones[1] != "" && $milestones[1] != "-") {
      if ($date - (60*60*12) > $milestones[1]) return $competition['config']['factors'][2];
    }
    if ($milestones[0] != "" && $milestones[0] != "-") {
      if ($date - (60*60*12) > $milestones[0]) return $competition['config']['factors'][1];
    }

    return $competition['config']['factors'][0];
  }
}

if (!function_exists("updatePilotFactor")) {
  function updatePilotFactor($date, $pilot, $km, $competition, $db) {
    // Aktuelle Meilensteine des Piloten auslesen
    $res_pilots = db_query("SELECT * FROM %pre%pilots WHERE id = '".$pilot."'", $db, $competition['id']);
    if (!($xpilot = db_fetch_array($res_pilots))) {
      db_query("INSERT INTO %pre%pilots VALUES('".$pilot."','-|-|-|-|-|-')", $db, $competition['id']);
      $xpilot['milestones'] = "-|-|-|-|-|-";
    }
    
    $milestones = explode("|", $xpilot['milestones']);
    if (!($milestones[5] != "" && $milestones[5] != "-")) {
      if ($km > 1000) {
        $milestones[5] = $date;
        $new = 1000;
      }
    }
    if (!($milestones[4] != "" && $milestones[4] != "-")) {
      if ($km > 700) {
        $milestones[4] = $date;
        $new = 700;
      }
    }
    if (!($milestones[3] != "" && $milestones[3] != "-")) {
      if ($km > 500) {
        $milestones[3] = $date;
        $new = 500;
      }
    }
    if (!($milestones[2] != "" && $milestones[2] != "-")) {
      if ($km > 300) {
        $milestones[2] = $date;
        $new = 300;
      }
    }
    if (!($milestones[1] != "" && $milestones[1] != "-")) {
      if ($km > 100) {
        $milestones[1] = $date;
        $new = 100;
      }
    }
    if (!($milestones[0] != "" && $milestones[0] != "-")) {
      if ($km > 50) {
        $milestones[0] = $date;
        $new = 50;
      }
    }
    $milestones = implode("|", $milestones);
    
    // Wenn Meilensteine sich geändert haben in Datenbank updaten und News eintragen
    if ($milestones != $xpilot['milestones']) {
      db_query("UPDATE %pre%pilots SET milestones = '".$milestones."' WHERE id = '".$pilot."'", $db, $competition['id']);
      
      $temp_news = new Template("templates/".$competition['lang']."/news.msg.milestone.tpl");
      $temp_news->fillStandard($competition);
      $temp_news->addVariable("pilot", $pilot);
      $temp_news->addVariable("km", $km);
      $temp_news->addVariable("milestone", $new);
      $temp_news->addVariable("sdate", date("d.m.", $date));
      $temp_news->addVariable("ldate", date("d.m.Y", $date));
      $temp_news->addVariable("old_factor", getPilotFactor($date - 60*60*24*7, $pilot, $competition, $db));
      $temp_news->addVariable("new_factor", getPilotFactor($date + 60*60*24*7, $pilot, $competition, $db));
      $temp_news_h = str_replace(strstr($temp_news->getOutput(), "\r\n"), "", $temp_news->getOutput());
      $temp_news_t = trim(strstr($temp_news->getOutput(), "\r\n"));
      addNews($temp_news_h, $temp_news_t, "System", $competition, $db);
    }
  }
}
?>