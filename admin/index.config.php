<?
if (array_key_exists("action2", $_POST) && $_POST['action2'] == "chg") {
  if(array_key_exists("cname", $_POST) && trim($_POST['cname']) != "")
  db_query("UPDATE %pre%config SET value='".$_POST['cname']."' WHERE name='name'", $db, $competition['id']);

  if(array_key_exists("clubs", $_POST) && trim($_POST['clubs']) != "")
  db_query("UPDATE %pre%config SET value='".$_POST['clubs']."' WHERE name='clubs'", $db, $competition['id']);

  if(array_key_exists("clubs_names", $_POST) && trim($_POST['clubs_names']) != "")
  db_query("UPDATE %pre%config SET value='".$_POST['clubs_names']."' WHERE name='clubs_names'", $db, $competition['id']);

  if(array_key_exists("homebase", $_POST) && trim($_POST['homebase']) != "")
  db_query("UPDATE %pre%config SET value='".$_POST['homebase']."' WHERE name='homebase'", $db, $competition['id']);

  if(array_key_exists("homefactor", $_POST) && trim($_POST['homefactor']) != "")
  db_query("UPDATE %pre%config SET value='".str_replace(",", ".", $_POST['homefactor'])."' WHERE name='homefactor'", $db, $competition['id']);

  if(array_key_exists("clubs", $_POST) && trim($_POST['clubs']) != "")
  db_query("UPDATE %pre%config SET value='".$_POST['clubs']."' WHERE name='clubs'", $db, $competition['id']);
  
  if(array_key_exists("factor1", $_POST) && array_key_exists("factor2", $_POST) && array_key_exists("factor3", $_POST) && 
  array_key_exists("factor4", $_POST) && array_key_exists("factor5", $_POST) && array_key_exists("factor6", $_POST) && 
  array_key_exists("factor7", $_POST)) {
    $factor1 = str_replace(",", ".", $_POST['factor1']);
    $factor2 = str_replace(",", ".", $_POST['factor2']);
    $factor3 = str_replace(",", ".", $_POST['factor3']);
    $factor4 = str_replace(",", ".", $_POST['factor4']);
    $factor5 = str_replace(",", ".", $_POST['factor5']);
    $factor6 = str_replace(",", ".", $_POST['factor6']);
    $factor7 = str_replace(",", ".", $_POST['factor7']);
    $factors = $factor1."|".$factor2."|".$factor3."|".$factor4."|".$factor5."|".$factor6."|".$factor7;
    db_query("UPDATE %pre%config SET value='".$factors."' WHERE name='factors'", $db, $competition['id']);
  }
  
  if(array_key_exists("pass1", $_POST) && array_key_exists("pass2", $_POST))
    if (trim($_POST['pass1']) != "" && trim($_POST['pass1']) == trim($_POST['pass2'])) {
      db_query("UPDATE %pre%config SET value='".trim($_POST['pass1'])."' WHERE name='password'", $db, $competition['id']);
      setcookie("desti_admin_pass", trim($_POST['pass1']));
    }

  header("LOCATION: index.php?action=config");
  die;
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
</style>
<script type="text/javascript">
  <!--
  parent.frames[0].location.reload();
  setTimeout("parent.frames[0].toogleNav(1);", "500");
  setTimeout("parent.frames[0].toogleNav(1);", "1000");
  setTimeout("parent.frames[0].toogleNav(1);", "10000");
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
				<td style="PADDING: 5px;">
				<form method="POST" action="index.php">
        Name des Wettbewerbs:<br>
				<input name="cname" value="<?=$competition['name']?>" style="WIDTH: 100%;"><br>
        <br>
        <hr noshade="noshade" color="#004422">
        <br>
				Vereine (IDs im Format Verein1|Verein2|...):<br>
        <input name="clubs" value="<?=$competition['config']['clubs']?>" style="WIDTH: 100%;"><br>
        <br>
        Vereine (Namen im Format Verein1|Verein2|...):<br>
        <input name="clubs_names" value="<?=$competition['config']['clubs_names']?>" style="WIDTH: 100%;"><br>
        <br>
        <hr noshade="noshade" color="#004422">
        <br>
        Heimatplatz/-pl&auml;tze (im Format Platz1|Platz2|...):<br>
        <input name="homebase" value="<?=$competition['config']['homebase']?>" style="WIDTH: 100%;"><br>
        <br>
        Faktor f&uuml;r Fl&uuml;ge die nicht am Heimatplatz gestartet sind:<br>
        <input name="homefactor" value="<?=$competition['config']['homefactor']?>" style="WIDTH: 100%;"><br>
        <br>
        <hr noshade="noshade" color="#004422">
        <br>
        Piloten-Faktoren:<br>
        <? $factors = explode("|", $competition['config']['factors']); ?>
        <br>
        weniger als 50km:<br>
        <input name="factor1" value="<?=$factors[0]?>" style="WIDTH: 100%;"><br>
        <br>
        weniger als 100km:<br>
        <input name="factor2" value="<?=$factors[1]?>" style="WIDTH: 100%;"><br>
        <br>
        weniger als 300km:<br>
        <input name="factor3" value="<?=$factors[2]?>" style="WIDTH: 100%;"><br>
        <br>
        weniger als 500km:<br>
        <input name="factor4" value="<?=$factors[3]?>" style="WIDTH: 100%;"><br>
        <br>
        weniger als 700km:<br>
        <input name="factor5" value="<?=$factors[4]?>" style="WIDTH: 100%;"><br>
        <br>
        weniger als 1000km:<br>
        <input name="factor6" value="<?=$factors[5]?>" style="WIDTH: 100%;"><br>
        <br>
        mehr als 1000km:<br>
        <input name="factor7" value="<?=$factors[6]?>" style="WIDTH: 100%;"><br>
        <br>
        <hr noshade="noshade" color="#004422">
        <br>
        Passwort &auml;ndern:<br>
        <input type="password" name="pass1" value="<?=$competition['config']['password']?>" style="WIDTH: 100%;"><br>
        <br>
        Neues Passwort noch einmal eingeben:<br>
        <input type="password" name="pass2" value="<?=$competition['config']['password']?>" style="WIDTH: 100%;"><br>
        <br>
        <hr noshade="noshade" color="#004422">
        <br>
        <input type="submit" value="Config &auml;ndern" style="WIDTH: 100%;">
				<input type="hidden" name="action" value="config">
				<input type="hidden" name="action2" value="chg">
				</form>
				</td>
			</tr>
		</table>

		</td>
</tr>
</table>
</body>
</html>