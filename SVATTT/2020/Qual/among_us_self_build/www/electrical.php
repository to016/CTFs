<?php

if (!defined('check_access')) 
{
  die("IMPOSTOR ALERT!!!");
}

?>
<div class="navbar">
  <a href="?page=home">Home</a>
  <a href="?page=crew">Crew</a>
  <a href="?page=map">Map</a>
  <a href="?page=cafeteria">Cafeteria</a>
  <a href="?page=medbay">MedBay</a>
  <a href="?page=electrical" class="active">Electrical</a>
  <a href="?page=o2">O2</a>
  <a href="?page=forgot" class="right">Forgot</a>
  <a href="?page=login" class="right">Login</a>
</div>

<style>
input[type="submit"] {
    background-color: black; 
    color: white; 
    border: 2px solid white;
    padding: 5px 32px;
}

input:not([type="submit"]) {
    background-color: black; 
    color: white; 
    width: 20%;
    padding: 12px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 2px solid white;
    border-radius: 4px;
}

</style>

<?php
if(!isset($_SESSION["user"]) || empty($_SESSION["user"])) {
	die("<br><center>Unauthorized!</center>");
}
?>
<!-- upload -->
<br><br>
<div class="w3-container">
  <div class="w3-black">
    <div id="myBar" class="w3-green" style="height:24px;width:0"></div>
  </div>
  <br>
</div>
<center>
<form id="myform" method="post" action="?page=electrical" enctype="multipart/form-data">
 	<input type="file" name='file'><br>
</form>
<br><input type="submit" name="upload_file" onclick="move()" value="Upload">
</center>
<?php
upload($_FILES["file"]);
?>


<!-- download -->
<br><br>
<div class="w3-container">
  <div class="w3-black">
    <div id="myBar2" class="w3-green" style="height:24px;width:0"></div>
  </div>
  <br>
</div>
<center>
<?php 
download();
?>
</center>


<script>
function move() {
  var elem = document.getElementById("myBar");   
  var width = 1;
  var id = setInterval(frame, 10);
  function frame() {
    if (width >= 100) {
      clearInterval(id);
    } else {
      width++; 
      elem.style.width = width + '%'; 
    }
  }
  setTimeout(() => {  document.getElementById("myform").submit(); }, 1000);
}

function move2() {
  var elem = document.getElementById("myBar2");   
  var width = 1;
  var id = setInterval(frame, 10);
  function frame() {
    if (width >= 100) {
      clearInterval(id);
    } else {
      width++; 
      elem.style.width = width + '%'; 
    }
  }
  setTimeout(() => {  document.getElementById("test").click(); }, 1000);
}

</script>

<img src="assets/vent.png" style="float:right;" width="3%"/>