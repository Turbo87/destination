<?
if (!function_exists("getAlias")) {
  function getAlias ($pilotname, $db, $competition, $create_new = true) {
    $res_pilots = db_query("SELECT * FROM %pre%pilots WHERE id = '".$pilotname."'", $db, $competition['id']);
    if (!($pilot = db_fetch_array($res_pilots))) {
      $res_alias = db_query("SELECT * FROM %pre%pilots_alias WHERE alias = '".$pilotname."'", $db, $competition['id']);
      if ($alias = db_fetch_array($res_alias)) {
        $pilotname = $alias['id'];
      } else if ($create_new) {
        db_query("INSERT INTO %pre%pilots VALUES('".$pilotname."', '-|-|-|-|-|-')", $db, $competition['id']);
      } else {
        return false;
      }
    }
    return $pilotname;
  }
}

if (!function_exists("addZeros")) {
  function addZeros($number, $digits, $append = false) {
    if ($append) {
      if (strpos($number, ".") == false)
        $number .= ".";
  
      while (strlen(strstr($number, ".")) - 1 < $digits) {
        $number .= "0";
      }
    } else {
      if ($number < 0) $digits++;
      while (strlen($number) < $digits) {
        $number = "0" . $number;
      }
    }
  
    return $number;
  }
}

if (!function_exists("fixPlaneCallsign")) {
  function fixPlaneCallsign($c) {
    $c = trim($c);
    $c = strtoupper($c);
    $c = str_replace("*", "", $c);
    $c = str_replace(".", "", $c);
    $c = str_replace("_", "-", $c);
    $c = str_replace("+", "-", $c);
    $c = str_replace("D ", "D-", $c);
    while (strpos($c,"--") !== false) {
      $c = str_replace("--", "-", $c);
    }
    if (substr($c, -1) == ")") $c = substr($c, 0, strlen($c) - strlen(strrchr($c, "(")));
    if ($c == "COMPETITION-ID-:") $c = "";
    if ($c == "D-") $c = "";
    if ($c == "UNBEKANNT") $c = "";
    if ($c == "NOTSET") $c = "";
    if (substr($c, -1) == "-") $c = substr($c, 0, strlen($c) - 1);
    if (strlen($c) == 4 && (is_numeric($c) || ($c{0} == "K" && !is_numeric($c{1}) && !is_numeric($c{2}) && !is_numeric($c{3})))) $c = "D-".$c;
    if (substr($c,0,1) == "D" && strlen($c) == 5 && ((is_numeric(substr($c,1)) && $c{1} != "-") || ($c{1} == "K" && !is_numeric($c{2}) && !is_numeric($c{3}) && !is_numeric($c{4})))) $c = "D-".substr($c,1);
    if (substr($c,0,2) == "D-") {
      $x = substr($c, 2);
      if (strlen($x) > 4) {
        $x = str_replace("-", "", $x);
        $c = "D-".$x;
      }
      $x = substr($c, 2);
      if (strlen($x) > 4) {
        $c = "D-".substr($x,0,4);
      }
      
      $x = substr($c, 2);
      
      if (strlen($x) == 4 && !is_numeric($x)) {
        if ($x{0} != "K" || is_numeric($x{1}) || is_numeric($x{2}) || is_numeric($x{3}))
          $c = "";
      }
      if (strlen($x) < 4) $c = "";
    }
    return $c;
  }
}

if (!function_exists("updateProgress")) {
  function updateProgress ($percentage, $divider, $db, $competition) {
    $p = $percentage / $divider[1];
    $p = $p + ($divider[0] / $divider[1]);
    
    $res_percent = db_query("SELECT * FROM %pre%config WHERE name = 'update_progress'", $db, $competition['id']);
    if (!($temp = db_fetch_array($res_percent))) {
      db_query("INSERT INTO %pre%config VALUES('update_progress', '".$p."')", $db, $competition['id']);
    } else {
      db_query("UPDATE %pre%config SET value = '".$p."' WHERE name = 'update_progress'", $db, $competition['id']);
    }
  }
}

if (!function_exists("log2file")) {
  function log2file ($str) {
    // In Datei loggen (anhängen)
    $fp = fopen("update.log", "a");
    fwrite($fp, date("d.m.Y H:i:s")." - ".$str."\r\n");
    fclose($fp);
  }
}

