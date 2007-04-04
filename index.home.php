<?php
/*
 * index.home.php
 * Created on 21.03.2007 by Tobias Bieniek
 */
 
// Startseiten-Template laden und mit Variablen füllen
$temp = new Template("templates/".$competition['lang']."/home.tpl");

// Heimatflugplatz einfügen
$base = (explode("|", $competition['config']['homebase']));
$base = $base[0];
$temp->addVariable("homebase", $base);

// Heimatbonus einfügen
$temp->addVariable("awayfactor", $competition['config']['homefactor']);
$hf = "1.0";
while (strlen($hf) < strlen($competition['config']['homefactor'])) {
	$hf = $hf . "0";
}
$temp->addVariable("homefactor", $hf);

// Meilenstein-Faktoren einfügen
$factors = explode("|", $competition['config']['factors']);
for ($i = 0; $i < count($factors); $i++) {
  $temp->addVariable("factor_".($i+1), $factors[$i]);
}

// OLC Jahr berechnen
$year = date("Y");
if (time() >= mktime(0,0,0,11,1,$year))
  $year++;

// kleine Rankingtabellen der letzten <3> Jahre einfuegen
include_once("index.smallranking.php");

$sr_perpage = 3;
if (isset($competition['config']['sr_per_page']) && $competition['config']['sr_per_page'] > 0)
  $sr_perpage = $competition['config']['sr_per_page'];

$small_rankings = "";
$i = 0;
for ($j = 0; $j < 100 && $i < $sr_perpage; $j++) {
  $xtemp = getSmallRanking($competition, $db, $year - $j);
  if ($xtemp != "") {
    $small_rankings .= $xtemp;
    $i++;
  }  
}

$temp->addVariable("small_rankings", $small_rankings);

// News-System hinzufügen
$page = 1;
if (array_key_exists("page", $_GET)) $page = $_GET['page'];

$i = 0;
$s_news = "";
$next = 0;
$prev = 0;

$perpage = 5;
if (isset($competition['config']['news_per_page']) && $competition['config']['news_per_page'] > 0)
  $perpage = $competition['config']['news_per_page'];

$res_news = db_query("SELECT * FROM %pre%news ORDER BY time DESC, id DESC", $db, $competition['id']);
while($news = db_fetch_array($res_news)) {
  if ($i >= ($page - 1) * $perpage && $i < $page * $perpage) {
    $news_temp = new Template("templates/".$competition['lang']."/home.news.row.tpl");
    $news_temp->addVariable("id", $news['id']);
    $news_temp->addVariable("author", $news['author']);
    $news_temp->addVariable("header", $news['header']);
    $news_temp->addVariable("text", str_replace("\r\n", "<br />\r\n", $news['text']));
    $news_temp->addVariable("ldate", date("d.m.Y", $news['time']));
    $news_temp->addVariable("sdate", date("d.m.", $news['time']));
    $news_temp->addVariable("ltime", date("H:i:s", $news['time']));
    $news_temp->addVariable("stime", date("H:i", $news['time']));
    $s_news .= $news_temp->getOutput();
  }
  if ($i >= $page * $perpage) {
    $next = 1;
  }
  if ($i < ($page - 1) * $perpage) {
    $prev = 1;
  }
  $i++;
}
if ($i == 0) {
  $news_temp = new Template("templates/".$competition['lang']."/home.news.row.empty.tpl");
  $news_temp->fillStandard($competition);
  $s_news .= $news_temp->getOutput();
}

// Links einfügen (Vor/Zurück) 
if ($next == 1) {
  $next = new Template("templates/".$competition['lang']."/home.news.next.tpl");
  $next->fillStandard($competition);
  $next->addVariable("page", ($page+1));
  $next = $next->getOutput();
} else
  $next = "&nbsp;";


if ($prev == 1) {
  $prev = new Template("templates/".$competition['lang']."/home.news.prev.tpl");
  $prev->fillStandard($competition);
  $prev->addVariable("page", ($page-1));
  $prev = $prev->getOutput();
} else
  $prev = "&nbsp;";

$temp->addVariable("news", $s_news);
$temp->addVariable("news_page", $page);
$temp->addVariable("news_next", $next);
$temp->addVariable("news_prev", $prev);
?>
