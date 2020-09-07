<?php
session_start();

include("db.php");

if(isset($_SESSION['username'])){
	
$Uname = $_SESSION['username'];

if($UserSql = $mysqli->query("SELECT * FROM users WHERE username='$Uname'")){

    $UserRow = mysqli_fetch_array($UserSql);

	$Uid = $UserRow['id'];
	
	$Uavatar = $UserRow['avatar'];

    $UserSql->close();
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
}

}

$page = $_GET["page"];
$start = ($page - 1) * 50;


if($Messages = $mysqli->query("SELECT * FROM pms WHERE to_id='$Uid' ORDER BY msg_order DESC LIMIT $start, 50")){

   while($MessageRow = mysqli_fetch_array($Messages)){
	   
	   $ShowMessages = $MessageRow['message'];
	   $MsgByid = $MessageRow['from_id'];
	   $MsgByUser = $MessageRow['from_name'];		
	   
	   $SortMessages= strlen ($ShowMessages);
	   if ($SortMessages> 32) {
	   $Msg = substr($ShowMessages,0,28).'...';
	   }else{
	   $Msg = $ShowMessages;}
	   
	   $Msg = strip_tags($Msg);
	   
	   $UserLink = preg_replace("![^a-z0-9]+!i", "-", $MsgByUser);
	   $UserLink = strtolower($UserLink);
	   
	   
	   if($MsgByid==$Uid){
	   $Stats 		 = $MessageRow['from_read'];
	   $deleteStats  = $MessageRow['from_delete'];
	   }else{
	   $Stats = $MessageRow['to_read'];  
	   $deleteStats  = $MessageRow['to_delete'];	 
	   }

	if($deleteStats==0){

?>        
            <tr class="col-mgs <?php if($Stats==0){echo "col-read";}?>">
                <td><a href="profile-<?php echo $MsgByid;?>-<?php echo $UserLink;?>.html" target="_blank"><?php echo $MessageRow['from_name'];?></a></td>
                <td><a href="show_msg-<?php echo $MessageRow['pm_id'];;?>.html"><?php echo $Msg;?></a></td>
                <td><?php echo $MessageRow['last_msg'];?></td>
            </tr> 
<?php
	}

}$Messages->close();
   
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
}
?>

<nav id="page-nav">
  			<a href="data_messages.php?page=2"></a>
</nav>    
    
<script src="js/jquery.infinitescroll.min.js"></script>

<script>
$('#display-Mgs').infinitescroll({
		navSelector  : '#page-nav',    // selector for the paged navigation 
      	nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
      	itemSelector : '.col-mgs',     //
		loading: {
          				finishedMsg: 'No more posts to load.',
          				img: 'templates/<?php echo $Settings['template'];?>/images/loader.gif'
	}
	}, function(newElements, data, url){
		
	});	    
</script>