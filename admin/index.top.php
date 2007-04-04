<html>
<head>
<link href="admin.css" type="text/css" rel="stylesheet">
<title>Destination Adminbereich</title>
<script type="text/javascript">
  <!--
  function toogleNav (id) {
    var row = document.getElementById("navrow");
    var j = 0;
    id++;
    for (var i = 0; i < row.childNodes.length; i++) {
      if (row.childNodes[i].nodeName == "TD") {
        if (j == id) {
          row.childNodes[i].style.background = "#FFFFFF";
          row.childNodes[i].style.color = "#004422";
        } else {
          row.childNodes[i].style.background = "#004422";
          row.childNodes[i].style.color = "#FFFFFF";
        }
        j++;
      }
    }
  }
  //-->
  </script>
</head>
<body	style="BACKGROUND: url(bg_top.jpg); BACKGROUND-COLOR: #118822;">
<table height="100%" cellspacing="0" cellpadding="5" align="center">
	<tbody>
		<tr>
			<td style="VERTICAL-ALIGN: middle" align="center" colspan="7"><span
				style="FONT-WEIGHT: bold; FONT-SIZE: 38px; COLOR: white"><?=$competition['name']?></span>
			</td>
		</tr>

		<tr id="navrow" height="20">
      <td style="PADDING-RIGHT: 3px; PADDING-LEFT: 3px; FONT-SIZE: 1px; PADDING-BOTTOM: 3px; PADDING-TOP: 3px">&nbsp;</td>
      <td class="link" style="WIDTH: 125px" onclick="parent.content.location.href='index.php?action=home';">Home</td>
      <td class="link" style="WIDTH: 125px" onclick="parent.content.location.href='index.php?action=config';">Generelles</td>
      <td class="link" style="WIDTH: 125px" onclick="parent.content.location.href='index.php?action=news';">News</td>
      <td class="link" style="WIDTH: 125px" onclick="parent.content.location.href='index.php?action=flights';">Fl&uuml;ge</td>
      <td class="link" style="WIDTH: 125px" onclick="parent.content.location.href='index.php?action=pilots';">Piloten</td>
      <td class="link" style="WIDTH: 125px" onclick="parent.location.href='index.php?action=logout';">Logout</td>
      <td style="PADDING-RIGHT: 3px; PADDING-LEFT: 3px; FONT-SIZE: 1px; PADDING-BOTTOM: 3px; PADDING-TOP: 3px">&nbsp;</td>
    </tr>
	</tbody>
</table>
<script type="text/javascript">
  <!--
  toogleNav(0);
  //-->
  </script>
</body>
</html>