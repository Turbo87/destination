<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<html>
<head>
  <title>%comp_name%</title>
  <link href="templates/destination.css" type="text/css" rel="stylesheet">
  <script type="text/javascript">
  <!--
  parent.frames[0].toogleNav(1);
  //-->
  </script>
</head>

<body style="BACKGROUND: url('templates/top_shadow.png') repeat-x fixed;">
  <table width="780" align="center">
    <tbody>
      <tr>
        <td style="FONT-WEIGHT: bold; FONT-SIZE: 32px; PADDING-BOTTOM: 10px; PADDING-TOP: 25px" align="center" colspan="2">Ranking - %year%</td>
      </tr>

      <tr>
        <td>%link-%</td>
        <td align="right">%link+%</td>
      </tr>

      <tr>
        <td valign="top" width="75%">
          <table style="BORDER: #004422 1px solid" cellspacing="0" cellpadding="2" width="100%">
            <tbody>
              <tr style="FONT-SIZE: 10px; BACKGROUND: #004422; COLOR: white">
                <td align="right">#</td>
                <td>Name</td>
                <td colspan="2">Gesamt</td>
                <td>Flug 1</td>
                <td>Flug 2</td>
                <td>Flug 3</td>
              </tr>

              %rows%

              <tr style="FONT-SIZE: 10px; BACKGROUND: #004422; COLOR: white">
                <td colspan="3">%link-%</td>
                <td align="right" colspan="4">%link+%</td>
              </tr>
              
              <tr style="FONT-SIZE: 10px; ">
                <td colspan="7">Quelle der Original-Wertungsdaten: <a href="http://www.onlinecontest.org/" target="_blank">OLC Online Contest</a></td>
              </tr>
            </tbody>
          </table>
        </td>

        <td valign="top" width="25%">
          %small_rankings%
        </td>
      </tr>
    </tbody>
  </table>
%updating%
</body>
</html>