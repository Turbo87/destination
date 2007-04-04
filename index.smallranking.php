<?php
/*
 * index.smallranking.php
 * Created on 04.04.2007 by Tobias Bieniek
 */

if (!function_exists("getSmallRanking")) {
  function getSmallRanking($competition, $db, $year) {
    if (!is_numeric($year)) return "";
    
    // Template öffnen
    $temp = new Template("templates/".$competition['lang']."/small_ranking.tpl");
    $temp->addVariable("year", $year);
    
    // Ranking auslesen
    $i = 0;
    $res_ranking = db_query("SELECT * FROM %pre%rankings WHERE year = ".$year." ORDER BY points DESC, f1_points DESC, f2_points DESC, f3_points DESC LIMIT 5", $db, $competition['id']);
    $rows = "";
    while ($rank = db_fetch_array($res_ranking)) {
      $row = new Template("templates/".$competition['lang']."/small_ranking.row.tpl");
      $row->fillStandard($competition);
      
      $row->addVariable("odd", ($i % 2 == 0 ? "o" : "e"));
      
      $row->addVariable("pos", ($i + 1));
      $row->addVariable("id",  $rank['id']);
      $row->addVariable("points", round($rank['points']*100)/100);
      $row->addVariable("km", $rank['km']);
    
      $rows .= $row->getOutput();
      $i++;
    }
    if (trim($rows) == "") {
      return "";
    }
    $temp->addVariable("ranks", $rows);
    return $temp->getOutput();
  }
}
?>
