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
                  %comp_name% ist ein dezentraler
                  Streckenflugwettbewerb basierend auf den Daten des OLC
                  Servers, jedoch ausschlie&szlig;lich konzipiert f&uuml;r die
                  am Flugplatz %homebase% ans&auml;ssigen Vereine.
                  <a href="javascript:show_info();" id="infolink">mehr...</a>
                  <span id="infotext" style="DISPLAY: none;"><br><br>Der Wettbewerb hat das
                  Ziel zum Streckenflug zu motivieren, indem auch die
                  j&uuml;ngeren und unerfahreneren Piloten eine Chance haben,
                  auf Grund eines Pilotenfaktors, der je nach Leistungsstand,
                  mit in die Flugdistanz eingerechnet wird.<br/>
                  <br/>
                  Die Seite aktualisiert sich etwa alle 30 Minuten automatisch
                  und wertet die Fl&uuml;ge entsprechend der Wettbewerbsregeln
                  neu aus. Die Punkte f&uuml;r einen Flug berechnen sich durch
                  folgende Formeln:

                  <blockquote>
                    <pre>
            (km * Pilotenfaktor * Flugplatzfaktor)
Punkte  =  ----------------------------------------
                       Flugzeugfaktor

Flugzeugfaktor = (DAeC-Index / 100)<sup>2</sup>
Flugplatzfaktor = %homefactor% (%homebase%)
                  %awayfactor% (Außerhalb)
</pre>
                  </blockquote>Der Pilotenfaktor ist abh&auml;ngig von der
                  bisher gr&ouml;&szlig;ten geflogenen Strecke des jeweiligen
                  Piloten. Wird doppelsitzig geflogen z&auml;hlt der Faktor des
                  besseren Piloten.

                  <blockquote>
<pre>Gr&ouml;&szlig;te Strecke &lt; 50km:   Faktor %factor_1%
Gr&ouml;&szlig;te Strecke &lt; 100km:  Faktor %factor_2%
Gr&ouml;&szlig;te Strecke &lt; 300km:  Faktor %factor_3%
Gr&ouml;&szlig;te Strecke &lt; 500km:  Faktor %factor_4%
Gr&ouml;&szlig;te Strecke &lt; 700km:  Faktor %factor_5%
Gr&ouml;&szlig;te Strecke &lt; 1000km: Faktor %factor_6%
Gr&ouml;&szlig;te Strecke &gt; 1000km: Faktor %factor_7%</pre>
                  </blockquote>Die aktuellen Faktoren k&ouml;nnen der Tabelle
                  unter Piloten im Hauptmen&uuml; entnommen werden. Der
                  Fairness halber m&ouml;chten wir die Piloten bei denen falsche Werte
                  eingetragen sind bitten sich bei uns zu melden, damit wir
                  diese korrigieren können. <a href="javascript:hide_info();">weniger...</a>
                  </span><br><br>
                  Bei Fragen bezüglich der Wertung oder Änderungswünschen wendet euch
                  bitte an den zuständigen Vereinsbetreuer. Bei allen weiteren Fragen
                  zum System an sich: <a href="mailto:Tobias.Bieniek@gmx.de">Tobias.Bieniek@gmx.de</a>
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
                  <b>Hinweis: Auf Grund eines Updates des OLC-Servers kann es derzeit zu Problemen bei der Synchronisation mit Destination kommen. Die Probleme sind bekannt und werden sobald wie möglich behoben.</b><br><br>
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