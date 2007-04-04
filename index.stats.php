<?
/*
 * index.stats.php
 * Created on 17.07.2008 by Tobias Bieniek
 */

include_once("filemanager.php");

$arg1 = "1";
if (array_key_exists('arg1', $_GET)) 
  $arg1 = $_GET['arg1'];
  
$arg2 = "0";
if (array_key_exists('arg2', $_GET)) 
  $arg2 = $_GET['arg2'];
$arg2x = base64_decode($arg2);
 
$arg3 = "0";
if (array_key_exists('arg3', $_GET)) 
  $arg3 = $_GET['arg3'];
  
$temp = new Template("templates/".$competition['lang']."/stats.tpl");

$temp->addVariable("arg1", $arg1);
$temp->addVariable("arg2", $arg2);
$temp->addVariable("arg2x", $arg2x);
$temp->addVariable("arg3", $arg3);

$script = "";
$content = "";
$planes = "";
$pilots = "";

$submenu_overview_style = false;
if ($arg1 == "1") {
  $submenu_overview_style = true;
  $script .= "focus('tr_overview');";
  
  if ($arg3 == "flights") {
    $script .= "focus('tr_overview_flights');";
    $content = "<img src=\"prerender/".$competition['id'].".overview_flights.png\" style=\"BORDER: #004422 1px solid;\">";
  } else if ($arg3 == "time") {
    $script .= "focus('tr_overview_time');";
    $content = "<img src=\"prerender/".$competition['id'].".overview_time.png\" style=\"BORDER: #004422 1px solid;\">";
  } else if ($arg3 == "pilots") {
    $script .= "focus('tr_overview_pilots');";
    $content = "<img src=\"prerender/".$competition['id'].".overview_pilots.png\" style=\"BORDER: #004422 1px solid;\"><br /><br />";
    $xtemp = new Template("prerender/".$competition['id'].".overview_pilots.htm");
    $content .= $xtemp->getOutput();
  } else {
    $script .= "focus('tr_overview_distance');";   
    $content = "<img src=\"prerender/".$competition['id'].".overview_distance.png\" style=\"BORDER: #004422 1px solid;\"><br /><br />";
    $xtemp = new Template("prerender/".$competition['id'].".overview_distance.htm");
    $content .= $xtemp->getOutput();
  }
}
  
$dropdown_planes_style = false;
$submenu_planes_style = false;
$submenu_planes_compare_style = false;
if ($arg1 == "2") {
  $dropdown_planes_style = true;
  $script .= "focus('tr_planes');";
    
  $res_planes = db_query("SELECT * FROM %pre%flights WHERE plane_callsign != '' GROUP BY plane_callsign ORDER BY plane_callsign", $db, $competition['id']);
  while ($plane = db_fetch_array($res_planes)) {
    $planes .= "<option value=\"".base64_encode($plane['plane_callsign'])."\"".(($arg2x == $plane['plane_callsign']) ? " selected" : "").">".$plane['plane_callsign']." (".$plane['plane'].")";
  }
  
  if ($arg2 == "compare") {
    $submenu_planes_compare_style = true;
    
    if ($arg3 == "flights") {
      $script .= "focus('tr_planes_compare_flights');";
      $content = "<img src=\"prerender/".$competition['id'].".planes_compare_flights.png\" style=\"BORDER: #004422 1px solid;\">";
    } else if ($arg3 == "time") {
      $script .= "focus('tr_planes_compare_time');";
      $content = "<img src=\"prerender/".$competition['id'].".planes_compare_time.png\" style=\"BORDER: #004422 1px solid;\">";
    } else {
      $script .= "focus('tr_planes_compare_distance');";   
      $content = "<img src=\"prerender/".$competition['id'].".planes_compare_distance.png\" style=\"BORDER: #004422 1px solid;\">";
    }
  } else if ($arg2 != "0" && isPlane($arg2x, $db, $competition)) {
    $submenu_planes_style = true;
    
    if ($arg3 == "distance") {
      $script .= "focus('tr_planes_distance');";
      $content = "<img src=\"".getFilename("prerender/".$competition['id'].".planes_distance.$arg2.png", $db, $competition)."\" style=\"BORDER: #004422 1px solid;\">";
    } else if ($arg3 == "flights") {
      $script .= "focus('tr_planes_flights');";
      $content = "<img src=\"".getFilename("prerender/".$competition['id'].".planes_flights.$arg2.png", $db, $competition)."\" style=\"BORDER: #004422 1px solid;\">";
    } else if ($arg3 == "time") {
      $script .= "focus('tr_planes_time');";
      $content = "<img src=\"".getFilename("prerender/".$competition['id'].".planes_time.$arg2.png", $db, $competition)."\" style=\"BORDER: #004422 1px solid;\">";
    } else if ($arg3 == "pilots") {
      $script .= "focus('tr_planes_pilots');";
      //$content = "<img src=\"".getFilename("prerender/".$competition['id'].".planes_pilots.$arg2.png", $db, $competition)."\" style=\"BORDER: #004422 1px solid;\"><br /><br />";
      $xtemp = new Template(getFilename("prerender/".$competition['id'].".planes_pilots.$arg2.htm", $db, $competition));
      $content = $xtemp->getOutput();
    } else {
      $script .= "focus('tr_planes_overview');";   
      $xtemp = new Template(getFilename("prerender/".$competition['id'].".planes_overview.$arg2.htm", $db, $competition));
      $content = $xtemp->getOutput();
    }
  }
}

