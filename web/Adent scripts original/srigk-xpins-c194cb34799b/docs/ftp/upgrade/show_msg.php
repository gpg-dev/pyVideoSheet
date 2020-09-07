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
<?php }else{

$Mpid = $mysqli->escape_string($_GET['mid']);	
	
?>

<script>
$(function() {

$(".mark-msg").click(function() 
{
var id = $(this).data("id");
var name = $(this).data("name");
var dataString = 'id='+ id ;
var parent = $(this);

if (name=='mark-unread')
{
$(this).fadeIn(200).html;
$.ajax({
type: "POST",
url: "mark_unread.php",
data: dataString,
cache: false,

success: function(html)
{
$('#output').html(html);
$('.mark-msg').css('display', 'none');
$('.mark-msg-2').css('display', 'inline-block');
}
});
}
return false;
});
});

$(function() {

$(".mark-msg-2").click(function() 
{
var id = $(this).data("id");
var name = $(this).data("name");
var dataString = 'id='+ id ;
var parent = $(this);

if (name=='mark-read')
{
$(this).fadeIn(200).html;
$.ajax({
type: "POST",
url: "mark_read.php",
data: dataString,
cache: false,

success: function(html)
{
$('#output').html(html);
$('.mark-msg-2').css('display', 'none');
$('.mark-msg').css('display', 'inline-block');
}
});
}
return false;
});
});

$(function() {

$(".btn-delete").click(function() 
{
var id = $(this).data("id");
var name = $(this).data("name");
var dataString = 'id='+ id ;
var parent = $(this);

if (name=='delete')
{
$(this).fadeIn(200).html;
$.ajax({
type: "POST",
url: "delete_message.php",
data: dataString,
cache: false,

success: function(html)
{
$('#output').html(html);
}
});
}
return false;
});
});

//Delete Button

$(function() {

$(".btn-delete").click(function() 
{
var id = $(this).data("id");
var name = $(this).data("name");
var dataString = 'id='+ id ;
var parent = $(this);

if (name=='delete-msg')
{
$(this).fadeIn(200).html;
$.ajax({
type: "POST",
url: "delete_message.php",
data: dataString,
cache: false,

success: function(html)
{
$('#output').html(html);
}
});
}
return false;
});
});
</script>


<div class="container pull-col">

<div class="col-md-8">

<?php
	
	$TotNewMsgs = $UserRow['notifications'];
	
	if($TotNewMsgs>0){
		
	$mysqli->query("UPDATE users SET notifications=notifications-1 WHERE id='$Uid'");
	
	}

	function clickableurls($text) {

        //Not perfect but it works - please help improve it. 
        $text=preg_replace('/([^(\'|")]((f|ht){1}tp(s?):\/\/)[-a-zA-Z0-9@:%_\+.~#?&;\/\/=]+[^(\'|")])/','<a href="\\1" target="_blank">\\1</a>', $text);
        $text=preg_replace("/(^|[ \\n\\r\\t])([^('|\")]www\.([a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+)(\/[^\/ \\n\\r]*)*[^('|\")])/",
                '\\1<a href="http://\\2" target="_blank">\\2</a>', $text);
        $text=preg_replace("/(^|[ \\n\\r\\t])([^('|\")][_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}[^('|\")])/",'\\1<a href="mailto:\\2" target="_blank">\\2</a>', $text);


        return $text;
    }

	
   if($Messages = $mysqli->query("SELECT * FROM pms WHERE pm_id='$Mpid' LIMIT 1")){

   $MessageRow = mysqli_fetch_array($Messages);
	   
	   $ShowMessages = $MessageRow['message'];
	   $MsgByid = $MessageRow['from_id'];
	   $MsgByUser = $MessageRow['from_name'];		
	   $MsgToUser = $MessageRow['to_name'];
	   
	   $UserLink = preg_replace("![^a-z0-9]+!i", "-", $MsgByUser);
	   $UserLink = strtolower($UserLink);
	   
	   $ShowMessages =  clickableurls(nl2br($ShowMessages));
	   
	   $FromAvatarImg = $MessageRow['avatar'];
	   
	   
	   if (empty($FromUavatar)){ 
		$FromAvatarImg =  'templates/'.$settings['template'].'/images/default-avatar.png';
		}else{
		$FromAvatarImg =  $FromUavatar;
		}
		
	if($MsgByid==$Uid){
	$mysqli->query("UPDATE pms SET from_read=1 WHERE  pm_id='$Mpid'");
	$MsgDeleted = $MessageRow['to_delete'];
	}else{
	$mysqli->query("UPDATE pms SET to_read=1 WHERE  pm_id='$Mpid'");
	$MsgDeleted = $MessageRow['from_delete'];	
	}
	
	$Messages->close();
   
	}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
	}


?> 


<div class="panel panel-default">

<div class="panel-heading"><h1>Conversation Between <?php echo ucfirst($MsgByUser);?> &amp; <?php echo ucfirst($MsgToUser);?></h1></div>

    <div class="panel-body">
    
<div class="actions">

