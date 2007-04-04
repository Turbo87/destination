<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<html>
<head>
  <title>%comp_name%</title>
  <link href="templates/destination.css" type="text/css" rel="stylesheet">
  <script type="text/javascript">
  <!--
  parent.frames[0].toogleNav(5);
  //-->
  </script>
</head>

<body style="BACKGROUND: url('templates/top_shadow.png') repeat-x fixed;">
  <table width="600" align="center">
    <tbody>
      <tr>
        <td style="FONT-WEIGHT: bold; FONT-SIZE: 32px; PADDING-BOTTOM: 25px; PADDING-TOP: 25px" align="center" colspan="2">Details - %date%</td>
      </tr>

      <tr>
        <td style="BORDER: #004422 1px solid" valign="top" width="50%">
          <table style="FONT-SIZE: 12px" cellspacing="0" cellpadding="2" width="100%">
            <tbody>
              <tr>
                <td>Pilot:</td>
                <td>
                  <span class="ulink" onclick="location.href='index.php?c=%comp_id%&pilots&id=%pilot%';">%pilot%</span>
                </td>
              </tr>

%copilot%

              <tr>
                <td>Factor:</td>
                <td>%factor%</td>
              </tr>

              <tr>
                <td>Club:</td>
                <td>%club%</td>
              </tr>

              <tr>
                <td>Plane:</td>
                <td>%plane% / %plane_callsign%</td>
              </tr>

              <tr>
                <td>Index:</td>
                <td>%plane_index%</td>
              </tr>
            </tbody>
          </table>
        </td>

        <td style="BORDER: #004422 1px solid" valign="top" width="50%">
          <table style="FONT-SIZE: 12px" cellspacing="0" cellpadding="2" width="100%">
            <tbody>
              <tr>
                <td>Airfield:</td>
                <td>%airfield%</td>
              </tr>

              <tr>
                <td>km:</td>
                <td>%km% km</td>
              </tr>

              <tr>
                <td>Points:</td>
                <td>%points%</td>
              </tr>

              <tr>
                <td>OLC-Points:</td>
                <td>%olc%</td>
              </tr>

              <tr>
                <td>km/h:</td>
                <td>%speed% km/h</td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td style="FONT-SIZE: 10px; BORDER: #004422 1px solid; PADDING: 3px;" align="left" colspan="2">
        Source of the original data: <a href="http://www.onlinecontest.org/" target="_blank">OLC Online Contest</a>
        </td>
      </tr>
      <tr>
        <td style="BORDER: #004422 1px solid; PADDING: 10px;" align="center" colspan="2">
        Sorry! The OLC Guidelines for external usage of their data don't allow the display of maps anymore...<br><br>You can get to the Flight by clicking <a href="%olc_flight_link%" target="_blank">here</a>.
          <!--%images%//-->
        </td>
      </tr>

    </tbody>
  </table>
%updating%
</body>
</html>