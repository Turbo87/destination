<?php
/*
* index.php
* Created on 21.03.2007 by Tobias Bieniek
*/

// Fehlerbehandlung uebernimmt das Skript
//error_reporting(0);
if (version_compare(phpversion(), "5.0.0", ">"))
  date_default_timezone_set("UTC");

// Einbinden der Template Funktionen
include_once ("templates.php");

// Einbinden weiterer haeufig benutzter Funktionen
include_once ("common.php");
set_error_handler("handleError");
include_once ("sql.php");

// Welcher Wettbewerb soll angezeigt werden?
if (!array_key_exists('c', $_GET)) {
  show_error("Kein Wettbewerb ausgewaehlt!");
}
$competition['id'] = $_GET['c'];

if (!($db = db_open($competition['id'])))
  show_error('Die Datenbank konnte nicht geoeffnet werden!');

// Config auslesen
if (!($res_config = db_query("SELECT * FROM %pre%config", $db, $competition['id'])))
  show_error($competition['id'] . ' - Config konnte nicht geoeffnet werden!');

while ($row = db_fetch_array($res_config)) {
  $competition['config'][$row['name']] = $row['value'];
}
if (array_key_exists("lang", $competition['config'])) {
  $competition['lang'] = $competition['config']['lang'];  
} else {
  $competition['lang'] = "de";
}

if (trim($competition['config']['name']) == "")
  show_error($competition['id'] . ' - Config nicht vollstaendig!');

$competition['name'] = $competition['config']['name'];

// Datenbank updaten
include ("settings.php");

if ($competition['config']['update_in_progress'] != "false" && time() - $competition['config']['last_update'] >  60 * 60 * 4) {
  db_query("UPDATE %pre%config SET value = \"-1\" WHERE name=\"last_update\"", $db, $competition['id']);
  //db_query("UPDATE %pre%config SET value = \"false\" WHERE name=\"update_in_progress\"", $db, $competition['id']);
}
if ($competition['config']['update_in_progress'] == "false" && time() - $competition['config']['last_update'] >  60 * 30) {
  set_error_handler("ignoreError");
  $f = fsockopen($update_server, 80, $errno, $errstr, 5);
  fputs($f, "GET $update_url/update.php?c=" . $competition['id'] . " HTTP/1.1\r\n");
  fputs($f, "Host: $update_server\r\n");
  fputs($f, "\r\n");
  fclose($f);
  set_error_handler("handleError");
}

// Unterseiten einbinden
$temp = null;
$skip = false;
if (array_key_exists("ranking", $_GET)) {
  include ("index.ranking.php");
}
elseif (array_key_exists("list", $_GET)) {
  include ("index.list.php");
}
elseif (array_key_exists("pilots", $_GET)) {
  include ("index.pilots.php");
}
elseif (array_key_exists("flight", $_GET)) {
  include ("index.flight.php");
}
elseif (array_key_exists("home", $_GET)) {
  include ("index.home.php");
}
/*elseif (array_key_exists("pre_stats", $_GET)) {
  include ("pre.stats.php");
}*/
elseif (array_key_exists("stats", $_GET)) {
  include ("index.stats.php");
}
elseif (array_key_exists("top", $_GET)) {
  include ("index.top.php");
}
elseif (array_key_exists("pre_pdf", $_GET)) {
  include ("pre.pdf.php");
  $skip = true;
}
else {
  include ("index.frameset.php");
}

$percent = $competition['config']['update_progress'];
$percent = round($percent * 100);

$updating_str = "Download from OLC: $percent%";
if ($percent >= 100)
  $updating_str = "ReScoring Flights...";
if ($percent >= 200)
  $updating_str = "Rendering Statistics... ".($percent-200)."%";
  
$updating = "<style>
div.updating {
  POSITION: fixed;
  TOP: 0px;
  LEFT: 0px;
  WIDTH: 185px;
  HEIGHT: 22px; 
  BACKGROUND: url('templates/updating.png') no-repeat;
  PADDING: 0px;
  PADDING-LEFT: 47px; 
  PADDING-TOP: 4px;
  FONT-SIZE: 11px; 
  FONT-FAMILY: Arial;
  COLOR: white;
}
</style>
<!--[if lte IE 6]>
<style>
html, body {
	height: 100%;
	overflow: auto;
}
div.updating {
	POSITION: absolute;
}
</style>
<![endif]-->  
<div class=\"updating\">$updating_str</div>\r\n";

if (!$skip) {
  if ($temp == null) {
    show_error($competition['id'] .
      ' - Die gesuchte Seite konnte nicht gefunden werden!');
  }

  $temp->fillStandard($competition);
  if ($competition['config']['update_in_progress'] == "false")
    $updating = "";
  $temp->addVariable("updating", $updating);
  $echo = $temp->getOutput();
  echo $echo;
}

db_close($db);
?>