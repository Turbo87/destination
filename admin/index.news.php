<?php
// News-System hinzufügen
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
  $res_news = db_query("SELECT * FROM %pre%news ORDER BY time DESC, id DESC", $db, $competition['id']);
  while($news = db_fetch_array($res_news)) {
    if ($i >= ($page - 1) * 5 && $i < $page * 5) {
      $s_news .= "          <table cellpadding='4' width='100%'>
          <tr>
            <td>ID: ".$news['id']."</td>
            <td>".date("d.m.y - H:i", $news['time'])." von ".$news['author']."</td>
          </tr>
          <tr>
            <td colspan='2'>".$news['header']."</td>
          </tr>
          <tr>
            <td colspan='2'>".$news['text']."</td>
          </tr>
          <tr>
            <td colspan='2'><a href='index.php?action=news&action2=del&id=".$news['id']."'>L&ouml;schen</a>&nbsp;|&nbsp;<a href='index.php?action=news&action2=edit&id=".$news['id']."'>Editieren</a></td>
          </tr>
        </table>
        <hr noshade='noshade' color='#004422'>
      ";
    }
    if ($i >= $page * 5) {
      $next = 1;
    }
    if ($i < ($page - 1) * 5) {
      $prev = 1;
    }
    $i++;
  }
  if ($i == 0) {
    $s_news = "          <table cellpadding='4' width='100%'>
          <tr>
            <td align='center'>Keine News vorhanden!</td>
          </tr>
        </table>
";  }

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
  parent.frames[0].toogleNav(2);
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
<?=($prev ? "<a href='index.php?action=news&action2=show&page=".($page-1)."'>&lt; Zur&uuml;ck</a>&nbsp;|&nbsp;" : "")?><a href="index.php?action=news&action2=add">Neue Nachricht eintragen</a><?=($next ? "&nbsp;|&nbsp;<a href='index.php?action=news&action2=show&page=".($page+1)."'>Weiter &gt;</a>" : "")?>
<hr noshade='noshade' color='#004422'>
<?=$s_news?>
<?=($prev ? "<a href='index.php?action=news&action2=show&page=".($page-1)."'>&lt; Zur&uuml;ck</a>&nbsp;|&nbsp;" : "")?><a href="index.php?action=news&action2=add">Neue Nachricht eintragen</a><?=($next ? "&nbsp;|&nbsp;<a href='index.php?action=news&action2=show&page=".($page+1)."'>Weiter &gt;</a>" : "")?>
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
  header("LOCATION: index.php?action=news");
  $id = $_GET["id"];

  $res_news = db_query("SELECT * FROM %pre%news WHERE id = $id", $db, $competition['id']);
  if (!($news = db_fetch_array($res_news)))
  header("LOCATION: index.php?action=news");

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

INPUT, TEXTAREA {
  BORDER: solid 1px #004422;
  BACKGROUND: #E8FFE8;
}
</style>
<script type="text/javascript">
  <!--
  parent.frames[0].toogleNav(2);
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
        <form action="index.php" method="POST"><input type="hidden" name="action" value="news"> <input type="hidden" name="action2" value="edit2"> <input type="hidden" name="id" value="<?=$news['id']?>">
        <table cellpadding='4' width='100%'>
          <tr>
            <td>ID:</td>
            <td><?=$news['id']?></td>
          </tr>
          <tr>
            <td>Datum/Zeit:</td>
            <td><?=date("d.m.y - H:i", $news['time'])?></td>
          </tr>
          <tr>
            <td>Autor:</td>
            <td><input name="author" value="<?=$news['author']?>" style="WIDTH: 100%;"></td>
          </tr>
          <tr>
            <td>&Uuml;berschrift:</td>
            <td><input name="header" value="<?=$news['header']?>" style="WIDTH: 100%;"></td>
          </tr>
          <tr>
            <td colspan="2"><textarea name="text" style="WIDTH: 100%; HEIGHT: 150px;"><?=$news['text']?></textarea></td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit" value="Editieren">&nbsp;<input type="button" value="Abbrechen" onclick="history.back();"></td>
          </tr>
        </table>
      </form>
      </td>
      </tr>
    </table>

    </td>
  </tr>
</table>
</body>
</html>
  <?
} elseif ($action2 == "edit2") {
  if (!array_key_exists("id", $_POST))
  header("LOCATION: index.php?action=news");
  $id = $_POST["id"];

  if (!array_key_exists("author", $_POST) || !array_key_exists("header", $_POST) || !array_key_exists("text", $_POST))
  header("LOCATION: index.php?action=news");

  db_query("UPDATE %pre%news SET author = '".$_POST['author']."', header = '".$_POST['header']."', text = '".$_POST['text']."' WHERE id = ".$id, $db, $competition['id']);
  header("LOCATION: index.php?action=news");
} elseif ($action2 == "add") {
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

INPUT, TEXTAREA {
  BORDER: solid 1px #004422;
  BACKGROUND: #E8FFE8;
}
</style>
<script type="text/javascript">
  <!--
  parent.frames[0].toogleNav(2);
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
        <form action="index.php" method="POST">
        <input type="hidden" name="action" value="news">
        <input type="hidden" name="action2" value="add2">
        <table cellpadding='4' width='100%'>
          <tr>
            <td>Autor:</td>
            <td><input name="author" value="<?=$competition['name']?>" style="WIDTH: 100%;"></td>
          </tr>
          <tr>
            <td>&Uuml;berschrift:</td>
            <td><input name="header" value="" style="WIDTH: 100%;"></td>
          </tr>
          <tr>
            <td colspan="2"><textarea name="text" style="WIDTH: 100%; HEIGHT: 150px;"></textarea></td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit" value="Hinzuf&uuml;gen">&nbsp;<input type="button" value="Abbrechen" onclick="history.back();"></td>
          </tr>
        </table>
        </form>
        </td>
      </tr>
    </table>

    </td>
  </tr>
</table>
</body>
</html>
  <?
} elseif ($action2 == "add2") {
  if (!array_key_exists("author", $_POST) || !array_key_exists("header", $_POST) || !array_key_exists("text", $_POST))
  header("LOCATION: index.php?action=news");

  $id = db_query_count("SELECT * FROM %pre%news", $db, $competition['id']) + 1;
  
  db_query("INSERT INTO %pre%news VALUES($id, ".time().", '".$_POST['author']."', '".$_POST['header']."', '".$_POST['text']."')", $db, $competition['id']);
  header("LOCATION: index.php?action=news");
} elseif ($action2 == "del") {
  if (!array_key_exists("id", $_GET))
  header("LOCATION: index.php?action=news");
  $id = $_GET["id"];

  db_query("DELETE FROM %pre%news WHERE id = ".$id, $db, $competition['id']);
  db_query("UPDATE %pre%news SET id = id - 1 WHERE id > ".$id, $db, $competition['id']);
  header("LOCATION: index.php?action=news");
}
?>