$dropdown_pilots_style = false;
$submenu_pilots_style = false;
$submenu_pilots_compare_style = false;
if ($arg1 == "3") {
  $dropdown_pilots_style = true;
  $script .= "focus('tr_pilots');";
    
  $res_pilots = db_query("SELECT * FROM %pre%pilots ORDER BY id", $db, $competition['id']);
  while ($pilot = db_fetch_array($res_pilots)) {
    if (isActivePilot($pilot['id'], $db, $competition))
      $pilots .= "<option value=\"".base64_encode($pilot['id'])."\"".(($arg2x == $pilot['id']) ? " selected" : "").">".$pilot['id'];
  }
  
  if ($arg2 == "compare") {
    $submenu_pilots_compare_style = true;

    if ($arg3 == "flights") {
      $script .= "focus('tr_pilots_compare_flights');";
      $content = "<img src=\"prerender/".$competition['id'].".pilots_compare_flights.png\" style=\"BORDER: #004422 1px solid;\">";
    } else if ($arg3 == "time") {
      $script .= "focus('tr_pilots_compare_time');";
      $content = "<img src=\"prerender/".$competition['id'].".pilots_compare_time.png\" style=\"BORDER: #004422 1px solid;\">";
    } else {
      $script .= "focus('tr_pilots_compare_distance');";   
      $content = "<img src=\"prerender/".$competition['id'].".pilots_compare_distance.png\" style=\"BORDER: #004422 1px solid;\">";
    }
  } else if (getAlias($arg2x, $db, $competition, false) != false) {
    $submenu_pilots_style = true;
    
    if ($arg3 == "distance") {
      $script .= "focus('tr_pilots_distance');";
      $content = "<img src=\"".getFilename("prerender/".$competition['id'].".pilots_distance.$arg2.png", $db, $competition)."\" style=\"BORDER: #004422 1px solid;\">";
    } else if ($arg3 == "flights") {
      $script .= "focus('tr_pilots_flights');";
      $content = "<img src=\"".getFilename("prerender/".$competition['id'].".pilots_flights.$arg2.png", $db, $competition)."\" style=\"BORDER: #004422 1px solid;\">";
    } else if ($arg3 == "time") {
      $script .= "focus('tr_pilots_time');";
      $content = "<img src=\"".getFilename("prerender/".$competition['id'].".pilots_time.$arg2.png", $db, $competition)."\" style=\"BORDER: #004422 1px solid;\">";
    } else if ($arg3 == "planes") {
      $script .= "focus('tr_pilots_planes');";
      //$content = "<img src=\"".getFilename("prerender/".$competition['id'].".pilots_planes.$arg2.png", $db, $competition)."\" style=\"BORDER: #004422 1px solid;\"><br /><br />";
      $xtemp = new Template(getFilename("prerender/".$competition['id'].".pilots_planes.$arg2.htm", $db, $competition));
      $content = $xtemp->getOutput();
    } else {
      $script .= "focus('tr_pilots_overview');";   
      $xtemp = new Template(getFilename("prerender/".$competition['id'].".pilots_overview.$arg2.htm", $db, $competition));
      $xtemp->addVariable("img", getFilename("prerender/".$competition['id'].".pilots_overview.$arg2.png", $db, $competition));
      $content = $xtemp->getOutput();
    }

  }
}

$temp->addVariable("submenu_overview_style", ($submenu_overview_style ? "" : "DISPLAY: none"));

$temp->addVariable("planes", $planes);
$temp->addVariable("dropdown_planes_style", ($dropdown_planes_style ? "" : "DISPLAY: none"));
$temp->addVariable("submenu_planes_style", ($submenu_planes_style ? "" : "DISPLAY: none"));
$temp->addVariable("submenu_planes_compare_style", ($submenu_planes_compare_style ? "" : "DISPLAY: none"));
$temp->addVariable("planes_compare_selected", ($arg2 == "compare" ? " selected" : ""));

$temp->addVariable("pilots", $pilots);
$temp->addVariable("dropdown_pilots_style", ($dropdown_pilots_style) ? "" : "DISPLAY: none");
$temp->addVariable("submenu_pilots_style", ($submenu_pilots_style ? "" : "DISPLAY: none"));
$temp->addVariable("submenu_pilots_compare_style", ($submenu_pilots_compare_style ? "" : "DISPLAY: none"));
$temp->addVariable("pilots_compare_selected", ($arg2 == "compare" ? " selected" : ""));

$temp->addVariable("script", $script);
$temp->addVariable("content", $content);
?>