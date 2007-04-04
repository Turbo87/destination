<?php
/*
 * sql.php
 * Created on 13.04.2007 by Tobias Bieniek
 */
 
include("settings.php");

if (!function_exists("db_query")) {
  // Datenbank Abfrage ausführen und falls nötig Ergebnis zurückgeben
  if ($dbmode == "mysql") {
    function db_query($query, $db, $cid) {
      return mysql_query(str_replace("%pre%", $cid."_", $query), $db);
    }
  } else {
    function db_query($query, $db, $cid) {
      return sqlite_query(str_replace("%pre%", "", $query), $db);
    }
  }
}

if (!function_exists("db_query_count")) {
  // Anzahl der Zeilen einer SELECT Anweisung zurückgeben
  if ($dbmode == "mysql") {
    function db_query_count($query, $db, $cid) {
      $res = mysql_query(str_replace("%pre%", $cid."_", $query), $db);
      if (!$res) return false;
      return mysql_num_rows($res);
    }
  } else {
    function db_query_count($query, $db, $cid) {
      $res = sqlite_query(str_replace("%pre%", "", $query), $db);
      if (!$res) return false;
      return sqlite_num_rows($res);
    }
  }
}

if (!function_exists("db_result_count")) {
  // Anzahl der Zeilen einer SELECT Anweisung zurückgeben
  if ($dbmode == "mysql") {
    function db_result_count($res) {
      if (!$res) return false;
      return mysql_num_rows($res);
    }
  } else {
    function db_result_count($res) {
      if (!$res) return false;
      return sqlite_num_rows($res);
    }
  }
}

if (!function_exists("db_fetch_array")) {
  // NÃ¤chste Zeile als Array zurÃ¼ckgeben
  if ($dbmode == "mysql") {
    function db_fetch_array($res) {
      if (!$res) return false;
      return mysql_fetch_array($res);
    }
  } else {
    function db_fetch_array($res) {
      if (!$res) return false;
      return sqlite_fetch_array($res);
    }
  }
}

if (!function_exists("db_open")) {
  // Datenbankverbindung Ã¶ffnen und auf Fehler reagieren
  if ($dbmode == "mysql") {
    function db_open($cid) {
      GLOBAL $mysql_host, $mysql_user, $mysql_pass, $mysql_db;
      
      // Zum MySQL Server verbinden
      if (!($db = mysql_connect($mysql_host, $mysql_user, $mysql_pass))) 
        show_error('Die Datenbank konnte nicht geoeffnet werden! (Server nicht erreichbar!)');
        
      // Datenbank auswÃ¤hlen
      if (!mysql_select_db($mysql_db))
        show_error('Die Datenbank konnte nicht geoeffnet werden! (Datenbank nicht vorhanden!)');
        
      // Wettbewerb vorhanden?
      $res = mysql_query("SELECT * FROM competitions WHERE id = '".$cid."'", $db);
      if (!$res || !($row = mysql_fetch_array($res)))
        show_error('Der Wettbewerb "'.$cid.'" ist nicht in der Datenbank vorhanden!');
        
      return $db;
    }
  } else {
    function db_open($cid) {
      // Datenbank vorhanden?
      if (!file_exists("database/".$cid.".sdb"))
        show_error('Der Wettbewerb "'.$cid.'" ist nicht in der Datenbank vorhanden!');
      
      // Datenbank Ã¶ffnen
      if (!($db = sqlite_open("database/".$cid.".sdb")))
        show_error('Die Datenbank "'.$cid.'.sdb" konnte nicht geoeffnet werden!');
      
      return $db;
    }
  }
}

if (!function_exists("db_close")) {
  // Datenbankverbindung schlieÃŸen
  if ($dbmode == "mysql") {
    function db_close($db) {
      return mysql_close($db);
    }
  } else {
    function db_close($db) {
      return sqlite_close($db);
    }
  }
}

if (!function_exists("db_get_dbs")) {
  // Datenbankverbindung schlieÃŸen
  if ($dbmode == "mysql") {
    function db_get_dbs() {
      GLOBAL $mysql_host, $mysql_user, $mysql_pass, $mysql_db;
 
      // Zum MySQL Server verbinden
      if (!($db = mysql_connect($mysql_host, $mysql_user, $mysql_pass))) 
        return;
        
      // Datenbank auswÃ¤hlen
      if (!mysql_select_db($mysql_db))
        return;
        
      // Wettbewerb vorhanden?
      $res = mysql_query("SELECT * FROM competitions", $db);
      if (!$res)
        return;
      
      $comps = null;
      while ($comp = mysql_fetch_array($res)) {
        $comps[] = $comp['id'];
      }
        
      return $comps;
    }
  } else {
    function db_get_dbs() {
      $comps = null;
      if ($dh = opendir("../database/")) {
        while (($file = readdir($dh)) !== false) {
          if (substr($file, -4) == ".sdb")
            $comps[] = str_replace(".sdb", "", $file);
        }   
        closedir($dh);
      }
      return $comps;
    }
  }
}
?>
