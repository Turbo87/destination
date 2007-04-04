<?php
/*
 * index.list.php
 * Created on 21.03.2007 by Tobias Bieniek
 */

// Jahr angegeben? (ansonsten aktuelle Saison) 
$year = date("Y");
if (time() >= mktime(0,0,0,11,1,$year))
  $year++;
  
if (array_key_exists("year", $_GET))
  if (is_numeric($year) && $year < 2020 && $year > 2004)
    $year = $_GET["year"];
  
// Template Öffnen
$temp = new Template("templates/".$competition['lang']."/list.tpl");
$temp->addVariable("year", $year);

// Links einfügen (Vor/Zurück)
$next = "&nbsp;";
if (db_query_count("SELECT * FROM %pre%flights WHERE date < ".mktime(0,0,0,11,1,($year+1))." AND date >= ".mktime(0,0,0,11,1,($year)), $db, $competition['id']) > 0) {
  $next = new Template("templates/".$competition['lang']."/list.next.tpl");
  $next->fillStandard($competition);
  $next->addVariable("year", ($year+1));
  $next = $next->getOutput();
}
$temp->addVariable("link+", $next);
$next = null;

$prev = "&nbsp;";
if (db_query_count("SELECT * FROM %pre%flights WHERE date < ".mktime(0,0,0,11,1,($year-1))." AND date >= ".mktime(0,0,0,11,1,($year-2)), $db, $competition['id']) > 0) {
  $prev = new Template("templates/".$competition['lang']."/list.prev.tpl");
  $prev->fillStandard($competition);
  $prev->addVariable("year", ($year-1));
  $prev = $prev->getOutput();
}
$temp->addVariable("link-", $prev);
$prev = null;

// Sortierung?
$sort = "date DESC";
if (array_key_exists("sort", $_GET))
  $sort = $_GET['sort'];

// Flüge auslesen
$i = 0;
$res_flights = db_query("SELECT * FROM %pre%flights WHERE date < ".mktime(0,0,0,11,1,($year))." AND date >= ".mktime(0,0,0,11,1,($year-1))." ORDER BY ".$sort.", date DESC, points DESC", $db, $competition['id']);
$rows = "";
while ($flight = db_fetch_array($res_flights)) {
  $row = new Template("templates/".$competition['lang']."/list.row.tpl");
  $row->fillStandard($competition);
  
  $row->addVariable("odd", ($i % 2 == 0 ? "o" : "e"));
  
  $row->addVariable("id", $flight['id']);
  $row->addVariable("date", date("d.m.", $flight['date']));
  $row->addVariable("pilot",  trim($flight['pilot']));
  $row->addVariable("copilot", "");
  if (trim($flight['copilot']) != "") {
    $copilot = new Template("templates/".$competition['lang']."/list.row.copilot.tpl");
    $copilot->fillStandard($competition);
    $copilot->addVariable("copilot", trim($flight['copilot']));
    $row->addVariable("copilot", $copilot->getOutput());
    $copilot = null;
  }
  $row->addVariable("factor", $flight['factor']);
  $row->addVariable("plane", $flight['plane']);
  $row->addVariable("points", round($flight['points']*100)/100);
  $row->addVariable("olc", $flight['olc_points']);
  $row->addVariable("km", $flight['km']);
  $row->addVariable("airfield", $flight['airfield']);
  
  if (substr($flight['id'], 0, 4) >= 2007) {
    // http://www.onlinecontest.org/olc-2.0/gliding/flightinfo.html?flightId=
    $flight_link = "http://www.onlinecontest.org/olc-2.0/gliding/flightinfo.html?flightId=".substr($flight['id'], 5);
  } else {
    // http://www2.onlinecontest.org/olcphp/2005/ausw_fluginfo.php?ref3=225011&ueb=N&olc=olc-d&spr=de
    $flight_link = "http://www2.onlinecontest.org/olcphp/".substr($flight['id'], 0, 4)."/ausw_fluginfo.php?ref3=".substr($flight['id'], 5)."&ueb=N&olc=olc-d&spr=de";
  }
  $row->addVariable("olc_link", $flight_link);

  $rows .= $row->getOutput();
  $i++;
}
if (trim($rows) == "") {
  $row = new Template("templates/".$competition['lang']."/list.row.empty.tpl");
  $row->fillStandard($competition);
  $rows = $row->getOutput();
}
$temp->addVariable("rows", $rows);
?>
