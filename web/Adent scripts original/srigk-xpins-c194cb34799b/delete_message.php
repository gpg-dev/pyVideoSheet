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
	
	$DeleteFrom = $MessageRow['from_delete'];
	
	$DeleteTo = $MessageRow['to_delete'];
	
	$FromRead = $MessageRow['from_read'];
	
	$ToRead = $MessageRow['to_read'];
   
  	$Messages->close();
   
	}else{
    
	printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
	
	}

if($MsgByid==$Uid){
	
	if($DeleteTo==1){
	$mysqli->query("DELETE FROM pms WHERE pm_id='$GetPm'");	
	$mysqli->query("DELETE FROM pm_replies WHERE pm_rid='$GetPm'");
	}else{		
	$mysqli->query("UPDATE pms SET from_delete=1, from_read=1 WHERE  pm_id='$GetPm'");
	}
		
	}else{
	
	//Msg Count
	if($ToRead==0){
	$mysqli->query("UPDATE users SET notifications=notifications-1 WHERE id='$Uid'");		
	}
	//End	
		
	if($DeleteFrom==1){
	$mysqli->query("DELETE FROM pms WHERE pm_id='$GetPm'");
	$mysqli->query("DELETE FROM pm_replies WHERE pm_rid='$GetPm'");	
	}else{		
	$mysqli->query("UPDATE pms SET to_delete=1, to_read=1 WHERE  pm_id='$GetPm'");
	}

	//Msg Count
	if($FromRead==0){
		$where = "(from_id='$Uid' AND from_delete=0) OR (to_id='$Uid' AND to_delete=0)";
		$select="COUNT(*) as count";
		$sql = "SELECT $select FROM pms WHERE $where";
		$count = 0;
		if ($newMes = $mysqli->query($sql)) {
			$NewMsgRow = mysqli_fetch_array($newMes);
			$count = $NewMsgRow['count'];
		}
		$mysqli->query("UPDATE users SET notifications=$count WHERE id='$Uid'");
	}
	//End
		
}

?>
<script>
$("#freeow").freeow("Oki..", "Conversation Deleted", {
classes: ["smokey"],
autoHide: true
});

$('#chats').delay(1000).slideUp(1000);

function leave() {
window.location = "messages.html";
}
setTimeout("leave()", 2000);

</script>
<?php

}

}

?>