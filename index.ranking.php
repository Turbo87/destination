<?php
/*
 * index.ranking.php
 * Created on 04.04.2007 by Tobias Bieniek
 */

// Jahr angegeben? (ansonsten aktuelle Saison) 
$year = date("Y");
if (time() >= mktime(0,0,0,11,1,$year))
  $year++;
  
if (array_key_exists("year", $_GET))
  if (is_numeric($year) && $year < 2020 && $year > 2004)
    $year = $_GET["year"];
  
// Template Öffnen
$temp = new Template("templates/".$competition['lang']."/ranking.tpl");
$temp->addVariable("year", $year);

// Links einfügen (Vor/Zurück)
$next = "&nbsp;";
if (db_query_count("SELECT * FROM %pre%rankings WHERE year = ".($year + 1), $db, $competition['id']) > 0) {
  $next = new Template("templates/".$competition['lang']."/ranking.next.tpl");
  $next->fillStandard($competition);
  $next->addVariable("year", ($year+1));
  $next = $next->getOutput();
}
$temp->addVariable("link+", $next);
$next = null;

$prev = "&nbsp;";
if (db_query_count("SELECT * FROM %pre%rankings WHERE year = ".($year - 1), $db, $competition['id']) > 0) {
  $prev = new Template("templates/".$competition['lang']."/ranking.prev.tpl");
  $prev->fillStandard($competition);
  $prev->addVariable("year", ($year-1));
  $prev = $prev->getOutput();
}
$temp->addVariable("link-", $prev);
$prev = null;

// Ranking auslesen und anzeigen
$i = 0;
$res_ranking = db_query("SELECT * FROM %pre%rankings WHERE year = ".$year." ORDER BY points DESC, f1_points DESC, f2_points DESC, f3_points DESC", $db, $competition['id']);
$rows = "";
while ($rank = db_fetch_array($res_ranking)) {
  $row = new Template("templates/".$competition['lang']."/ranking.row.tpl");
  $row->fillStandard($competition);
  
  $row->addVariable("odd", ($i % 2 == 0 ? "o" : "e"));
  
  $row->addVariable("pos", ($i + 1));
  $row->addVariable("name",  $rank['id']);
  $row->addVariable("points", round($rank['points']*100)/100);
  $row->addVariable("km", $rank['km']);
  $row->addVariable("points1", "");
  if ($rank['f1_points'] > 0)
    $row->addVariable("points1", round($rank['f1_points']*100)/100);
  $row->addVariable("points2", "");
  if ($rank['f2_points'] > 0)
    $row->addVariable("points2", round($rank['f2_points']*100)/100);
  $row->addVariable("points3", "");
  if ($rank['f3_points'] > 0)
    $row->addVariable("points3", round($rank['f3_points']*100)/100);
  $row->addVariable("id1", $rank['f1_id']);
  $row->addVariable("id2", $rank['f2_id']);
  $row->addVariable("id3", $rank['f3_id']);

  $rows .= $row->getOutput();
  $i++;
}
if (trim($rows) == "") {
  $row = new Template("templates/".$competition['lang']."/ranking.row.empty.tpl");
  $row->fillStandard($competition);
  $rows = $row->getOutput();
}
$temp->addVariable("rows", $rows);

// OLC Jahr berechnen
$thisyear = date("Y");
if (time() >= mktime(0,0,0,11,1,$thisyear))
  $thisyear++;

// 3 kleine Rankings anzeigen (letzte Jahre abgesehen vom angezeigten)
include_once("index.smallranking.php");
$small_rankings = "";
$i = 0;
for ($j = 0; $j < 100 && $i < 3; $j++) {
	if ($thisyear - $j != $year) {
    $xtemp = getSmallRanking($competition, $db, $thisyear - $j);
    if ($xtemp != "") {
    	$small_rankings .= $xtemp;
      $i++;
    }  
  }
}
$temp->addVariable("small_rankings", $small_rankings);
?>
