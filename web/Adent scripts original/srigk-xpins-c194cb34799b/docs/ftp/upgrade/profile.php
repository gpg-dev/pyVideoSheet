<?php include("header_profile.php");?>
<script>
$(function() {
//$("#content").on("click",".like",function()
$(".like").click(function() 
{
var id = $(this).data("id");
var name = $(this).data("name");
var dataString = 'id='+ id ;
var parent = $(this);

if (name=='up')
{
$(this).fadeIn(200).html;
$.ajax({
type: "POST",
url: "like_vote.php",
data: dataString,
cache: false,

success: function(html)
{
parent.html(html);
}
});
}
return false;
});
});

</script>

<div class="container">

<div class="col-md-3 pull-col">

<div class="page-title-profile"><h1><?php echo ucfirst($ThisUN);?></h1></div>

<?php if (empty($avatar)){ ?>
	<img src="templates/<?php echo $settings['template'];?>/images/default-avatar.png" width="250" height="250" alt="<?php echo ucfirst($ThisUN);?>" class="img-responsive img-profile">
	<?php }elseif (!empty($avatar)){?>
	<img src="timthumb.php?src=http://<?php echo $settings['siteurl'];?>/avatars/<?php echo $ThisUserRow['avatar'];?>&amp;h=250&amp;w=250&amp;q=100" alt="<?php echo ucfirst($ThisUN);?>" class="img-responsive img-profile"/>
	<?php }?>

<?php if(!empty($ThisUserLong)){?>    
<div class="left-box">
<p><?php echo $ThisUserLong;?></p>
</div><!-- /.left-box -->
<?php }?>

<div class="left-box">
<span class="glyphicon glyphicon-comment"></span> &nbsp;<a href="send_message-<?php echo $upid;?>.html">Send an Message</a>
</div><!-- /.left-box -->

</div><!-- /.col-md-3 -->

<div class="col-md-9 pull-col">

<div class="page-title-profile"><h1 class="pull-left">Posts by <?php echo ucfirst($ThisUN);?></h1> <a class="pull-right remove-display" href="user_posts-<?php echo $upid;?>-<?php echo $ThisULink;?>.html">View All Posts by <?php echo ucfirst($ThisUN);?></a></div>

