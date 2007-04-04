<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<html>
<head>
  <title>%comp_name%</title>
  <link href="templates/destination.css" type="text/css" rel="stylesheet">
  <script type="text/javascript">
  <!--
  parent.frames[0].toogleNav(3);
  //-->
  </script>
</head>

<body style="BACKGROUND: url('templates/top_shadow.png') repeat-x fixed;">
  <table width="780" align="center">
    <tbody>
      <tr>
        <td style="FONT-WEIGHT: bold; FONT-SIZE: 32px; PADDING-BOTTOM: 25px; PADDING-TOP: 25px" align="center">Piloten</td>
      </tr>

      <tr>
        <td valign="top">
      <TABLE width="100%" style="BORDER: solid 1px #004422;" cellspacing="0" cellpadding="2">
        <TR style="FONT-SIZE: 16px; COLOR: white; BACKGROUND: #004422; FONT-WEIGHT: bold;">
          <TD style="PADDING: 5px;" colspan="2">%id%</TD>
        </TR>
        <TR>
          <TD width="25%" style="FONT-SIZE: 2px;">
            <TABLE width="100%" style="BORDER: solid 1px #004422; FONT-SIZE: 12px;" cellspacing="0" cellpadding="2">
              <TR style="FONT-SIZE: 10px; COLOR: white; BACKGROUND: #004422;">
                <TD colspan="2">Competition goals:</TD>
              </TR>
              <TR>
                <TD>50km</TD>
                <TD align="right">%milestone1%</TD>
              </TR>
              <TR>
                <TD>100km</TD>
                <TD align="right">%milestone2%</TD>
              </TR>
              <TR>
                <TD>300km</TD>
                <TD align="right">%milestone3%</TD>
              </TR>
              <TR>
                <TD>500km</TD>
                <TD align="right">%milestone4%</TD>
              </TR>
              <TR>
                <TD>700km</TD>
                <TD align="right">%milestone5%</TD>
              </TR>
              <TR>
                <TD>1000km</TD>
                <TD align="right">%milestone6%</TD>
              </TR>
            </TABLE>
            <BR />

            <TABLE width="100%" style="BORDER: solid 1px #004422; FONT-SIZE: 12px;" cellspacing="0" cellpadding="2">
              <TR style="FONT-SIZE: 10px; COLOR: white; BACKGROUND: #004422;">
                <TD colspan="2">Statistics:</TD>
              </TR>
              <TR>
                <TD style="PADDING-TOP: 6px; PADDING-BOTTOM: 6px; BORDER-BOTTOM: solid 1px #004422;">Flights:</TD>
                <TD align="right" style="PADDING-TOP: 6px; PADDING-BOTTOM: 6px; BORDER-BOTTOM: solid 1px #004422;">%total%</TD>

              </TR>
              <TR>
                <TD style="PADDING-TOP: 6px;">km:</TD>
                <TD align="right" style="PADDING-TOP: 6px;">%km%</TD>
              </TR>
              <TR>
                <TD style="PADDING-BOTTOM: 6px; BORDER-BOTTOM: solid 1px #004422;">km/Flight:</TD>

                <TD align="right" style="PADDING-BOTTOM: 6px; BORDER-BOTTOM: solid 1px #004422;">%km_avg%</TD>
              </TR>
              <TR>
                <TD style="PADDING-TOP: 6px;">Points:</TD>
                <TD align="right" style="PADDING-TOP: 6px;">%points%</TD>
              </TR>
              <TR>

                <TD style="PADDING-BOTTOM: 6px; BORDER-BOTTOM: solid 1px #004422;">Points/Flight:</TD>
                <TD align="right" style="PADDING-BOTTOM: 6px; BORDER-BOTTOM: solid 1px #004422;">%points_avg%</TD>
              </TR>
              <TR>
                <TD style="PADDING-TOP: 6px; PADDING-BOTTOM: 6px;">OLC:</TD>
                <TD align="right" style="PADDING-TOP: 6px; PADDING-BOTTOM: 6px; BORDER-BOTTOM: solid 1px #004422;">%olc%</TD>
              </TR>
              <TR>
                <TD align="right" style="PADDING-TOP: 6px; PADDING-BOTTOM: 6px;" colspan="2"><a href="index.php?c=%comp_id%&stats&arg1=3&arg2=%idx%">zur Hauptstatistik</a></TD>
              </TR>
  </TABLE>
            <BR />
            <TABLE width="100%" style="BORDER: solid 1px #004422; FONT-SIZE: 12px;" cellspacing="0" cellpadding="2">
              <TR style="FONT-SIZE: 10px; COLOR: white; BACKGROUND: #004422;">
                <TD colspan="2">Ranks:</TD>
              </TR>
%ranks%              
            </TABLE>

          </TD>
          <TD width="75%">
            <TABLE width="100%" style="MARGIN-BOTTOM: 4px;" cellspacing="0" cellpadding="2">
              <TR style="FONT-SIZE: 10px;">
                <TD>Source of the original data: <a href="http://www.onlinecontest.org/" target="_blank">OLC Online Contest</a></TD>
              </TR>            
            </TABLE>
            <TABLE width="100%" style="BORDER: solid 1px #004422;" cellspacing="0" cellpadding="2">
              <TR style="FONT-SIZE: 10px; COLOR: white; BACKGROUND: #004422;">
                <TD>Date</TD>
                <TD>Factor</TD>
                <TD>Plane</TD>
                <TD>Points</TD>
                <TD>km</TD>
                <TD>Airfield</TD>
                <TD></TD>
              </TR>
%flights%               
            </TABLE>
        </td>
      </tr>
    </tbody>
  </table>
%updating%
</body>
</html>