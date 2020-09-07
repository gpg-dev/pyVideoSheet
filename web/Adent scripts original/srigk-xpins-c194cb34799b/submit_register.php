<?php
session_start();

include('db.php');

if($_POST)
{	
	
	if(!isset($_POST['inputUsername']) || strlen($_POST['inputUsername'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please enter a username.</div>');
	}
	
	$CheckUserName = $mysqli->escape_string($_POST['inputUsername']);
	
	if($UserCheck = $mysqli->query("SELECT * FROM users WHERE username ='$CheckUserName'")){

   	$CheckRow = mysqli_fetch_array($UserCheck);
	
	$UserExists = $CheckRow['username'];

   	$UserCheck->close();
   
	}else{
   
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

	}
	
	
	$GetEmail = $mysqli->escape_string($_POST['inputEmail']);
	
	if($EmailCheck = $mysqli->query("SELECT * FROM users WHERE email='$GetEmail'")){

   	$CheckEmailRow = mysqli_fetch_array($EmailCheck);
	
	$EmailExists = $CheckEmailRow['email'];

   	$EmailCheck->close();
   
	}else{
   
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

	}
	
	if ($_POST['inputUsername'] == $UserExists)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Username already taken. Please try another.</div>');
	}
	
	if(!isset($_POST['inputUsername']) || strlen($_POST['inputUsername'])<3)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Username must be least 3 characters long.</div>');
	}
	
	if(!isset($_POST['inputEmail']) || strlen($_POST['inputEmail'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please let us know your email adress.</div>');
	}
	
	$ValidateEmail = $_POST['inputEmail'];
	
	if (filter_var($ValidateEmail, FILTER_VALIDATE_EMAIL)) {
  	// The email address is valid
	} else {
  		die('<div class="alert alert-danger">Please enter a valid email address.</div>');
	}
	
	if ($_POST['inputEmail'] == $EmailExists)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Email already taken. Please try another.</div>');
	}
	
	if(!isset($_POST['inputPassword']) || strlen($_POST['inputPassword'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please provide a password.</div>');
	}
	
	if(!isset($_POST['inputPassword']) || strlen($_POST['inputPassword'])<6)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Password must be least 6 characters long.</div>');
	}
		if(!isset($_POST['inputConfirmPassword']) || strlen($_POST['inputConfirmPassword'])< 1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please enter the same password as above.</div>');
	}
	
	if ($_POST['inputPassword']!== $_POST['inputConfirmPassword'])
 	{
		//required variables are empty
     	die('<div class="alert alert-danger">Password did not match! Try again.</div>');
 	
	}
	
	$rcode=$_REQUEST['inputCode'];
	
	if($rcode != $_SESSION['rand_code']) {

		?>
	
	<script>
	d = new Date();
	$("#cap").attr("src", "captcha.php?"+d.getTime());
	$('#inputCode').val('');
	</script> 
	
	<?php

		die('<div class="alert alert-danger">Please enter the correct code.</div>');


	}
	
			
	
	$UserName  			= $mysqli->escape_string($_POST['inputUsername']); // Username
	$Email  			= $mysqli->escape_string($_POST['inputEmail']); // Email
	$Password  			= $mysqli->escape_string($_POST['inputPassword']); // Password
	$EncryptedPassword  = md5($Password); // Encript Password
	$RegisteredDate		= date("F j, Y"); //Added date
	

	$mysqli->query("INSERT INTO users(username, email, password, reg_date) VALUES ('$UserName', '$Email', '$EncryptedPassword','$RegisteredDate')");
		
		
?>
<script type="text/javascript">
function leave() {
window.location = "login.html";
}
setTimeout("leave()", 1000);
</script>
<?php		
		
		die('<div class="alert alert-success">Thank you for Registering. Please wait while we redirect you to login.</div>');
		

   }else{
   		die('<div class="alert alert-danger">There seems to be a problem. Please try again.</div>');
   } 

?>