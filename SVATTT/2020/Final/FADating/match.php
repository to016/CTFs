<?php
@session_start();
error_reporting(0);
ini_set('display_errors', 0);
require_once 'dbconnect.php';
require_once 'patch.php';
?>
<style>
table {
	padding: 3%;
	border: dashed;
	border-color: #fc89ac;
	margin: auto;
}
td, th {
  text-align: left;
  padding: 10px;
}
.button2 {
    background-color: pink; 
    color: white; 
    width: 15%;
    border: 2px solid white;
    padding: 5px 32px;
    margin: auto;
}

.button1 {
    background-color: pink; 
    color: white; 
    border: 2px solid white;
    padding: 5px 32px;
}

input:not([type="submit"]) {
    background-color: white; 
    color: pink; 
    width: 15%;
    padding: 12px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 2px solid pink;
    border-radius: 4px;
}
</style>
<!DOCTYPE html>
<html lang="en">
<head>
<title>2FA Dating</title>
<link rel="stylesheet" href="assets/main.css">
<link rel="stylesheet" href="assets/w3.css">
</head>
<body>
</style>
<div class="navbar">
  <a href="index.php" id="home">HOME</a>
  <a href="profile.php" id="profile">Profile</a>
  <a href="match.php" id="match" class="active">Match</a>
  <a href="logout.php" id="logout" class="right">Logout</a>
</div>

<?php
if(isset($_SESSION["user"]) && !empty($_SESSION["user"])) {
  print("<br><br><br><center>People You May Love ;) </center><br><br><br>");
}
else
{
  die("<br><center>Unauthorized</center>");
}
?>

<table>
  <tr>
    <th>Name</th>
    <th>Year-of-Birth</th>
    <th>Comment</th>
  </tr>

<?php
  
  // Some rule to filter eligible person
  $res = mysqli_query($conn,"SELECT * FROM fa_dating_users JOIN fa_dating_comments ON fa_dating_users.fa_dating_username = fa_dating_comments.fa_dating_username WHERE fa_dating_users.fa_dating_username != 'admin' AND fa_dating_users.fa_dating_yob>1920 AND fa_dating_users.fa_dating_yob<2002 ORDER BY RAND() LIMIT 1");
  while($row = mysqli_fetch_array($res)) 
  {
    echo '<tr>';
    echo '<td>'.htmlentities($row['fa_dating_name']).'</td>';
    echo '<td>'.htmlentities($row['fa_dating_yob']).'</td>';
    echo '<td>'.htmlentities($row['fa_dating_comment']).'</td>';
    echo '</tr>';
    if(isset($_SESSION["user"]) && $_SESSION["user"]=="admin") {
      $save_username = $row['fa_dating_username'];
      $save_file = uniqid();
    }
  }
?>
</table>
<br><br>
<center>
<?php
if(isset($_SESSION["user"]) && $_SESSION["user"]=="admin"){
  $html = <<<EOF
  <form method="POST" action="match.php">
    <input hidden type="text" name="save_username" value="$save_username" /><br>
    <input hidden type="text" name="save_file" value="$save_file" /><br>
EOF;
  print($html);
  if(isset($_POST["save_username"]) && !empty($_POST["save_username"]) && isset($_POST["save_file"]) && !empty($_POST["save_file"]))
  {
    $save_file = $_POST["save_file"];
    $user = mysqli_real_escape_string($conn, $_POST["save_username"]);
    $res = mysqli_query($conn, "SELECT * FROM fa_dating_users WHERE fa_dating_username ='$user'");
    $row = mysqli_fetch_assoc($res);
    $save_info = trim($row['fa_dating_name'])."|".$row['fa_dating_yob'];
    file_put_contents("$save_file","$save_info");
  }
}
?>
  <button type="submit" class="button1" id="button_match" <?php if(isset($_SESSION["user"]) && $_SESSION["user"]!="admin") {echo 'onclick="match()"';}?> >❤️ Match</button>
</form>
  <div id = "text"></div>
</center>

<script>
  function match() {
    document.getElementById("text").innerHTML = "Nope!";
  }
</script>

