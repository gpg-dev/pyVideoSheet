<?php include("header_cat.php");

$upid = $mysqli->escape_string($_GET['id']);

	//user info

if($ThisUserSql = $mysqli->query("SELECT * FROM users WHERE id='$upid'")){

   $ThisUserRow = mysqli_fetch_array($ThisUserSql);
   
     $ThisUserSql->close();

}else{

     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");

}


?>

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

<div class="page-title"><h1><?php echo ucfirst($ThisUserRow['username']);?> Gallery</h1></div>

<div id="content" class="pull-container">

<?php

	$result = $mysqli->query("SELECT * FROM posts WHERE uid=$upid ORDER BY id DESC LIMIT 0, 20");
	
	while($row = mysqli_fetch_array($result))
  	{
		$long = stripslashes($row['description']);
		$strd = strlen ($long);
		if ($strd > 140) {
		$dlong = substr($long,0,137).'...';
		}else{
		$dlong = $long;}
		
		$LongTitle = stripslashes($row['title']);
		$strt = strlen ($LongTitle);
		if ($strt > 40) {
		$tlong = substr($LongTitle,0,37).'...';
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
                <div class="box">
                
               <?php if($Type==2){?><div class="play-button"><a href="post-<?php echo $row['id'];?>-<?php echo $PageLink;?>.html" target="_self"><span class="fa fa-video-camera"></span> Play</a></div><?php }else if($Type==3){?><div class="play-button"><a href="post-<?php echo $row['id'];?>-<?php echo $PageLink;?>.html" target="_self"><span class="fa fa-volume-up"></span> Play</a></div><?php }?> 
               
                <a href="views.php?id=<?php echo $row['id'];?>" target="_blank" title="<?php echo $row['title'];?>">
                               
                <img alt="<?php echo $row['title']; ?>" src="uploaded_images/<?php echo $row['image']; ?>" class="box-image" ></a>
                
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
</div><!-- /.content -->

		<nav id="page-nav">
  			<a href="data_user.php?id=<?php echo $upid;?>&page=2"></a>
		</nav>
     
		<script src="js/isotope.pkgd.min.js"></script>
		<script src="js/jquery.infinitescroll.min.js"></script>
        <script src="js/imagesloaded.pkgd.min.js"></script>

		
<script>
$(document).ready(function() {
 
var $container = $('#content')

$($container).imagesLoaded( function(){ 
$($container).isotope({
itemSelector: '.box',
resizable: false,

})
})
 
$container.infinitescroll({
navSelector: '#page-nav',
nextSelector: '#page-nav a',
itemSelector: '.box',
loading : {
msgText : 'Loading',
finishedMsg: 'No more posts to load.',
img: 'templates/<?php echo $settings['template'];?>/images/loading.gif'
}
},
function(newElements) {
	
$($container).imagesLoaded( function(){
$container.isotope('appended', $(newElements))
})


$(".like").unbind("click"); 
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

})
 
})
</script>

<?php include("footer.php");?>  