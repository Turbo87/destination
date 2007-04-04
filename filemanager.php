<?
function getFilename($longname, $db, $competition) {
  $res_files = db_query("SELECT * FROM files WHERE longname = '$longname'", $db, $competition['id']);
  if (!($file = db_fetch_array($res_files))) return false;
  if (!file_exists($file['shortname'])) return false;
  return $file['shortname'];
}

function getFilenameOrNew($longname, $db, $competition) {
  $shortname = getFilename($longname, $db, $competition);
  if ($shortname != false) return $shortname;
  else {
    $longname2 = str_replace(strchr($longname, "."), str_replace("/", "~", strchr($longname, ".")), $longname);
    //$filename = substr(strrchr($longname, "/"),1);
    $filename = basename($longname2);
    $filename = substr($filename,0,strlen($filename)-strlen(strrchr($filename, ".")));

    $i = 1;
    while (file_exists(str_replace($filename, $i, $longname2))) {
      $i++;
    }
    $shortname = str_replace($filename, $i, $longname2);
    db_query("DELETE FROM files WHERE shortname = '$shortname' OR longname = '$longname'", $db, $competition['id']);
    db_query("INSERT INTO files VALUES ('$shortname', '$longname')", $db, $competition['id']);
    return $shortname;
  }
}
?>