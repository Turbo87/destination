<html>
<head>
  <title>%comp_name%</title>
  <link href="templates/destination.css" type="text/css" rel="stylesheet">
  <script type="text/javascript">
  <!--
  function toogleNav (id) {
    var row = document.getElementById("navrow");
    if (row == null) return;
    var j = 0;
    id++;
    for (var i = 0; i < row.childNodes.length; i++) {
      if (row.childNodes[i].nodeName == "TD") {
        if (j == id) {
          //row.childNodes[i].style.background = "#FFFFFF";
          row.childNodes[i].style.background = "url('templates/top_active.png')";
          row.childNodes[i].style.color = "#004422";
        } else {
          //row.childNodes[i].style.background = "#004422";
          row.childNodes[i].style.background = "url('templates/top_inactive.png')";
          row.childNodes[i].style.color = "#FFFFFF";
        }
        j++;
      }
    }
  }
  //-->
  </script>
  <style>
  .link {
    FONT-WEIGHT: 600;
  }
  </style>
</head>

<body style="BACKGROUND: url('templates/bg.png'); BACKGROUND-COLOR: #118822;">
<table width="100%" height="100%" cellpadding="0" cellspacing="0">
<tr>
<td>
<a href="http://www.onlinecontest.org" target="_blank"><img src="templates/olc_logo_powered_green_small.png" border="0"></a>
</td>
<td>
  <table cellspacing="0" cellpadding="5" align="left" style="HEIGHT: 100px; OVERFLOW: hidden;">
    <tbody>
      <tr style="HEIGHT: 72px; OVERFLOW: hidden;">
        <td style="VERTICAL-ALIGN: middle" align="center" colspan="8">
          <span style="FONT-WEIGHT: bold; FONT-SIZE: 38px; COLOR: white">%comp_name%</span>
        </td>
      </tr>

      <tr id="navrow" style="HEIGHT: 28px; OVERFLOW: hidden;">
        <td style="PADDING-RIGHT: 3px; PADDING-LEFT: 3px; FONT-SIZE: 1px; PADDING-BOTTOM: 3px; PADDING-TOP: 3px">&nbsp;</td>
        <td class="link" style="WIDTH: 120px; PADDING-LEFT: 6px;" onclick="parent.content.location.href='index.php?c=%comp_id%&home';">Home</td>
        <td class="link" style="WIDTH: 120px; PADDING-LEFT: 6px;" onclick="parent.content.location.href='index.php?c=%comp_id%&ranking';">Ranking</td>
        <td class="link" style="WIDTH: 120px; PADDING-LEFT: 6px;" onclick="parent.content.location.href='index.php?c=%comp_id%&list';">Flightlist</td>
        <td class="link" style="WIDTH: 120px; PADDING-LEFT: 6px;" onclick="parent.content.location.href='index.php?c=%comp_id%&pilots';">Pilots</td>
        <td class="link" style="WIDTH: 120px; PADDING-LEFT: 6px;" onclick="parent.content.location.href='index.php?c=%comp_id%&stats';">Statistics</td>
        <td class="link" style="WIDTH: 120px; PADDING-LEFT: 6px; CURSOR: default;">Details</td>
        <td style="PADDING-RIGHT: 3px; PADDING-LEFT: 3px; FONT-SIZE: 1px; PADDING-BOTTOM: 3px; PADDING-TOP: 3px">&nbsp;</td>
      </tr>
    </tbody>
  </table>
</td>
</tr>
</table>
  <script type="text/javascript">
  <!--
  toogleNav(0);
  //-->
  </script>
</body>
</html>