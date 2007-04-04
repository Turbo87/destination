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
  if (parent.frames[0]) {
    parent.location.href="index.php";
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
				<td style="PADDING: 10px; FONT-SIZE: 20px; " align="center">Destination Adminbereich</td>
			</tr>
			<tr>
				<td style="PADDING: 5px;">
				<form method="POST" action="index.php">Username:<br>
				<input name="desti_admin_user" style="WIDTH: 100%;"><br><br>
				Passwort:<br>
				<input type="password" name="desti_admin_pass" style="WIDTH: 100%;"><br><br>
				<input type="submit" value="Einloggen" style="WIDTH: 100%;">
				<input type="hidden" name="action" value="login">
				</form>
				</td>
			</tr>
		</table>

		</td>
</tr>
</table>
</body>
</html>