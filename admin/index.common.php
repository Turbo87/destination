<?php
if (!function_exists("show_error")) {
  // Fehler anzeigen und weitere Skriptausführung unterbinden
  function show_error($msg) {
?>
<table align="center" width="500" style="PADDING-TOP: 40px;">
	<tr>
		<td valign="top" width="1"><img src="../templates/error.jpg"></td>
		<td style="FONT-FAMILY: Tahoma;"><b>Es ist ein Fehler aufgetreten!</b><br>
		<br>
		<?=str_replace("&gt;", ">", str_replace("&lt;", "<", htmlentities($msg)))?></td>
	</tr>
</table>
<?php
    die;
  }
}
?>