<?php

	$result = $mysqli->query("SELECT * FROM posts WHERE uid=$upid ORDER BY id DESC LIMIT 12");
	
	$CountPosts= mysqli_num_rows($result);
	
	if($CountPosts == 0){
		
		echo '<div class="no-posts">'.ucfirst($ThisUN).' haven&#39;t posted anything yet</div>';
		
	}
	
	while($row = mysqli_fetch_array($result))
  	{
		$long = stripslashes($row['description']);
		$strd = strlen ($long);
		if ($strd > 30) {
		$dlong = substr($long,0,27).'...';
		}else{
		$dlong = $long;}
		
		$LongTitle = stripslashes($row['title']);
		$strt = strlen ($LongTitle);
		if ($strt > 30) {
		$tlong = substr($LongTitle,0,27).'...';
		}else{
		$tlong = $LongTitle;}
		
		$PageLink = preg_replace("![^a-z0-9]+!i", "-", $LongTitle);
		$PageLink = strtolower($PageLink);
		$PageLink = strtolower($PageLink);
		
		
		//new code
		$Type = $row['type'];
		
		$PostId = $row['id'];
		$up = $row['likes'];
		
		if ($Uid >0){
		//Check Votes

		if($VcSql= $mysqli->query("SELECT uid FROM favip WHERE postid='$PostId' and uid='$Uid'")){

   		 $VcRow = mysqli_num_rows($VcSql);
	
		}else{
	
     	printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");

		}
			
		}
		
	
?>
                <div class="box-profile">
                                
                <?php if($Type==2){?><div class="play-button"><a href="post-<?php echo $row['id'];?>-<?php echo $PageLink;?>.html" target="_self"><span class="fa fa-video-camera"></span> Play</a></div><?php }else if($Type==3){?><div class="play-button"><a href="post-<?php echo $row['id'];?>-<?php echo $PageLink;?>.html" target="_self"><span class="fa fa-volume-up"></span> Play</a></div><?php }?>                         
                
                <a href="views.php?id=<?php echo $row['id'];?>" target="_blank" title="<?php echo $row['title'];?>">
                                         
                <img src="timthumb.php?src=http://<?php echo $settings['siteurl'];?>/uploaded_images/<?php echo $row['image']; ?>&amp;h=200&amp;w=275&amp;q=100" alt="<?php echo $row['title']; ?>" class="box-image" >
                </a>
                                               
         	<div class="social-icon-box">
            <a class="social-icons fb-button fa fa-facebook page-social pull-left" href="javascript:void(0);" onclick="popup('http://www.facebook.com/share.php?u=http://<?php echo $SiteLink;?>/post-<?php echo $PostId;?>-<?php echo $PageLink;?>.html&amp;title=<?php echo urlencode(ucfirst($LongTitle));?>')"></a>

<a class="social-icons twitter-button fa fa-twitter page-social pull-left" href="javascript:void(0);" onclick="popup('http://twitter.com/home?status=<?php echo urlencode(ucfirst($LongTitle));?>+http://<?php echo $SiteLink;?>/post-<?php echo $PostId;?>-<?php echo $PageLink;?>.html')"></a>

<a class="social-icons gpluf-button fa fa-google-plus page-social pull-left" href="javascript:void(0);" onclick="popup('https://plus.google.com/share?url=<?php echo $SiteLink;?>/post-<?php echo $PostId;?>-<?php echo $PageLink;?>.html')"></a>

<a class="social-icons pinterest-button fa fa-pinterest page-social pull-left"  href="javascript:void(0);" onclick="popup('http://pinterest.com/pin/create%2Fbutton/?url=http://<?php echo $SiteLink;?>/post-<?php echo $PostId;?>-<?php echo $PageLink;?>.html')"></a>
            

<a class="social-icons fa fa-search-plus page-social pull-right"  href="post-<?php echo $row['id'];?>-<?php echo $PageLink;?>.html" ></a>  
<a class="social-icons fa fa-link page-social pull-right"  href="views.php?id=<?php echo $row['id'];?>" ></a>
          
            </div>
                    <h3><a href="post-<?php echo $row['id'];?>-<?php echo $PageLink;?>.html" target="_self"><?php echo $tlong;?></a></h3>  
					<p><?php echo $dlong;?></p>
                    
       <div class="like-box">
            <div class='up'>
			<?php if(!isset($_SESSION['username'])){?>
			<a href="" class="like" data-id="<?php echo $PostId;?>" data-name="up"><span class="likes two fa fa-heart"> <?php echo $up; ?></span></a>
			<?php }else{ ?>
			<?php if ($VcRow == NULL){?>
			<a href="" class="like" data-id="<?php echo $PostId;?>" data-name="up"><span class="likes two fa fa-heart"> <?php echo $up; ?></span></a>
			<?php }elseif ($VcRow ==1) {?>
			<a href="" class="like" data-id="<?php echo $PostId;?>" data-name="up"><span class="likes one fa fa-heart"> <?php echo $up; ?></span></a>
			<?php } }?>
	   <div class="tot-views fa fa-eye"> <?php echo $row['tot'];?></div>
	   </div><!-- /up-->
            </div><!-- /like-box-->    
                </div><!-- /box -->
                
<?php } ?>

<div class="page-title-profile"><h1 class="pull-left">Likes by <?php echo ucfirst($ThisUN);?></h1><a class="pull-right remove-display" href="user_likes-<?php echo $upid;?>-<?php echo $ThisULink;?>.html">View All Likes by <?php echo ucfirst($ThisUN);?></a></div>

