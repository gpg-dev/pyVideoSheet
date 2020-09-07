<?php

session_start();

include("db.php");

if(isset($_SESSION['username'])){

if($_POST['id'])
{

//Get User

$Uname = $_SESSION['username'];

if($UserSql = $mysqli->query("SELECT * FROM users WHERE username='$Uname'")){

    $UserRow = mysqli_fetch_array($UserSql);

	$Uid = $UserRow['id'];

    $UserSql->close();

}else{

     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

}
	
	
$GetPm = $_POST['id'];

if($Messages = $mysqli->query("SELECT * FROM pms WHERE pm_id='$GetPm' LIMIT 1")){

   	$MessageRow = mysqli_fetch_array($Messages);
	
	$MsgByid = $MessageRow['from_id'];
   
  	$Messages->close();
   
	}else{
    
	printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
	
	}

if($MsgByid==$Uid){
	$mysqli->query("UPDATE pms SET from_read=1 WHERE  pm_id='$GetPm'");
	}else{
	$mysqli->query("UPDATE pms SET to_read=1 WHERE  pm_id='$GetPm'");	
}

$mysqli->query("UPDATE users SET notifications=notifications-1 WHERE id='$Uid'");

?>
<script>
$("#freeow").freeow("Oki..", "Conversation marked as read", {
classes: ["smokey"],
autoHide: true
});

</script>
<?php

}

}

?>