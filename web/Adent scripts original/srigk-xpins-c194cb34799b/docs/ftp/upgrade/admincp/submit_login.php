<?php
session_start();
ob_start();

include('../db.php');

if(!isset($_SESSION['adminuser'])){

if($_POST)
{	
	
	$username =	$mysqli->escape_string($_POST['inputUsername']); 
	$password = $mysqli->escape_string($_POST['inputPassword']);
	$gpassword=md5($password);
	
	if($UserCheck = $mysqli->query("SELECT * FROM admin WHERE adminuser='$username' and adminpassword='$gpassword'")){

   	$VdUser = mysqli_fetch_array($UserCheck);
	
	$Count= mysqli_num_rows($UserCheck);

   	$UserCheck->close();
   
	}else{
   
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");

	}
	
	if ($Count == 1)
	{
		//required variables are empty
		$_SESSION["adminuser"] = $username;
		//header("location:index.html");
?>
<script type="text/javascript">
function leave() {
  window.location = "index.php";
}
setTimeout("leave()", 1000);
</script>
<?php

	die('<div class="alert alert-success" role="alert">Login You on. Please Wait...</div>');
	
	
   }else{
	   
?>
<script>
$('.panel').shake();
</script>
<?php	   
	   
	   die('<div class="alert alert-danger" role="alert">Wrong username or password.</div>');
   		
   } 
}
}
ob_end_flush();
 
?>