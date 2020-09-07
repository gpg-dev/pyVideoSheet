<?php

// Error reporting:
error_reporting(E_ALL^E_NOTICE);

include "db.php";
include "include/reply.class.php";

$Date		        = date("F j, Y");
$MsgOrder			= date("Ymd").time();


$arr = array();
$validates = Comment::validate($arr);

if($validates)
{
	/* Everything is OK, insert to database: */
	
	$mysqli->query("INSERT INTO pm_replies(from_id,from_name,to_id,to_name,avatar,message,pm_date,pm_rid)
					VALUES (
						'".$arr['ruid']."',
						'".$arr['name']."',
						'".$arr['toid']."',
						'".$arr['toname']."',
						'".$arr['avatarlink']."',
						'".$arr['body']."',
						'".$Date."',
						'".$arr['pmid']."'
					)");
	
	$arr['dt'] = date('r',time());

	$GetPm = $arr['pmid'];
	$GetReply = $arr['ruid']; 
	
	if($Messages = $mysqli->query("SELECT * FROM pms WHERE pm_id='$GetPm' LIMIT 1")){

   	$MessageRow = mysqli_fetch_array($Messages);
	
	$GetFrom = $MessageRow['from_id'];
   
  	$Messages->close();
   
	}else{
    
	printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
	
	}

	
	if($GetFrom==$GetReply){
	
	$mysqli->query("UPDATE pms SET to_read=0,last_msg='$Date',msg_order='$MsgOrder' WHERE pm_id='$GetPm'");
	
	}else{
	
	$mysqli->query("UPDATE pms SET from_read=0,last_msg='$Date',msg_order='$MsgOrder' WHERE pm_id='$GetPm'");	
		
	}
	
	$mysqli->query("UPDATE users SET notifications=notifications+1 WHERE id='".$arr['toid']."'");
	
	$arr = array_map('stripslashes',$arr);
	
	$insertedComment = new Comment($arr);

	/* Outputting the markup of the just-inserted comment: */

	echo json_encode(array('status'=>1,'html'=>$insertedComment->markup()));

	}
	else
	{
	/* Outputtng the error messages */
	echo '{"status":0,"errors":'.json_encode($arr).'}';
	
	}

?>