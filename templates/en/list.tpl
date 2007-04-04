<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<html>
<head>
  <title>%comp_name%</title>
  <link href="templates/destination.css" type="text/css" rel="stylesheet">
  <script type="text/javascript">
  <!--
  parent.frames[0].toogleNav(2);
  //-->
  </script>
</head>

<body style="BACKGROUND: url('templates/top_shadow.png') repeat-x fixed;">
  <table width="780" align="center">
    <tbody>
      <tr>
        <td style="FONT-WEIGHT: bold; FONT-SIZE: 32px; PADDING-BOTTOM: 10px; PADDING-TOP: 25px" align="center" colspan="2">Flightlist - %year%</td>
      </tr>

      <tr>
        <td>%link-%</td>
        <td align="right">%link+%</td>
      </tr>

      <tr>
        <td valign="top" colspan="2">
          <table style="BORDER: #004422 1px solid" cellspacing="0" cellpadding="3" width="100%">
            <tbody>
              <tr style="FONT-SIZE: 10px; BACKGROUND: #004422; COLOR: white">
                <td><span class="link" onclick="location.href='index.php?c=%comp_id%&list&year=%year%&sort=date%20DESC';">Date</span></td>
                <td><span class="link" onclick="location.href='index.php?c=%comp_id%&list&year=%year%&sort=pilot';">Name</span></td>
                <td><span class="link" onclick="location.href='index.php?c=%comp_id%&list&year=%year%&sort=factor';">Factor</span></td>
                <td><span class="link" onclick="location.href='index.php?c=%comp_id%&list&year=%year%&sort=plane';">Plane</span></td>
                <td><span class="link" onclick="location.href='index.php?c=%comp_id%&list&year=%year%&sort=points%20DESC';">Points</span></td>
                <!--<td><span class="link" onclick="location.href='index.php?c=%comp_id%&list&year=%year%&sort=olc_points%20DESC';">OLC</span></td>//-->
                <td><span class="link" onclick="location.href='index.php?c=%comp_id%&list&year=%year%&sort=km%20DESC';">km</span></td>
                <td><span class="link" onclick="location.href='index.php?c=%comp_id%&list&year=%year%&sort=airfield';">Airfield</span></td>
                <td></td>
              </tr>

              %rows%

              <tr style="FONT-SIZE: 10px; BACKGROUND: #004422; COLOR: white">
                <td colspan="4">%link-%</td>
                <td align="right" colspan="4">%link+%</td>
              </tr>
              <tr style="FONT-SIZE: 10px;">
                <td colspan="9">Source of the original data: <a href="http://www.onlinecontest.org/" target="_blank">OLC Online Contest</a></td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
%updating%
</body>
</html>