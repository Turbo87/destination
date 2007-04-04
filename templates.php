<?php
/*
 * templates.php
 * Created on 21.03.2007 by Tobias Bieniek
 */

class Template {
	var $filename = "";
  var $vars = null;
  
	function Template($filename) {
    if (!file_exists($filename)) {
      if (function_exists("log_error")) log_error("Template ".$filename." not found!");
      return false; 
    }
    $this->filename = $filename;		
    $this->vars = null;
	}
  
  // Template-Variable zum Array hinzufügen
  function addVariable($var, $value) {
  	$this->vars[$var] = $value;
  }
  
  // Standard-Variablen hinzufügen
  function fillStandard($competition) {
  	$this->addVariable("comp_id", $competition['id']);
    $this->addVariable("comp_name", $competition['name']);
  }
  
  // Template mit Variablen ersetzen und ausgeben
  function getOutput() {
  	if (!file_exists($this->filename))
      return false;
    
    $output = implode("", file($this->filename));
    if (count($this->vars) > 0)
      foreach ($this->vars as $var => $value) {
  		  $output = str_replace("%".$var."%", $value, $output);
  		}
    
    return $output;
  }
}
?>