<a class="mark-msg" data-name="mark-unread" data-id="<?php echo $Mpid;?>">Mark as Unread</a>
<a class="mark-msg-2" data-name="mark-read" data-id="<?php echo $Mpid;?>">Mark as Read</a>

&nbsp;&nbsp; 
<a class="btn-delete" data-name="delete-msg" data-id="<?php echo $Mpid;?>">Delete Conversation</a>

</div><!--/.actions-->    

<div class="the-form">

<div class="msg-box" id="chats">

<div id="output"></div>

   
       
            <ul class="chat">

                        <li class="left clearfix"><span class="chat-img pull-left">
                            <a href="profile-<?php echo $MsgByid;?>-<?php echo $UserLink;?>.html" target="_blank"><img src="timthumb.php?src=http://<?php echo $settings['siteurl']?>/<?php echo $FromAvatarImg;?>&amp;h=50&amp;w=50&amp;q=100" alt="<?php echo $MsgByUser;?> <?php echo $MessageRow['username'];?>" class="media-object" /></a>
                        </span>
                            <div class="chat-body clearfix">
                                <div class="chat-header">
                                    <strong class="primary-font"><a href="profile-<?php echo $MsgByid;?>-<?php echo $UserLink;?>.html" target="_blank"><?php echo $MsgByUser;?></a></strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span> <?php echo $MessageRow['pm_date'];?></small>
                      </div>
             
                                <p><?php echo $ShowMessages;?></p>
                            </div>
                        </li>                     
                    </ul>

<?php
   if($Replies = $mysqli->query("SELECT * FROM pm_replies WHERE pm_rid='$Mpid'")){

   while($RepliesRow = mysqli_fetch_array($Replies)){
	   
	   $ShowReplies = $RepliesRow['message'];
	   $RepliesByid = $RepliesRow['from_id'];
	   $RepliesByUser = $RepliesRow['from_name'];		
	   
	   $RepliesUserLink = preg_replace("![^a-z0-9]+!i", "-", $MsgByUser);
	   $RepliesUserLink = strtolower($UserLink);
	   
	   $ShowReplies =  clickableurls(nl2br($ShowReplies));
	   
	   $RepliesFromUavatar = $RepliesRow['avatar'];
	   
	   
	    if (empty($RepliesFromUavatar)){ 
		$RepliesFromAvatarImg =  'templates/'.$settings['template'].'/images/default-avatar.png"';
		}else{
		$RepliesFromAvatarImg =  $RepliesFromUavatar;
		}

?>        
            <ul class="chat">
                          <li class="left clearfix"><span class="chat-img pull-left">
                            <a href="profile-<?php echo $RepliesByid;?>-<?php echo $RepliesUserLink;?>.html" target="_blank"><img src="timthumb.php?src=http://<?php echo $settings['siteurl']?>/<?php echo $RepliesFromAvatarImg;?>&amp;h=50&amp;w=50&amp;q=100" alt="<?php echo $RepliesByUser;?>" class="media-object" /></a>
                        </span>
                            <div class="chat-body clearfix">
                                <div class="chat-header">
                                    <strong class="primary-font"><a href="profile-<?php echo $RepliesByid;?>-<?php echo $RepliesUserLink;?>.html" target="_blank"><?php echo $RepliesByUser;?></a></strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span> <?php echo $RepliesRow['pm_date'];?></small>
                      </div>
           
                                <p><?php echo $ShowReplies;?></p>
                            </div>
                        </li>                     
                    </ul>
<?php
}

$Replies->close();
   
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

if (empty($Uavatar)){ 
	$AvatarImg =  'templates/'.$settings['template'].'/images/default-avatar.png';
}elseif (!empty($Uavatar)){
	$AvatarImg =  'avatars/'.$Uavatar;
}
?>


<div id="jr"></div>

<div class="comment-form">

<?php if($MsgDeleted==0){?>	

		<form id="replyForm" method="post" action="">
    	<div>
        	<input type="hidden" name="name" id="name" value="<?php echo $Uname;?>" />
            
            <input type="hidden" name="ruid" id="ruid" value="<?php echo $Uid;?>" />
            
            <input type="hidden" name="toid" id="toid" value="<?php echo $MsgByid;?>" />
            
            <input type="hidden" name="toname" id="toname" value="<?php echo $MsgByUser;?>" />
            
            <input type="hidden" name="avatarlink" id="avatarlink" value="<?php echo $AvatarImg;?>" />
            
            <input type="hidden" name="pmid" id="pmid" value="<?php echo $Mpid;?>" />

           <div class="form-group">
                       
           <textarea name="body" id="body" class="form-control"></textarea>
           
           </div>
            
            <input type="submit" id="submit" class="btn btn-default btn-info btn-lg pull-right" value="Send" /></label>
        </div>
    </form>
    
<?php }else{?>

<div class="alert alert-danger" role="alert">You can no longer reply to this conversation. You can start a new conversation or delete conversation.</div>

<?php }?>
    
    </div>
    
<script type="text/javascript" src="js/jquery.pms.js"></script>
    
  
                  
</div><!--msg-box-->
   
    
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