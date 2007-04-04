<?
mysql_connect("localhost", "teutocup", "blablubb");
mysql_select_db("teutocup");

$res_weeks = mysql_query("SELECT MIN(raceweek) as min, MAX(raceweek) as max FROM ranking WHERE year = 2009");
if ($weeks = mysql_fetch_array($res_weeks)) {
  $min = $weeks['min'];
  $max = $weeks['max'];
} else {
  die;
}

echo "<table width=100%>\r\n";
echo "<tr style=\"FONT-WEIGHT: bold;\">\r\n";
echo "<td>#</td>\r\n";
echo "<td>Pilot</td>\r\n";
for ($i = $min; $i <= $max; $i++) {
  echo "<td width=30 align=center>$i</td>\r\n";
}
echo "<td align=center></td>\r\n";
echo "<td align=center>Ges.</td>\r\n";
echo "</tr>\r\n";

$place = 1;
$res_ranking = mysql_query("SELECT year, pilot, SUM(points) as points FROM ranking WHERE year = 2009 GROUP BY pilot ORDER BY points DESC");
while ($rank = mysql_fetch_array($res_ranking)) {
  echo "<tr style=\"BACKGROUND: ".($place % 2 == 1 ? "#cccccc" : "#eeeeee").";\">\r\n";
  echo "<td>$place</td>\r\n";
  echo "<td>".$rank['pilot']."</td>\r\n";
  for ($i = $min; $i <= $max; $i++) {
    $str = "";
    $res_points = mysql_query("SELECT points, speed, id FROM ranking WHERE year = ".$rank['year']." AND raceweek = $i AND pilot = '".$rank['pilot']."'");
    if ($points = mysql_fetch_array($res_points)) {
      $id  = substr($points['id'], 5);
      $str = "<a href=\"http://www.onlinecontest.org/olc-2.0/gliding/flightinfo.html?flightId=".$id."\">".$points['points']."<br><font size=1>(".$points['speed'].")</font></a>";
    }
    echo "<td align=center>".$str."</td>\r\n";
  }
  echo "<td align=center>&nbsp;&nbsp;=&nbsp;&nbsp;</td>\r\n";
  echo "<td align=center>".$rank['points']."</td>\r\n";
  $place++;
}

echo "</table>\r\n";

mysql_close();
?>