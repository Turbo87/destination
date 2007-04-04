<?php 

function log2file($str) {
  file_put_contents("mi_update.log", file_get_contents("mi_update.log").$str);
}

set_time_limit(0);
include_once ("settings.php");

mysql_connect($mysql_host, $mysql_user, $mysql_pass);
mysql_select_db($mysql_db);

$res = mysql_query("SELECT * FROM competitions");
while ($row = mysql_fetch_array($res)) {
  $c[] = $row['id'];
}

log2file("+----------------------+\r\n");
log2file("| STARTING NEXT UPDATE |\r\n");
log2file("+----------------------+\r\n");

for($i = 0; $i < count($c); $i++) {
  log2file(date("d.m.Y H:i:s")." - Updating Competition \"".$c[$i]."\"\r\n");

  $f = fsockopen($update_server, 80, $errno, $errstr, 5);
  fputs($f, "GET $update_url/update.php?c=" . $c[$i] . " HTTP/1.1\r\n");
  fputs($f, "Host: $update_server\r\n");
  fputs($f, "\r\n");
  fclose($f);
    
  sleep(90);
}
?>