<?php
	
	$likesSql = $mysqli->query("SELECT * FROM favip LEFT JOIN posts ON favip.postid=posts.id WHERE favip.uid=$upid ORDER BY favip.id DESC LIMIT 12");
	
	$CountLikes = mysqli_num_rows($likesSql);
	
	if($CountLikes == 0){
		
		echo '<div class="no-posts">'.ucfirst($ThisUN).' haven&#39;t liked any posts</div>';
		
	}
	
	while($likesrow = mysqli_fetch_array($likesSql))
  	{
		$likeslong = $likesrow['description'];
		$Likesstrd = strlen ($likeslong);
		if ($Likesstrd > 30) {
		$dlikeslong = substr($likeslong,0,27).'...';
		}else{
		$dlikeslong = $likeslong;}
		
		$LikesLTitle = $likesrow['title'];
		$strt = strlen ($LikesLTitle);
		if ($strt > 30) {
		$tlong = substr($LikesLTitle,0,27).'...';
		}else{
		$tlong = $LikesLTitle;}
		
		$LikesLink = preg_replace("![^a-z0-9]+!i", "-", $LongTitle);
		$LikesLink = strtolower($LikesLink);
		$LikesLink = strtolower($LikesLink);
		
		
		//new code
		$LmesId = $likesrow['id'];
		$Lup = $likesrow['likes'];
		
		if ($Uid >0){
		//Check Votes

		if($LVcSql= $mysqli->query("SELECT uid FROM favip WHERE postid='$LmesId' and uid='$Uid'")){

   		 $LVcRow = mysqli_num_rows($LVcSql);
	
		}else{
	
     	printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");

		}
			
		}
		
	
?>

<div class="box-profile">
                                
                <?php if($Type==2){?><div class="play-button"><a href="post-<?php echo $likesrow['id'];?>-<?php echo $LikesLink;?>.html" target="_self"><span class="fa fa-video-camera"></span> Play</a></div><?php }else if($Type==3){?><div class="play-button"><a href="post-<?php echo $likesrow['id'];?>-<?php echo $LikesLink;?>.html" target="_self"><span class="fa fa-volume-up"></span> Play</a></div><?php }?>                         
                
                <a href="views.php?id=<?php echo $likesrow['id'];?>" target="_blank" title="<?php echo $likesrow['title'];?>">
                                         
                <img src="timthumb.php?src=http://<?php echo $settings['siteurl'];?>/uploaded_images/<?php echo $likesrow['image']; ?>&amp;h=200&amp;w=275&amp;q=100" alt="<?php echo $likesrow['title']; ?>" class="box-image" >
                </a>
                                               
         	<div class="social-icon-box">
            <a class="social-icons fb-button fa fa-facebook page-social pull-left" href="javascript:void(0);" onclick="popup('http://www.facebook.com/share.php?u=http://<?php echo $SiteLink;?>/post-<?php echo $LmesId;?>-<?php echo $LikesLink;?>.html&amp;title=<?php echo urlencode(ucfirst($LongTitle));?>')"></a>

<a class="social-icons twitter-button fa fa-twitter page-social pull-left" href="javascript:void(0);" onclick="popup('http://twitter.com/home?status=<?php echo urlencode(ucfirst($LongTitle));?>+http://<?php echo $SiteLink;?>/post-<?php echo $LmesId;?>-<?php echo $LikesLink;?>.html')"></a>

<a class="social-icons gpluf-button fa fa-google-plus page-social pull-left" href="javascript:void(0);" onclick="popup('https://plus.google.com/share?url=<?php echo $SiteLink;?>/post-<?php echo $LmesId;?>-<?php echo $LikesLink;?>.html')"></a>

<a class="social-icons pinterest-button fa fa-pinterest page-social pull-left"  href="javascript:void(0);" onclick="popup('http://pinterest.com/pin/create%2Fbutton/?url=http://<?php echo $SiteLink;?>/post-<?php echo $LmesId;?>-<?php echo $LikesLink;?>.html')"></a>
            

<a class="social-icons fa fa-search-plus page-social pull-right"  href="post-<?php echo $likesrow['id'];?>-<?php echo $LikesLink;?>.html" ></a>  
<a class="social-icons fa fa-link page-social pull-right"  href="views.php?id=<?php echo $likesrow['id'];?>" ></a>
          
            </div>
                    <h3><a href="post-<?php echo $likesrow['id'];?>-<?php echo $LikesLink;?>.html" target="_self"><?php echo $tlong;?></a></h3>  
					<p><?php echo $dlikeslong;?></p>
                    
       <div class="like-box">
            <div class='up'>
			<?php if(!isset($_SESSION['username'])){?>
			<a href="" class="like" data-id="<?php echo $LmesId;?>" data-name="up"><span class="likes two fa fa-heart"> <?php echo $Lup; ?></span></a>
			<?php }else{ ?>
			<?php if ($LVcRow == NULL){?>
			<a href="" class="like" data-id="<?php echo $LmesId;?>" data-name="up"><span class="likes two fa fa-heart"> <?php echo $Lup; ?></span></a>
			<?php }elseif ($LVcRow ==1) {?>
			<a href="" class="like" data-id="<?php echo $LmesId;?>" data-name="up"><span class="likes one fa fa-heart"> <?php echo $Lup; ?></span></a>
			<?php } }?>
	   <div class="tot-views fa fa-eye"> <?php echo $likesrow['tot'];?></div>
	   </div><!-- /up-->
            </div><!-- /like-box-->    
                </div><!-- /box -->
                
<?php } ?>

</div><!-- /.col-md-8 -->

</div><!-- /.content -->

<?php include("footer_pages.php");?>  