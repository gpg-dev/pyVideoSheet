<?php
include ("db.php");

if($_POST)
{	
	if(!isset($_POST['inputRecovery']) || strlen($_POST['inputRecovery'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please provide us with a valid email address.</div');
	}
	
	$EmailAddress = $_POST['inputRecovery'];
	
	if (filter_var($EmailAddress, FILTER_VALIDATE_EMAIL)) {
  	// The email address is valid
	} else {
  		die('<div class="alert alert-danger" role="alert">Please provide a valid email address.</div>');
	}
		
if($SiteSettings = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $Settings = mysqli_fetch_array($SiteSettings);
	
	$SiteName = $Settings['name'];
	
	$SiteSettings->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

//Get User Details

if($UserCheck = $mysqli->query("SELECT * FROM users WHERE email='$EmailAddress'")){

   	$GetUserInfo = mysqli_fetch_array($UserCheck);
	
	$UserCount= mysqli_num_rows($UserCheck);

   	$UserCheck->close();
   
}else{
   
    printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");

}

if ($UserCount==1){

$UserName 			 = $GetUserInfo['username'];
$UserId				 = $GetUserInfo['id'];	

$ToContact	 	 	 = $mysqli->escape_string($_POST['inputRecovery']);
$FromName			 = $Settings['name'];
$FromEmail			 = $Settings['email'];
$FromSubject		 = "Your new password for ".$SiteName;
$SiteURL			 = $Settings['siteurl'];


function rand_string( $length ) {

$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#@!%^&*";
return substr(str_shuffle($chars),0,$length);

}
$NewPassword = rand_string(10);
$EncryptPassword = md5($NewPassword);

$FromMessage = "Dear ".$UserName.",
<br/>
As you requested, your password has now been reset. Your new details are as follows:
<br/><br/>
Username: ".$UserName."
<br/>
Password: ".$NewPassword."
<br/><br/>
To change your password, please visit this page: http://".$SiteURL."/settings.html after you logged in with your new password.
<br/>
<br/>
All the best,
<br/>
".$FromName;

require_once('include/class.phpmailer.php');

$mail             = new PHPMailer(); ;

$mail->AddReplyTo($FromEmail, $FromName);

$mail->SetFrom($FromEmail, $FromName);

$mail->AddAddress($ToContact);

$mail->Subject = $FromSubject;

$mail->MsgHTML($FromMessage);

if(!$mail->Send()) {

?>

<div class="alert alert-danger" role="alert">Error sending mail</div>

<?php 

} else {

$mysqli->query("UPDATE users SET password='$EncryptPassword' WHERE id='$UserId'") or die (mysqli_error());	
	
?>

<div class="alert alert-success" role="alert">Your password has now been reset. Please check your email for your new login details.</div>

<?php }

}else{ ?>
	
<div class="alert alert-danger" role="alert">We cannot find any user with this email address. Please check your email address.</div>
	
<?php }

}


?>