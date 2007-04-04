<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  
<html>
<head>
  <title>%comp_name%</title>
  <link href="templates/destination.css" type="text/css" rel="stylesheet">
  <script type="text/javascript">
  <!--
  parent.frames[0].toogleNav(0);
  
  function show_info() {
    var link = document.getElementById("infolink");
    if (link) link.style.display = "none"; 
    var text = document.getElementById("infotext");
    if (text) text.style.display = ""; 
  }
  
  function hide_info() {
    var link = document.getElementById("infolink");
    if (link) link.style.display = ""; 
    var text = document.getElementById("infotext");
    if (text) text.style.display = "none"; 
  }
  
  hide_info();
  //-->
  </script>
</head>

<body style="BACKGROUND: url('templates/top_shadow.png') repeat-x fixed;">
  <table width="780" align="center">
    <tbody>
      <tr>
        <td style="FONT-WEIGHT: bold; FONT-SIZE: 32px; PADDING-BOTTOM: 10px; PADDING-TOP: 25px" align="center" colspan="2">News &amp; Infos</td>
      </tr>

      <tr>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
      </tr>

      <tr>
        <td valign="top" width="75%" height="1">
          <table style="BORDER: #004422 1px solid;" cellspacing="0" cellpadding="4" width="100%">
            <tbody>
              <tr style="FONT-SIZE: 14px; BACKGROUND: #004422; COLOR: white">
                <td><b>Infos zu %comp_name%
              <tr>
                <td>
                  %comp_name% is a cross-country soaring competition based 
                  on the data of the OLC servers, but only for the clubs of
                  the "%homebase%" airfield
                  <a href="javascript:show_info();" id="infolink">more...</a>
                  <span id="infotext" style="DISPLAY: none;"><br><br>The goal of the
                  competition is to motivate. This goal is reached by having a pilotfactor
                  taken in account, that gives a fair competition between fresh pilots
                  and the experts.<br/>
                  <br/>
                  The competition updates itself about every 30 minutes und scores
                  the detected flights by our own rules. The rule for calculating
                  the points is:

                  <blockquote>
                    <pre>
             (km * pilotfactor * airfieldfaktor)
points  =  ---------------------------------------
                        planefactor

planefactor = (index / 100)<sup>2</sup>
airfieldfaktor = %homefactor% (%homebase%)
                 %awayfactor% (other airfields)
</pre>
                  </blockquote>
                  The pilotfactor depends on the greatest flown distance of
                  each pilot. When flying in two-seat airplanes the pilotfactor
                  of the more experienced pilot is taken.

                  <blockquote>
<pre>Greatest distance &lt; 50km:   factor %factor_1%
Greatest distance &lt; 100km:  factor %factor_2%
Greatest distance &lt; 300km:  factor %factor_3%
Greatest distance &lt; 500km:  factor %factor_4%
Greatest distance &lt; 700km:  factor %factor_5%
Greatest distance &lt; 1000km: factor %factor_6%
Greatest distance &gt; 1000km: factor %factor_7%</pre>
                  </blockquote>
                  The current pilotfactor can be watched at "Pilots" in the
                  main menu. In order of a fair competition we ask all pilots
                  to tell us about wrong factors.
                  <a href="javascript:hide_info();">less...</a>
                  </span><br><br>
                  More questions: <a href="mailto:Tobias.Bieniek@gmx.de">Tobias.Bieniek@gmx.de</a>
                </td>
              </tr>
            </tbody>
          </table>
        </td>

        <td valign="top" width="25%" rowspan="2">
          %small_rankings%
        </td>
      </tr>

      <tr>
        <td valign="top" width="75%">
          <table style="BORDER: #004422 1px solid;" cellspacing="0" cellpadding="4" width="100%">
            <tbody>
              <tr style="FONT-SIZE: 14px; BACKGROUND: #004422; COLOR: white">
                <td><b>News
              <tr>
                <td style="FONT-SIZE: 14px;">
                  %news%<br>%news_next%%news_prev%
                </td>
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