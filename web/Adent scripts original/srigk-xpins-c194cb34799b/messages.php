<?php include("header.php");?>

<div id="content" class="main-container">


<?php
if(!isset($_SESSION['username'])){?>
<script type="text/javascript">
function leave() {
window.location = "login.html";
}
setTimeout("leave()", 2);
</script>
<?php }else{ ?>


<div class="container pull-col">

<div class="col-md-8">


<div class="panel panel-default">

<div class="panel-heading"><h1>Conversations</h1></div>

    <div class="panel-body">
    
<div class="actions">

<a class="mark-msg" href="new_message.html">Start a New Conversation</a>

</div><!--/.actions-->     

<div class="the-form">

<table class="table">
        <thead>
            <tr>
                <th>From</th>
                <th>Message</th>
                <th>Last Message</th>
            </tr>
        </thead>
        <tbody id="display-Mgs">
        
<?php


if($Messages = $mysqli->query("SELECT * FROM pms WHERE from_id='$Uid' OR to_id='$Uid' ORDER BY msg_order DESC LIMIT 0, 50")){
	
   $CountMsgs = $Messages->num_rows;	

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
  
                  
        </tbody>
    </table>
    
<?php

if($MsgByid==$Uid){

if($CheckMessages = $mysqli->query("SELECT * FROM pms WHERE from_delete=1 AND (from_id='$Uid' OR to_id='$Uid') ORDER BY msg_order DESC")){
	
   $CountCheckMsgs = $CheckMessages->num_rows;	
   
	$CheckMessages->close();
   
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

} 	

}else{

if($CheckMessages = $mysqli->query("SELECT * FROM pms WHERE to_delete=1 AND (from_id='$Uid' OR to_id='$Uid') ORDER BY msg_order DESC")){
	
   $CountCheckMsgs = $CheckMessages->num_rows;	
   
	$CheckMessages->close();
   
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

} 

}

if($CountCheckMsgs>1){

if($CountMsgs>0 && $deleteStats==1){
?>
<div class="slog-reg">Inbox is Empty</div>    
<?php }

}else if($CountMsgs==0 && $deleteStats==1){
?>
<div class="slog-reg">Inbox is Empty</div>    
<?php }

if($CountMsgs==0){

?>
<div class="slog-reg">Inbox is Empty</div>    
<?php }  ?>   
    
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
</div><!--the-form-->
   
</div>

</div><!--panel panel-default--> 



</div><!-- /.col-md-8 -->

<div class="col-md-4">
<?php if(!empty($Ad1)){ ?>
<div class="right-box">
<?php echo $Ad1;?>
</div><!--r-box-->
<?php }?>

<?php if(!empty($FbPage)){ ?>
<div class="right-box">
<div class="fb-like-box" data-href="<?php echo $FbPage;?>" data-width="292" data-show-faces="false" data-stream="false" data-show-border="false" data-header="false"></div>
</div><!--r-box-->
<?php }?>

<?php if(!empty($Ad2)){ ?>
<div class="right-box">
<?php echo $Ad2;?>
</div><!--r-box-->
<?php }?>
</div><!-- /.col-md-4 -->

</div><!-- /.container -->

<?php }?>

</div><!-- /.content -->

<?php include("footer_pages.php");?>  