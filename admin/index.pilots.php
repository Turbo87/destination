<?
$action2 = "show";
if (array_key_exists("action2", $_GET)) $action2 = $_GET['action2'];
if (array_key_exists("action2", $_POST)) $action2 = $_POST['action2'];

if (!function_exists("get_aliase")) {
  function get_aliase($name) {
    $name = trim($name);
    $xname = explode(" ", $name);
    if (count($xname) == 1) {
      return Array($xname);
    } elseif (count($xname) == 2) {
      return Array($xname[0]." ".$xname[1], $xname[1]." ".$xname[0]);
    } elseif (count($xname) > 2) {
      $x = null;
      for ($i = 0; $i < count($xname); $i++) {
        $y = get_aliase(str_replace($xname[$i], "", str_replace($xname[$i]." ", "", $name)));
        for ($j = 0; $j < count($y); $j++) {
          if (!is_array($y[$j])) {
            $x[] = $xname[$i]." ".$y[$j];
          }
        }
      }
      return $x;
    } else return false;
  }
}

if ($action2 == "show") {
  $i = 0;
  $s_pilots = "";
  $res_pilots = db_query("SELECT * FROM %pre%pilots ORDER BY id", $db, $competition['id']);
  while($pilot = db_fetch_array($res_pilots)) {
    $ms = explode("|", $pilot['milestones']);
    $pilot_km = "- km";
    if ($ms[0] != "-" && $ms[0] != "") {
      $pilot_km = "50 km";
    }
    if ($ms[1] != "-" && $ms[1] != "") {
      $pilot_km = "100 km";
    }
    if ($ms[2] != "-" && $ms[2] != "") {
      $pilot_km = "300 km";
    }
    if ($ms[3] != "-" && $ms[3] != "") {
      $pilot_km = "500 km";
    }
    if ($ms[4] != "-" && $ms[4] != "") {
      $pilot_km = "700 km";
    }
    if ($ms[5] != "-" && $ms[5] != "") {
      $pilot_km = "1000 km";
    }
  
    $pilot_alias = "";
    $res_aliase = db_query("SELECT * FROM %pre%pilots_alias WHERE id = '".$pilot['id']."' ORDER BY alias", $db, $competition['id']);
    while($alias = db_fetch_array($res_aliase)) {
      $pilot_alias .= "<a href='javascript:del_alias(\"".$pilot['id']."\", \"".$alias['alias']."\");'>".$alias['alias']."</a>, ";
    }
    $pilot_alias = substr($pilot_alias, 0, strlen($pilot_alias) - 2);
    
    $s_pilots .= "<tr class='list_row_".($i % 2 == 1 ? "o" : "e")."'>
          <td>".$pilot['id']."</td>
          <td>".$pilot_km."</td>
          <td>".$pilot_alias."</td>
          <td width='1' nowrap><a href='javascript:del_pilot(\"".$pilot['id']."\")'><img src='delete.png' alt='L&ouml;schen' border='0'></a> <a href='javascript:add_alias(\"".$pilot['id']."\");'><img src='vcard_add.png' alt='Alias hinzuf&uumlgen;' border='0'></a>  <a href='index.php?action=pilots&action2=edit&id=".$pilot['id']."'><img src='vcard_edit.png' alt='Bearbeiten' border='0'></a></td>
        </tr>
    ";
    $i++;
  }
  if ($i == 0) {
    $s_flights = "<tr>
            <td align='center'>Keine Flüge vorhanden!</td>
          </tr>
";
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Destination Adminbereich</title>
<style>
BODY {
  FONT-FAMILY: Tahoma;
  FONT-SIZE: 12px;
}

INPUT {
  BORDER: solid 1px #004422;
  BACKGROUND: #E8FFE8;
}
TD {
  VERTICAL-ALIGN: top
}
TR.list_row_e {
  BACKGROUND: #FFFFFF;
}
TR.list_row_e {
  BACKGROUND: #E8FFE8;
}
TR.list_row_x {
  BACKGROUND: #FFC8C8;
  COLOR: #444444;
}
</style>
<script type="text/javascript">
  <!--
  parent.frames[0].toogleNav(4);
  
  function del_alias(id, alias) {
    if (confirm("Wollen Sie den Alias '"+alias+"' wirklich loeschen?")) {
      location.href="index.php?action=pilots&action2=del_alias&id="+id+"&alias="+alias;
    }
  }
  function add_alias(id) {
    var alias = prompt("Bitte geben Sie einen neuen Alias fuer '"+id+"' ein:");
    if (alias != null && alias != id) {
      location.href="index.php?action=pilots&action2=add_alias&id="+id+"&alias="+alias;
    }
  }
  function del_pilot(id) {
    if (confirm("Wollen Sie den Piloten '"+id+"' wirklich loeschen?")) {
      location.href="index.php?action=pilots&action2=del&id="+id;
    }
  }
  function add_pilot() {
    var id = prompt("Bitte geben Sie einen neuen Piloten ein:");
    if (id != null) {
      location.href="index.php?action=pilots&action2=add&id="+id;
    }
  }
  //-->
</script>
</head>
<body>
<table style="WIDTH: 100%;">
<tr>
<td>

		<table align="center">
			<tr>
				<td style="PADDING: 10px; FONT-SIZE: 20px; " align="center"><?=$competition['name']?> - Adminbereich</td>
			</tr>
			<tr>
				<td style="PADDING: 5px;" align="center">
				<table width="600" cellpadding="4">
        <tr>
        <td>Pilot:</td>
        <td nowrap>max. km:</td>
        <td>Aliase:</td>
        <td><a href='javascript:add_pilot();'><img src='add.png' alt='Hinzuf&uuml;gen' border='0'></a></td>
        </tr>
        <?=$s_pilots?>
        </table>
				</td>
			</tr>
		</table>

		</td>
</tr>
</table>
</body>
</html>
<?
} elseif ($action2 == "edit") {
  if (!array_key_exists("id", $_GET))
  header("LOCATION: index.php?action=pilots");
  $id = $_GET["id"];
  
  $res_pilots = db_query("SELECT * FROM %pre%pilots WHERE id = '".$id."' ORDER BY id LIMIT 1", $db, $competition['id']);
  while($pilot = db_fetch_array($res_pilots)) {  
    $ms = explode("|",$pilot["milestones"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Destination Adminbereich</title>
<style>
BODY {
  FONT-FAMILY: Tahoma;
  FONT-SIZE: 12px;
}

INPUT {
  BORDER: solid 1px #004422;
  BACKGROUND: #E8FFE8;
  WIDTH: 200px;
}
TD {
  VERTICAL-ALIGN: top
}
TR.list_row_e {
  BACKGROUND: #FFFFFF;
}
TR.list_row_e {
  BACKGROUND: #E8FFE8;
}
TR.list_row_x {
  BACKGROUND: #FFC8C8;
  COLOR: #444444;
}
</style>
<script type="text/javascript">
  <!--
  parent.frames[0].toogleNav(4);
  //-->
</script>
</head>
<body>
<table style="WIDTH: 100%;">
<tr>
<td>

		<table align="center">
			<tr>
				<td style="PADDING: 10px; FONT-SIZE: 20px; " align="center"><?=$competition['name']?> - Adminbereich</td>
			</tr>
			<tr>
				<td style="PADDING: 5px;" align="center">
				<table width="600" cellpadding="4">
        <tr>
        <td style="BORDER: 1px solid #004422; BACKGROUND: #004422; COLOR: white; FONT-SIZE: 14px; FONT-WEIGHT: bold;" colspan="2"><?=$id?></td>
        </tr>
        <tr>
        <td style="BORDER: 1px solid #004422;">
        Ersetzen des Hauptnamens:<br><br>
<?
  $res_aliase = db_query("SELECT * FROM %pre%pilots_alias WHERE id = '".$id."' ORDER BY alias", $db, $competition['id']);
  while($alias = db_fetch_array($res_aliase)) {  

?>
        <a href="index.php?action=pilots&action2=replace_alias&id=<?=$id?>&alias=<?=$alias['alias']?>"><?=$alias['alias']?></a><br>
<?
}
?>
        </td>
        <td style="BORDER: 1px solid #004422;" rowspan="2">
        <form method="POST" action="index.php">
        <input type="hidden" name="action" value="pilots">
        <input type="hidden" name="action2" value="edit_ms">
        <input type="hidden" name="id" value="<?=$id?>">
        &Auml;nderung der Meilensteine:<br><br>
        <i>- = noch nicht geflogen<br>
        1 = geflogen, Datum unbekannt<br>
        dd.mm.jjjj = geflogen an Datum ...</i><br><br>
        50km: <?=$ms[0]?><br>
        <input name="ms1" value="<?=($ms[0] > 1 ? date("d.m.Y", $ms[0]) : $ms[0])?>"><br><br>
        100km: <?=$ms[1]?><br>
        <input name="ms2" value="<?=($ms[1] > 1 ? date("d.m.Y", $ms[1]) : $ms[1])?>"><br><br>
        300km: <?=$ms[2]?><br>
        <input name="ms3" value="<?=($ms[2] > 1 ? date("d.m.Y", $ms[2]) : $ms[2])?>"><br><br>
        500km: <?=$ms[3]?><br>
        <input name="ms4" value="<?=($ms[3] > 1 ? date("d.m.Y", $ms[3]) : $ms[3])?>"><br><br>
        700km: <?=$ms[4]?><br>
        <input name="ms5" value="<?=($ms[4] > 1 ? date("d.m.Y", $ms[4]) : $ms[4])?>"><br><br>
        1000km: <?=$ms[5]?><br>
        <input name="ms6" value="<?=($ms[5] > 1 ? date("d.m.Y", $ms[5]) : $ms[5])?>"><br><br>
        <input type="Submit" value="&Auml;ndern">
        </form>
        </td>
        </tr>
        <tr>
        <td style="BORDER: 1px solid #004422;">
        Alle m&ouml;glichen Aliase generieren:<br><br>
        <a href="index.php?action=pilots&action2=gen_alias&id=<?=$id?>">Generieren!</a>
        </td>
        </tr>
        <tr>
        <td style="BORDER: 1px solid #004422; BACKGROUND: #004422; COLOR: white; FONT-SIZE: 14px; FONT-WEIGHT: bold;" colspan="2"><a href="index.php?action=pilots" style="COLOR: white;">&lt; Zur&uuml;ck</a></td>
        </tr>
        </table>
				</td>
			</tr>
		</table>

		</td>
</tr>
</table>
</body>
</html>
<?
  }
} elseif ($action2 == "del") {
  if (!array_key_exists("id", $_GET))
  header("LOCATION: index.php?action=pilots");
  $id = $_GET["id"];

  db_query("DELETE FROM %pre%pilots WHERE id = '".$id."'", $db, $competition['id']);
  db_query("DELETE FROM %pre%pilots_alias WHERE id = '".$id."'", $db, $competition['id']);
  header("LOCATION: index.php?action=pilots");
} elseif ($action2 == "del_alias") {
  if (!array_key_exists("id", $_GET) || !array_key_exists("alias", $_GET))
  header("LOCATION: index.php?action=pilots");
  $id = $_GET["id"];
  $alias = $_GET["alias"];
  
  db_query("DELETE FROM %pre%pilots_alias WHERE id = '".$id."' AND alias = '".$alias."'", $db, $competition['id']);
  header("LOCATION: index.php?action=pilots");
} elseif ($action2 == "replace_alias") {
  if (!array_key_exists("id", $_GET) || !array_key_exists("alias", $_GET))
  header("LOCATION: index.php?action=pilots");
  $id = $_GET["id"];
  $alias = $_GET["alias"];
  
  db_query("UPDATE %pre%pilots SET id = '".$alias."' WHERE id = '".$id."'", $db, $competition['id']);
  db_query("UPDATE %pre%pilots_alias SET id = '".$alias."' WHERE id = '".$id."'", $db, $competition['id']);
  db_query("UPDATE %pre%pilots_alias SET alias = '".$id."' WHERE alias = '".$alias."'", $db, $competition['id']);
  header("LOCATION: index.php?action=pilots&action2=edit&id=".$alias);
} elseif ($action2 == "edit_ms") {
  if (!array_key_exists("id", $_POST))
    header("LOCATION: index.php?action=pilots");
  $id = $_POST["id"];
    
  if (!array_key_exists("ms3", $_POST) || !array_key_exists("ms4", $_POST) || !array_key_exists("ms5", $_POST))
    header("LOCATION: index.php?action=pilots&action2=edit&id=".$id);
  if (!array_key_exists("ms6", $_POST) || !array_key_exists("ms1", $_POST) || !array_key_exists("ms2", $_POST))
    header("LOCATION: index.php?action=pilots&action2=edit&id=".$id);
  
  $ms1 = $_POST["ms1"];
  if ($ms1 == "") $ms1 = "-";
  if ($ms1 != "-" && $ms1 != "1") {
    $ms1 = explode(".", $ms1);
    if (count($ms1) != 3)
      header("LOCATION: index.php?action=pilots&action2=edit&id=".$id);
    
    $ms1 = mktime(12,0,0,$ms1[1],$ms1[0],$ms1[2]);
  } 
  
  $ms2 = $_POST["ms2"];
  if ($ms2 == "") $ms2 = "-";
  if ($ms2 != "-" && $ms2 != "1") {
    $ms2 = explode(".", $ms2);
    if (count($ms2) != 3)
      header("LOCATION: index.php?action=pilots&action2=edit&id=".$id);
    
    $ms2 = mktime(12,0,0,$ms2[1],$ms2[0],$ms2[2]);
  } 
  
  $ms3 = $_POST["ms3"];
  if ($ms3 == "") $ms3 = "-";
  if ($ms3 != "-" && $ms3 != "1") {
    $ms3 = explode(".", $ms3);
    if (count($ms3) != 3)
      header("LOCATION: index.php?action=pilots&action2=edit&id=".$id);
    
    $ms3 = mktime(12,0,0,$ms3[1],$ms3[0],$ms3[2]);
  }
  
  $ms4 = $_POST["ms4"];
  if ($ms4 == "") $ms4 = "-";
  if ($ms4 != "-" && $ms4 != "1") {
    $ms4 = explode(".", $ms4);
    if (count($ms4) != 3)
      header("LOCATION: index.php?action=pilots&action2=edit&id=".$id);
    
    $ms4 = mktime(12,0,0,$ms4[1],$ms4[0],$ms4[2]);
  } 
  
  $ms5 = $_POST["ms5"];
  if ($ms5 == "") $ms5 = "-";
  if ($ms5 != "-" && $ms5 != "1") {
    $ms5 = explode(".", $ms5);
    if (count($ms5) != 3)
      header("LOCATION: index.php?action=pilots&action2=edit&id=".$id);
    
    $ms5 = mktime(12,0,0,$ms5[1],$ms5[0],$ms5[2]);
  } 
  
  $ms6 = $_POST["ms6"];
  if ($ms6 == "") $ms6 = "-";
  if ($ms6 != "-" && $ms6 != "1") {
    $ms6 = explode(".", $ms6);
    if (count($ms6) != 3)
      header("LOCATION: index.php?action=pilots&action2=edit&id=".$id);
    
    $ms6 = mktime(12,0,0,$ms6[1],$ms6[0],$ms6[2]);
  } 
  
  $ms = $ms1."|".$ms2."|".$ms3."|".$ms4."|".$ms5."|".$ms6;

  db_query("UPDATE %pre%pilots SET milestones = '".$ms."' WHERE id = '".$id."'", $db, $competition['id']);
  header("LOCATION: index.php?action=pilots&action2=edit&id=".$id);
} elseif ($action2 == "add") {
  if (!array_key_exists("id", $_GET))
  header("LOCATION: index.php?action=pilots");
  $id = $_GET["id"];

  db_query("INSERT INTO %pre%pilots VALUES('".$id."','-|-|-|-|-|-')", $db, $competition['id']);
  $aliase = get_aliase($id);
  for ($i = 0; $i < count($aliase); $i++) {
    if ($aliase[$i] != $id)
      db_query("INSERT INTO %pre%pilots_alias VALUES('".$aliase[$i]."','".$id."')", $db, $competition['id']);
  }
  header("LOCATION: index.php?action=pilots");
} elseif ($action2 == "gen_alias") {
  if (!array_key_exists("id", $_GET))
  header("LOCATION: index.php?action=pilots");
  $id = $_GET["id"];

  $aliase = get_aliase($id);
  for ($i = 0; $i < count($aliase); $i++) {
    if ($aliase[$i] != $id)
      db_query("INSERT IGNORE INTO %pre%pilots_alias VALUES('".$aliase[$i]."','".$id."')", $db, $competition['id']);
  }
  header("LOCATION: index.php?action=pilots&action2=edit&id=".$id);
} elseif ($action2 == "add_alias") {
  if (!array_key_exists("id", $_GET) || !array_key_exists("alias", $_GET))
  header("LOCATION: index.php?action=pilots");
  $id = $_GET["id"];
  $alias = $_GET["alias"];
  
  db_query("INSERT IGNORE INTO %pre%pilots_alias VALUES('".$alias."','".$id."')", $db, $competition['id']);
  header("LOCATION: index.php?action=pilots");
} 
?>