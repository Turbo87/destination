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
          <table style="BORDER: #004422 1px solid" cellspacing="0" cellpadding="2" width="100%">
            <tbody>
              <tr style="FONT-SIZE: 10px; BACKGROUND: #004422; COLOR: white">
                <td>Name</td>
                <td align="middle">50km</td>
                <td align="middle">100km</td>
                <td align="middle">300km</td>
                <td align="middle">500km</td>
                <td align="middle">700km</td>
                <td align="middle">1000km</td>
                <td></td>
              </tr>

              %rows%

            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
%updating%
</body>
</html>