<script>
function activate(id) {
  var row = document.getElementById("tab_"+id);
  if (row != null) {
    row.style.backgroundColor = "";
    row.className = "active_tab_item";
  }
    
  row = document.getElementById("table_"+id);
  if (row != null) 
    row.style.display = "";
}
function deactivateAll() {
  var row = null;
  for (i = %startyear%; i <= %endyear%; i++) {
    row = document.getElementById("tab_"+i);
    if (row != null) 
      row.className = "";
      
    row = document.getElementById("table_"+i);
    if (row != null) 
      row.style.display = "none";
  }
  
  row = document.getElementById("tab_total");
  if (row != null) 
    row.className = "";
    
  row = document.getElementById("table_total");
  if (row != null) 
    row.style.display = "none";
}
function hover2(obj) {
  if (obj.className == "active_tab_item") return;
  obj.style.backgroundColor = "#E8FFE8";
}
function unhover2(obj) {
  if (obj.className == "active_tab_item") return;
  obj.style.backgroundColor = "#FFFFFF";
  }
</script>
<style>
.active_tab_item {
  BACKGROUND: #004422;
  COLOR: white; 
}
</style>
<table style="BORDER: #004422 1px solid;" cellspacing="0" cellpadding="3" width="432">
  <tbody>
    <tr style="FONT-SIZE: 18px; HEIGHT: 30px;">
      %tabs%
      <td></td>
    </tr>

    <tr>
      %tables%
    </tr>
  </tbody>
</table>
<script>
deactivateAll();
activate(%endyear%);
</script>