<?
$page = 1;
if (array_key_exists("page", $_GET)) $page = $_GET['page'];

$action2 = "show";
if (array_key_exists("action2", $_GET)) $action2 = $_GET['action2'];
if (array_key_exists("action2", $_POST)) $action2 = $_POST['action2'];

if ($action2 == "show") {
  $i = 0;
  $s_news = "";
  $next = 0;
  $prev = 0;
  $res_flights = db_query("SELECT * FROM %pre%flights ORDER BY date DESC, points DESC, km DESC", $db, $competition['id']);
  while($flights = db_fetch_array($res_flights)) {
    if ($i >= ($page - 1) * 50 && $i < $page * 50) {
      $s_flights .= "<tr class='list_row_".($flights['status'] == "blocked" ? "x" : ($i % 2 == 1 ? "o" : "e"))."'>
            <td>".date("d.m.y", $flights['date'])."</td>
            <td>".$flights['pilot'].(trim($flights['copilot']) != "" ? "<br>".$flights['copilot'] : "")."</td>
            <td>".$flights['km']."</td>
            <td>".$flights['points']."</td>
            <td width='1' nowrap><a href='index.php?action=flights&action2=del&id=".$flights['id']."'><img src='delete.png' alt='L&ouml;schen' border='0'></a> <a href='index.php?action=flights&action2=delpics&id=".$flights['id']."'><img src='picture_delete.png' alt='Bilder l&ouml;schen' border='0'></a> <a href='index.php?action=flights&action2=".($flights['status'] == "blocked" ? "un" : "")."block&id=".$flights['id']."'><img src='lock".($flights['status'] == "blocked" ? "_open" : "").".png' alt='".($flights['status'] == "blocked" ? "Ents" : "S")."perren' border='0'></a></td>
          </tr>
      ";
    }
    if ($i >= $page * 50) {
      $next = 1;
    }
    if ($i < ($page - 1) * 50) {
      $prev = 1;
    }
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
  parent.frames[0].toogleNav(3);
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
        <td colspan="2"><?=($prev ? "<a href='index.php?action=flights&action2=show&page=".($page-1)."'>&lt; Zur&uuml;ck</a>" : "")?></td>
        <td colspan="3" align="right"><?=($next ? "<a href='index.php?action=flights&action2=show&page=".($page+1)."'>Weiter &gt;</a>" : "")?></td>
        </tr>
        <tr>
        <td>Datum:</td>
        <td>Pilot(en):</td>
        <td>km:</td>
        <td>Punkte:</td>
        <td></td>
        </tr>
        <?=$s_flights?>
        <tr>
        <td colspan="2"><?=($prev ? "<a href='index.php?action=flights&action2=show&page=".($page-1)."'>&lt; Zur&uuml;ck</a>" : "")?></td>
        <td colspan="3" align="right"><?=($next ? "<a href='index.php?action=flights&action2=show&page=".($page+1)."'>Weiter &gt;</a>" : "")?></td>
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
} elseif ($action2 == "del") {
  if (!array_key_exists("id", $_POST))
  header("LOCATION: index.php?action=flights");
  $id = $_GET["id"];

  db_query("DELETE FROM %pre%flights WHERE id = '".$id."'", $db, $competition['id']);
  header("LOCATION: index.php?action=flights");
} elseif ($action2 == "block") {
  if (!array_key_exists("id", $_POST))
  header("LOCATION: index.php?action=flights");
  $id = $_GET["id"];

  db_query("UPDATE %pre%flights SET status = 'blocked' WHERE id = '".$id."'", $db, $competition['id']);
  header("LOCATION: index.php?action=flights");
} elseif ($action2 == "unblock") {
  if (!array_key_exists("id", $_POST))
  header("LOCATION: index.php?action=flights");
  $id = $_GET["id"];

  db_query("UPDATE %pre%flights SET status = 'ready' WHERE id = '".$id."'", $db, $competition['id']);
  header("LOCATION: index.php?action=flights");
} elseif ($action2 == "delpics") {
  if (!array_key_exists("id", $_POST))
  header("LOCATION: index.php?action=flights");
  $id = $_GET["id"];

  if (file_exists("../images/".$competition['id']."/".$id.".jpg")) 
    unlink("../images/".$competition['id']."/".$id.".jpg");
  if (file_exists("../images/".$competition['id']."/".$id.".gif")) 
    unlink("../images/".$competition['id']."/".$id.".gif");
  if (file_exists("../images/".$competition['id']."/".$id.".png")) 
    unlink("../images/".$competition['id']."/".$id.".png");

  header("LOCATION: index.php?action=flights");
}
?>