if (!function_exists("die2")) {
  function die2 ($str = "") {
    // Fehler loggen und Ausführung des Updates abbrechen
    if (trim($str) != "")
      log2file("ERROR - $str");
    else
      log2file("ERROR - No Reason Given");

    die($str);
  }
}

if (!function_exists("show_error")) {
  function show_error ($str = "") {
    // Spiegelfunktion zu die2 auf Grund der Verwendung in sql.php 
    die2($str);
  }
}


if (!function_exists("getHTTPFile")) {
  // Standard ;)
  function getHTTPFile ($host, $port, $filename) {
    global $traffic;
    
    $fp = fsockopen ($host, $port, $errno, $errstr, 5);
    if (!$fp) {
      log2file("ERROR: Socket unavailable");
      return false;
    } else {
      $file = "";
      $r = "GET /".$filename." HTTP/1.0\r\n";
      $r .= "Host: $host\r\n";
      $r .= "\r\n";
      fputs ($fp, $r);
      $traffic['bytes'] += strlen($r);
      while (!feof($fp)) {
        $file = $file.fgets($fp,128);
      }
      fclose($fp);
      $traffic['bytes'] += strlen($file);
      $traffic['files']++;
      if (trim($file) != "") {
        $f = explode("\r\n", $file);
        $x = explode(" ", $f[0]);
  
        if ($x[1] != "200") {
          log2file("ERROR: HTTP Request returned ".$x[1]." - File appended");
          log2file($file);
          return false;
        }
        else return $file;
      } else {
        log2file("ERROR: Empty File");
        return false;
      }
    }
  }
}
/*
if (!function_exists("getHTTPFile")) {
  // Standard ;)
  function getHTTPFile ($host, $port, $filename) {
    $url = "http://".$host."/".$filename;
    $url = urlencode($url);
    return getHTTPFile2("proxy.org", 80, "proxy.pl?url=$url&proxy=hidemyass.com");     
  }
}

if (!function_exists("getHTTPFile2")) {
  // Standard ;)
  function getHTTPFile2 ($host, $port, $filename) {
    global $traffic;
    
    $fp = fsockopen ($host, $port, $errno, $errstr, 5);
    if (!$fp) {
      log2file("ERROR: Socket unavailable");
      return false;
    } else {
      $file = "";
      $r = "GET /".$filename." HTTP/1.1\r\n";
      $r .= "Host: $host\r\n";
      $r .= "\r\n";
      fputs ($fp, $r);
      $traffic['bytes'] += strlen($r);
      while (!feof($fp)) {
        $file = $file.fgets($fp,128);
      }
      fclose($fp);
      $traffic['bytes'] += strlen($file);
      $traffic['files']++;
      if (trim($file) != "") {
        $f = explode("\r\n", $file);
        $x = explode(" ", $f[0]);
  
        if ($x[1] == "302") {
            for ($i = 0; $i < count($f); $i++) {
                if (substr($f[$i],0,9) == "Location:") {
                    $url = trim(substr($f[$i], 9));
                }
            }
            if (trim($url) == ""){
                log2file("ERROR: Location Header not found");
                return false;
            }
            $host2 = substr(strstr($url, "//"), 2);
            $host2 = substr($host2, 0, strlen($host2) - strlen(strstr($host2, "/")));
            $file2 = substr(strstr($url, "//"), 2);
            $file2 = substr(strstr($file2, "/"), 1);
            return getHTTPFile2($host2, $port, $file2);
        } else if ($x[1] != "200") {
          log2file("ERROR: HTTP Request returned ".$x[1]." - File appended");
          log2file($file);
          return false;
        }
        else return $file;
      } else {
        log2file("ERROR: Empty File");
        return false;
      }
    }
  }
}
*/
if (!function_exists("handleError")) {
  // Funktion zur PHP-Fehler-Verarbeitung
  function handleError ($errno, $errstr, $errfile, $errline) {
    log2file("ERROR $errno\r\n----------------------------------------\r\n$errstr\r\n$errfile:$errline\r\n----------------------------------------");
    return;
  }
}

if (!function_exists("handleErrorWeather")) {
  // Funktion zur PHP-Fehler-Verarbeitung
  function handleErrorWeather ($errno, $errstr, $errfile, $errline) {
    log2file("Weather-ERROR $errno\r\n----------------------------------------\r\n$errstr\r\n$errfile:$errline\r\n----------------------------------------");
    return;
  }
}
?>