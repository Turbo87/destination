<?
if (!function_exists("isActivePilot")) {
  // Aktivität des Piloten prüfen
  function isActivePilot($pilotname, $db, $competition) {
    $pilotname = getAlias($pilotname, $db, $competition, false);
    if (!$pilotname) return false;
    
    $res_pilots = db_query("SELECT * FROM %pre%pilots WHERE id = '".$pilotname."'", $db, $competition['id']);
    if ($pilot = db_fetch_array($res_pilots)) {
      $flightcount = db_query_count("SELECT * FROM %pre%flights WHERE pilot = '".$pilotname."' AND date > ".(time() - (60*60*24*365*1.5)), $db, $competition['id']);
      if ($flightcount > 0) return true;
    }
    return false;
  }
}

if (!function_exists("isPlane")) {
  // Aktivität des Piloten prüfen
  function isPlane($plane, $db, $competition) {
    $planecount = db_query_count("SELECT * FROM %pre%flights WHERE plane_callsign = '".$plane."'", $db, $competition['id']);
    if ($planecount > 0) return true;
    return false;
  }
}

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

if (!function_exists("show_error")) {
  // Fehler anzeigen und weitere SkriptausfÃ¼hrung unterbinden
  function show_error($msg) {
    $out = new Template("templates/error.tpl");
    $out->addVariable("time", date("H:i:s"));
    $out->addVariable("date", date("d.m.Y"));
    $out->addVariable("msg", str_replace("&gt;", ">", str_replace("&lt;", "<", htmlentities($msg))));
    echo $out->getOutput();
    die;
  }
}

if (!function_exists("log_error")) {
  // Fehler loggen
  function log_error($msg) {
    $fp = fopen("error.log", "a");
    fwrite($fp, date("d.m.Y H:i:s")." - ".$msg."\r\n");
    fclose($fp);
  }
}

if (!function_exists("handleError")) {
  // Funktion zur PHP-Fehlerverarbeitung
  function handleError ($errno, $errstr, $errfile, $errline) {
    log_error("Fehler $errno\r\n$errstr\r\n$errfile:$errline\r\n----------------------------------------");
    if ($errno < 8)
      show_error("<b>Fehler $errno</b><br /><br />\r\n$errstr<br />\r\n$errfile:$errline");
  }
}

if (!function_exists("ignoreError")) {
  // Funktion zur PHP-Fehlerverarbeitung
  function ignoreError ($errno, $errstr, $errfile, $errline) {
    log_error("Fehler $errno\r\n$errstr\r\n$errfile:$errline\r\n----------------------------------------");
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

if (!function_exists("masort")) {
  function masort(&$data, $sortby)
  {
     static $sort_funcs = array();
   
     if (empty($sort_funcs[$sortby])) {
         $code = "\$c=0;";
         foreach (split(',', $sortby) as $key) {
           $array = array_pop($data);
           array_push($data, $array);
           if(is_numeric($array[$key]))
             $code .= "if ( \$c = ((\$a['$key'] == \$b['$key']) ? 0:((\$a['$key'] < \$b['$key']) ? -1 : 1 )) ) return \$c;";
           else
             $code .= "if ( (\$c = strcasecmp(\$a['$key'],\$b['$key'])) != 0 ) return \$c;\n";
         }
         $code .= 'return $c;';
         $sort_func = $sort_funcs[$sortby] = create_function('$a, $b', $code);
     } else {
         $sort_func = $sort_funcs[$sortby];
     }
     $sort_func = $sort_funcs[$sortby];
     uasort($data, $sort_func);
  }
}
?>