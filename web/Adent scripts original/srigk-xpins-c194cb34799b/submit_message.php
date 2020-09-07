<?php
session_start();

include('db.php');

//Get User Settings

if($SiteSettings = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $Settings = mysqli_fetch_array($SiteSettings);
	
	$Active = $Settings['active'];
	
	$SiteSettings->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
}

//From User Info

$LoggedUser = $_SESSION['username'];

if($GetUser = $mysqli->query("SELECT * FROM users WHERE username='$LoggedUser'")){

    $UserInfo = mysqli_fetch_array($GetUser);

	$UserId = $UserInfo['id'];
	
	$Avatar = $UserInfo['avatar'];
	
	$FromUsername = stripslashes($UserInfo['username']);
	
	$GetUser->close();
	
}else{
     
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
	 
}

//To User Info

$Mpid = $mysqli->escape_string($_GET['id']);

if($ThisUserSql = $mysqli->query("SELECT * FROM users WHERE id='$Mpid'")){

   $ThisUserRow = mysqli_fetch_array($ThisUserSql);
   
   $ThisUN = stripslashes($ThisUserRow['username']);
   
   $ThisUserSql->close();
   
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
}


if($_POST)
{	

	if(!isset($_POST['inputMessage']) || strlen($_POST['inputMessage'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please enter your message</div>');
	}
	
	
	
		
	$Message			= $mysqli->escape_string($_POST['inputMessage']); // file title
	$SendDate			= date("F j, Y");
	$MsgOrder			= date("Ymd").time();
	
	
		
	$mysqli->query("INSERT INTO pms(from_id, from_name, to_id, to_name, avatar, message, pm_date, from_read, to_read, from_delete, to_delete, last_msg, msg_order) VALUES ('$UserId','$FromUsername','$Mpid','$ThisUN','$Avatar','$Message','$SendDate','1','0','0','0','$SendDate','$MsgOrder')");
		

	$mysqli->query("UPDATE users SET notifications=notifications+1 WHERE id='$Mpid'");

?>

<script>
$('#postMessage').delay(500).resetForm(1000);
$('#postMessage').delay(1000).slideUp(1000);
</script>

<?php
		die('<div class="alert alert-success" role="alert">Message sent.</div>');

		
   }else{
   		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
   
   
}



?>