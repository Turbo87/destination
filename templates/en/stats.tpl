<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<html>
<head>
  <title>%comp_name%</title>
  <link href="templates/destination.css" type="text/css" rel="stylesheet">
  <script type="text/javascript">
  <!--
  parent.frames[0].toogleNav(4);
  //-->
  </script>  
  <script>
  function focus(objID) {
    var row = document.getElementById(objID);
    if (row == null) return;
    row.className = "active_menu_item";
  }
  function hover(obj) {
    if (obj.className == "active_menu_item") return;
    obj.style.backgroundColor = "#E8FFE8";
  }
  function unhover(obj) {
    if (obj.className == "active_menu_item") return;
    obj.style.backgroundColor = "#FFFFFF";
  }
  function gotoSite(arg1, arg2, arg3) {
    location.href = "index.php?c=%comp_id%&stats&arg1="+arg1+"&arg2="+arg2+"&arg3="+arg3;
  }
  </script>
  <style>
  .active_menu_item {
    BACKGROUND: #004422;
    COLOR: white; 
  }
  </style>
</head>

<body style="SCROLLBAR: v-scroll; BACKGROUND: url('templates/top_shadow.png') repeat-x fixed;">
  <table width="600" align="center">
    <tbody>
      <tr>
        <td style="FONT-WEIGHT: bold; FONT-SIZE: 32px; PADDING-BOTTOM: 10px; PADDING-TOP: 25px" align="center" colspan="2">Statistics</td>
      </tr>

      <tr>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
      </tr>
      
      <tr>
        <td valign="top" width="25%">
          <table style="BORDER: #004422 1px solid; FONT-SIZE: 12px;" cellspacing="0" cellpadding="5" width="90%">
            <tbody>
              <tr id="tr_overview" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('1', '0', '0');">
                <td>Overview</td>
              </tr>
              <tr id="tr_planes" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('2', '0', '0');">
                <td>Planes</td>
              </tr>
              <tr id="tr_pilots" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('3', '0', '0');">
                <td>Pilots</td>
              </tr>
            </tbody>
          </table>
          <br />
          <span id="submenu_overview" style="%submenu_overview_style%">
          <table style="BORDER: #004422 1px solid; FONT-SIZE: 12px;" cellspacing="0" cellpadding="5" width="90%">
            <tbody>
              <tr>
                <td id="tr_overview_distance" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('1', '0', 'distance');">Distance</td>
              </tr>
              <tr>
                <td id="tr_overview_flights" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('1', '0', 'flights');">Flights</td>
              </tr>
              <tr>
                <td id="tr_overview_time" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('1', '0', 'time');">Flighttime</td>
              </tr>
              <tr>
                <td id="tr_overview_pilots" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('1', '0', 'pilots');">Pilots</td>
              </tr>
            </tbody>
          </table>
          </span>
          <span id="dropdown_planes" style="%dropdown_planes_style%">
          <select size="1" onchange="if (this.value != '-1') gotoSite('2', this.value, '%arg3%');" style="BORDER: #004422 1px solid; BACKGROUND: #004422; COLOR: white; FONT-SIZE: 12px; WIDTH: 90%; HEIGHT: 20px; VERTICAL-ALIGN: middle;">
            <option value="-1">Please choosen:
            <option disabled>-------------
            <option value="compare"%planes_compare_selected%>Compare
            <option disabled>-------------
            %planes%
          </select>
          <br /><br />
          </span>
          <span id="submenu_planes" style="%submenu_planes_style%">
          <table style="BORDER: #004422 1px solid; FONT-SIZE: 12px;" cellspacing="0" cellpadding="5" width="90%">
            <tbody>
              <tr>
                <td id="tr_planes_overview" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('2', '%arg2%', 'overview');">Overview</td>
              </tr>
              <tr>
                <td id="tr_planes_distance" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('2', '%arg2%', 'distance');">Distance</td>
              </tr>
              <tr>
                <td id="tr_planes_flights" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('2', '%arg2%', 'flights');">Flights</td>
              </tr>
              <tr>
                <td id="tr_planes_time" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('2', '%arg2%', 'time');">Flighttime</td>
              </tr>
              <tr>
                <td id="tr_planes_pilots" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('2', '%arg2%', 'pilots');">Pilots</td>
              </tr>
            </tbody>
          </table>
          </span>
          <span id="submenu_planes_compare" style="%submenu_planes_compare_style%">
          <table style="BORDER: #004422 1px solid; FONT-SIZE: 12px;" cellspacing="0" cellpadding="5" width="90%">
            <tbody>
              <tr>
                <td id="tr_planes_compare_distance" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('2', '%arg2%', 'distance');">Distance</td>
              </tr>
              <tr>
                <td id="tr_planes_compare_flights" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('2', '%arg2%', 'flights');">Flights</td>
              </tr>
              <tr>
                <td id="tr_planes_compare_time" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('2', '%arg2%', 'time');">Flighttime</td>
              </tr>
            </tbody>
          </table>
          </span>
          <span id="dropdown_pilots" style="%dropdown_pilots_style%">
          <select size="1" onchange="if (this.value != '-1') gotoSite('3', this.value, '%arg3%');" style="BORDER: #004422 1px solid; BACKGROUND: #004422; COLOR: white; FONT-SIZE: 12px; WIDTH: 90%; HEIGHT: 20px; VERTICAL-ALIGN: middle;">
            <option value="-1">Please choose:
            <option disabled>-------------
            <option value="compare"%pilots_compare_selected%>Compare
            <option disabled>-------------
            %pilots%
          </select>
          <br /><br />
          </span>
          <span id="submenu_pilots" style="%submenu_pilots_style%">
          <table style="BORDER: #004422 1px solid; FONT-SIZE: 12px;" cellspacing="0" cellpadding="5" width="90%">
            <tbody>
              <tr>
                <td id="tr_pilots_overview" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('3', '%arg2%', 'overview');">Overview</td>
              </tr>
              <tr>
                <td id="tr_pilots_distance" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('3', '%arg2%', 'distance');">Distance</td>
              </tr>
              <tr>
                <td id="tr_pilots_flights" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('3', '%arg2%', 'flights');">Flights</td>
              </tr>
              <tr>
                <td id="tr_pilots_time" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('3', '%arg2%', 'time');">Flighttime</td>
              </tr>
              <tr>
                <td id="tr_pilots_planes" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('3', '%arg2%', 'planes');">Planes</td>
              </tr>
              <tr>
                <td id="tr_pilots_flightlog" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="location.href='index.php?c=%comp_id%&pilots&id=%arg2x%';">Flightlog</td>
              </tr>
            </tbody>
          </table>
          </span>
          <span id="submenu_pilots_compare" style="%submenu_pilots_compare_style%">
          <table style="BORDER: #004422 1px solid; FONT-SIZE: 12px;" cellspacing="0" cellpadding="5" width="90%">
            <tbody>
              <tr>
                <td id="tr_pilots_compare_distance" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('3', '%arg2%', 'distance');">Distance</td>
              </tr>
              <tr>
                <td id="tr_pilots_compare_flights" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('3', '%arg2%', 'flights');">Flights</td>
              </tr>
              <tr>
                <td id="tr_pilots_compare_time" style="CURSOR: pointer;" onmouseover="hover(this);" onmouseout="unhover(this);" onclick="gotoSite('3', '%arg2%', 'time');">Flighttime</td>
              </tr>
            </tbody>
          </table>
          </span>
        </td>
        <td valign="top" width="75%">%content%
        </td>
      </tr>
    </tbody>
  </table>
  <script>%script%</script>
  %updating%
</body>
</html>