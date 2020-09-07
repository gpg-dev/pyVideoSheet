<?php
session_start();

include('db.php');

if(!isset($_SESSION['username'])){
	//Do Nothing
}else{
	
$UserName = $_SESSION['username'];

if($GetUser = $mysqli->query("SELECT * FROM users WHERE username='$UserName'")){

    $UserInfo = mysqli_fetch_array($GetUser);

	$UserId = $UserInfo['id'];
	
    $GetUser->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
}


if($_POST)
{	
	
	if(!isset($_POST['inputEmail']) || strlen($_POST['inputEmail'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please let us know your email adress.</div>');
	}
	
	$email_address = $_POST['inputEmail'];
	
	if (filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
  	// The email address is valid
	} else {
  		die('<div class="alert alert-danger">Please enter a valid email address.</div>');
	}
	
	
	
	$Email  			= $mysqli->escape_string($_POST['inputEmail']); // Email
	$About              = $mysqli->escape_string($_POST['inputAbout']); // About
	
		
	$mysqli->query("UPDATE users SET email='$Email', about='$About' WHERE id='$UserId'");
		
		
		die('<div class="alert alert-success">Your profile updated successfully.</div>');
		

   }else{
   		die('<div class="alert alert-danger">There seems to be a problem. Please try again.</div>');
   } 


}//End Checking Loggedin User
?>