<?
if (!function_exists("preRender")) {
  function preRender($competition, $db) {
    include_once("filemanager.php");
    include_once("pre.stats.php");
    include_once("pre.external_js.php");
  }
}
?>