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
  parent.frames[0].toogleNav(0);
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
				Generelles:<br>
				Einstellen der globalen Einstellungen, wie z.B. Name des Wettbewerbs und Faktoren<br>
				<a href="index.php?action=config">hier klicken</a><br><br>
        News:<br>
        Hinzuf&uuml;gen, Editieren und L&ouml;schen von Nachrichten<br>
        <a href="index.php?action=news">hier klicken</a><br><br>
        Fl&uuml;ge:<br>
        Sperren von Fl&uuml;gen (in Arbeit)<br>
        <a href="index.php?action=flights">hier klicken</a><br><br>
        Piloten:<br>
				&Auml;ndern von Pilotennamen und Zusammenfassen von Piloten<br>
				<a href="index.php?action=pilots">hier klicken</a>
				</td>
			</tr>
		</table>

		</td>
</tr>
</table>
</body>
</html>