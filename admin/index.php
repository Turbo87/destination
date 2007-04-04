<?php
include_once("../sql.php");
include_once("index.common.php");

$user = "";
$pass = "";
if (array_key_exists("desti_admin_user", $_COOKIE) && array_key_exists("desti_admin_pass", $_COOKIE)) {
  $user = $_COOKIE["desti_admin_user"];
  $pass = $_COOKIE["desti_admin_pass"];
}
if (array_key_exists("desti_admin_user", $_POST) && array_key_exists("desti_admin_pass", $_POST)) {
  $user = strtolower($_POST["desti_admin_user"]);
  $pass = $_POST["desti_admin_pass"];
}

if ($user == "" || $pass == "") {
  include ("index.login.php");
} else {
  $action = "";
  if (array_key_exists("action", $_GET)) {
    $action = $_GET["action"];
  }
  
  if (array_key_exists("action", $_POST)) {
    $action = $_POST["action"];
  }
  
  if ($action == "login") {
    if (!($db = db_open($user))) {
      $user = "";
      $pass = "";
      show_error($user.' - Login nicht erfolgreich!<br>(Falsche Benutzername?)');
    } else {
	    // Config auslesen
	    if (!($res_config = db_query("SELECT * FROM %pre%config WHERE name = 'password'", $db, $user)))
	      show_error($user.' - Config konnte nicht geoeffnet werden!');
	
	    if (!($row = db_fetch_array($res_config))) 
	      show_error($user.' - Fehler beim Einloggen<br>(Passwort fehlerhaft?)');
	    
	    if ($row['value'] != $pass)
	      show_error($user.' - Fehler beim Einloggen<br>(Passwort fehlerhaft?)');
    }

    setcookie("desti_admin_user", $user);
    setcookie("desti_admin_pass", $pass);
    header("LOCATION: index.php");
    die;
	} elseif ($action == "logout") {
	  setcookie("desti_admin_user", "");
	  setcookie("desti_admin_pass", "");
	  header("LOCATION: index.php");
	  die;
	}
	
	$competition['id'] = $user;
	if (!($db = db_open($competition['id'])))
	  show_error($user.' - Verbindung zur Datenbank fehlerhaft!');
	
  // Config auslesen
  if (!($res_config = db_query("SELECT * FROM %pre%config", $db, $competition['id'])))
  show_error($competition['id'].' - Config konnte nicht geoeffnet werden!');

  while($row = db_fetch_array($res_config)) {
    $competition['config'][$row['name']] = $row['value'];
  }

  if (trim($competition['config']['name']) == "")
  show_error($competition['id'].' - Config nicht vollstaendig!');

  $competition['name'] = $competition['config']['name'];
  
  if ($pass != $competition['config']['password']) {
    setcookie("desti_admin_user", "");
    setcookie("desti_admin_pass", "");
    show_error($user.' - Sie wurden ausgeloggt! Passwort falsch!');   
  }
	   
  if ($action == "") {
    include("index.frameset.php");
  } elseif ($action == "top") {
    include("index.top.php");
  } elseif ($action == "home") {
    include("index.home.php");
  } elseif ($action == "config") {
    include("index.config.php");
  } elseif ($action == "flights") {
    include("index.flights.php");
  } elseif ($action == "pilots") {
    include("index.pilots.php");
  } elseif ($action == "pilot") {
    include("index.pilot.php");
  } elseif ($action == "news") {
    include("index.news.php");
  } else {
    include("index.home.php");
  }
}
?>