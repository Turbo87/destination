<?php
/*
 * update.news.php
 * Created on 20.04.2007 by Tobias Bieniek
 */

if (!function_exists("addNews")) {
  function addNews ($header, $str, $author, $competition, $db) {
    include_once("sql.php");
    
    $id = db_query_count("SELECT * FROM %pre%news", $db, $competition['id']) + 1;
    
    db_query("INSERT INTO %pre%news VALUES(".$id.", ".time().", '".$author."', '".
    $header."', '".$str."')", $db, $competition['id']);
  }
} 
?>
