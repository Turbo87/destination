<?
// Fehlerbehandlung Übernimmt das Skript
//error_reporting(0);
if (version_compare(phpversion(), "5.0.0", ">"))
  date_default_timezone_set("UTC");

// Skript muss unendlich laufen können
set_time_limit(0);

//////////////////////////////////////////////////////////////////////////////
// Wichtige Funktionen einbinden (Logging, HTTPRequests, Fehlerbehandlung)
include_once("update.common.php");

//set_error_handler("handleError");
include_once("sql.php");

// Rountinen zum Daten lesen einbinden
include_once("update.olc1.php");
include_once("update.olc2.php");

// Rountinen zum Daten verarbeiten einbinden
include_once("update.score.php");

// Rountinen zum Vorrendern einiger Seiten einbinden
//include_once("update.prerender.php");
include_once("pre.php");

//////////////////////////////////////////////////////////////////////////////

// Welcher Wettbewerb soll geupdatet werden?
if (!array_key_exists("c", $_GET))
  die2('Kein Wettbewerb ausgewaehlt!');
$competition['id'] = $_GET['c'];

// Debug-Modus
$DEBUG = false;
if (array_key_exists("debug", $_GET))
  $DEBUG = true;

// Mit Datenbank verbinden
if (!($db = db_open($competition['id'])))
  show_error('Die Datenbank konnte nicht geoeffnet werden!');

// Config auslesen
if (!($res_config = db_query("SELECT * FROM %pre%config", $db, $competition['id'])))
  die2($competition['id'].' - Config konnte nicht geoeffnet werden!');

while($row = db_fetch_array($res_config)) {
  $competition['config'][$row['name']] = $row['value'];
}
if (array_key_exists("lang", $competition['config'])) {
  $competition['lang'] = $competition['config']['lang'];  
} else {
  $competition['lang'] = "de";
}

if (trim($competition['config']['name']) == "" || trim($competition['config']['factors']) == "" || trim($competition['config']['clubs']) == "")
  die2($competition['id'].' - Config nicht vollstaendig!');

if ($competition['config']['update_in_progress'] != "false" && !array_key_exists("just_parse", $_GET)) {
  if ($DEBUG) log2file($competition['config']['name'].' - Update NICHT gestartet (DEBUG Mode)');
  echo "Update running!";
  die;
}

// update_in_progress flag setzen damit nicht 2 Updates parallel laufen
db_query("UPDATE %pre%config SET value = 'true' WHERE name = 'update_in_progress'", $db, $competition['id']);

// Startzeit speichern und Traffic-Counter zurücksetzen
$times['start'] = microtime(true);
$traffic['bytes'] = 0;
$traffic['files'] = 0;

$competition['name'] = $competition['config']['name'];
$competition['config']['factors'] = explode("|", $competition['config']['factors']);
$competition['config']['clubs'] = explode("|", $competition['config']['clubs']);

// Datenbank BackUp
if (time() - $competition['config']['last_backup'] > 60*60*24*7 && $dbmode != "mysql") {
  copy("database/".$competition['id'].".sdb", "database/backups/".$competition['id']."-".date("ymd").".sdb");
  db_query("UPDATE %pre%config SET value = '".time()."' WHERE name = 'last_backup'", $db, $competition['id']);
}

// OLC Saison berechnen
$olc_year = date("Y");
if (time() >= mktime(0,0,0,11,1,$olc_year))
  $olc_year++;

log2file($competition['name'].' - Update gestartet'.($DEBUG ? " (DEBUG Mode)" : ""));

// Vereine nacheinander abarbeiten
$i = 0;
if (!array_key_exists("just_parse", $_GET)) {
  if ($DEBUG) log2file("> Loading flights...");
  
  $divider[1] = count($competition['config']['clubs']);
	foreach ($competition['config']['clubs'] as $club) {
    // Vereinsnamen auslesen
    $clubs = explode("|", $competition['config']['clubs_names']);
    $clubname = $clubs[$i];
    $clubs = null;
    $divider[0] = $i;
    
    if ($DEBUG) log2file("> Verein: ".$clubname);
  
    // Daten ALLER Jahre (inklusive OLC 1.0) auslesen
    if (array_key_exists("all", $_GET)) {
      if ($DEBUG) log2file("> Downloading OLC 1.0 - 2004...");
      ripOLC1($competition, $db, $club, $clubname, 2004);
      
      if ($DEBUG) log2file("> Downloading OLC 1.0 - 2005...");
      ripOLC1($competition, $db, $club, $clubname, 2005);
      
      if ($DEBUG) log2file("> Downloading OLC 1.0 - 2006...");
      ripOLC1($competition, $db, $club, $clubname, 2006);
    }
  
    for ($y = 2007; $y <= $olc_year; $y++) {
      // Daten des OLC 2.0 auslesen und verarbeiten
      if (array_key_exists("all", $_GET) || $y == $olc_year || ($y == ($olc_year - 1) && (date("m") == "11" || date("m") == "12"))) {
        if ($DEBUG) log2file("> Downloading OLC 2.0 - $y...");
        ripOLC2($competition, $db, $club, $clubname, $y);
      }
    }
    
    $i++;
  }
}
$times['download'] = microtime(true);
updateProgress(1, array(0,1), $db, $competition);

// Flüge auswerten
if ($DEBUG) log2file("> Flüge auswerten...");
scoreFlights($competition, $db);
$times['score'] = microtime(true);

// Ranking berechnen
if ($DEBUG) log2file("> Rankings erstellen...");
calcRanking($competition, $db);
$times['ranking'] = microtime(true);
updateProgress(2, array(0,1), $db, $competition);

// Seiten im vorraus rechnen
if ($DEBUG) log2file("> PreRender Sites...");
preRender($competition, $db);
$times['prerender'] = microtime(true);

// Ausführzeiten berechnen
$times['download_total'] = $times['download'] -  $times['start'];
$times['score_total'] = $times['score'] -  $times['download'];
$times['ranking_total'] = $times['ranking'] -  $times['score'];
$times['prerender_total'] = $times['prerender'] -  $times['ranking'];
$times['total'] = $times['prerender'] -  $times['start'];

// UPDATE BEENDET!
log2file($competition['name'].' - Update beendet ('.round($times['total'],2).'s)');
log2file('>> Download: '.round($times['download_total'],2).'s / '.$traffic['files'].' Files / '.round($traffic['bytes']/1024,1).' KB');
log2file('>> Scoring & Ranking: '.round($times['score_total'],2).'s / '.round($times['ranking_total'],2).'s');
log2file('>> Rendering Stats: '.round($times['prerender_total'],2).'s');

// update_in_progress flag zurÃ¼cksetzen
db_query("UPDATE %pre%config SET value = 'false' WHERE name = 'update_in_progress'", $db, $competition['id']);
db_query("UPDATE %pre%config SET value = '".time()."' WHERE name = 'last_update'", $db, $competition['id']);

db_close($db);
?>