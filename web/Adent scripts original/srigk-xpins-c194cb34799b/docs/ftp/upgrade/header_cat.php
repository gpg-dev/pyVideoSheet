<?php 
session_start();

include("db.php");

error_reporting(E_ALL^E_NOTICE);

if($squ = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $settings = mysqli_fetch_array($squ);
	
	$FbPage = $settings['fbpage'];
	
	$SiteLink = $settings['siteurl'];
	
	$SiteTitle = $settings['name'];

    $squ->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

//Ads

if($AdsSql = $mysqli->query("SELECT * FROM siteads WHERE id='1'")){

    $AdsRow = mysqli_fetch_array($AdsSql);
	
	$Ad1 = stripslashes($AdsRow['ad1']);
	$Ad2 = stripslashes($AdsRow['ad2']);
	$Ad3 = stripslashes($AdsRow['ad3']);

    $AdsSql->close();

}else{
	
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}


//Get cat

$cid = $mysqli->escape_string($_GET['cid']);

if($GetCN = $mysqli->query("SELECT * FROM categories WHERE id='$cid'")){

    $CNRow = mysqli_fetch_array($GetCN);

    $GetCN->close();

}else{

     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");

}


if(isset($_SESSION['username'])){

	
$Uname = $_SESSION['username'];

if($UserSql = $mysqli->query("SELECT * FROM users WHERE username='$Uname'")){

    $UserRow = mysqli_fetch_array($UserSql);

	$Uid = $UserRow['id'];
	
	$Uavatar = $UserRow['avatar'];

    $UserSql->close();
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

}

//Tot Site Views
$mysqli->query("UPDATE settings SET site_hits=site_hits+1 WHERE id='1'");

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $CNRow['cname'];?> Gallery | <?php echo $settings['name']; ?></title>
<meta name="description" content="<?php echo $CNRow['description']; ?>" />
<meta name="keywords" content="<?php echo $settings['keywords']; ?>" />

<meta name="viewport" content="width=device-width, initial-scale=1">

<!--Facebook Meta Tags-->
<meta property="fb:app_id"          content="<?php echo $settings['fbpage']; ?>" /> 
<meta property="og:url"             content="http://<?php echo $SiteLink; ?>" /> 
<meta property="og:title"           content="<?php echo $CNRow['cname'];?> Gallery | <?php echo $settings['name']; ?>" />
<meta property="og:description" 	content="<?php echo $CNRow['description'];?>" /> 
<meta property="og:image"           content="http://<?php echo $SiteLink; ?>/images/logo.png" /> 
<!--End Facebook Meta Tags-->

<link href="favicon.ico" rel="shortcut icon" type="image/x-icon"/>

<link href="templates/<?php echo $settings['template'];?>/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="templates/<?php echo $settings['template'];?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="templates/<?php echo $settings['template'];?>/css/style.css" rel="stylesheet" type="text/css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="js/jquery.min.js"></script>	
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.ui.totop.min.js"></script>
<script src="js/jquery.freeow.min.js"></script>
<script src="js/easing.js"></script>

<script>

$(document).ready(function(){
	
$().UItoTop({ easingType: 'easeOutQuart' });

})

</script>

<script>
function popup(e){var t=700;var n=400;var r=(screen.width-t)/2;var i=(screen.height-n)/2;var s="width="+t+", height="+n;s+=", top="+i+", left="+r;s+=", directories=no";s+=", location=no";s+=", menubar=no";s+=", resizable=no";s+=", scrollbars=no";s+=", status=no";s+=", toolbar=no";newwin=window.open(e,"windowname5",s);if(window.focus){newwin.focus()}return false}
</script>
</head>

<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div id="wrap">

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.html"><img src="images/logo.png" class="logo" alt="<?php echo $SiteTitle;?>"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <?php if($pageName ==  '/'){ echo 'class="active"'; } if($pageName ==  '/index.html'){ echo 'class="active"'; }?>><a href="index.html">Home</a></li>
                
        <li class="dropdown <?php if($pageName ==  '/popular.html'){ echo 'class="active"'; } if($pageName ==  '/videos.html'){ echo 'class="active"'; } if($pageName ==  '/audios.html'){ echo 'class="active"'; }?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Explore <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="popular.html">Popular</a></li>
            <li><a href="videos.html">Videos</a></li>
            <li><a href="audios.html">Audios</a></li>   
          
          </ul>
        </li>
        
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categories <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
         <?php
if($mCategories = $mysqli->query("SELECT * FROM categories ORDER BY cname ASC")){

    while ($mcRow = mysqli_fetch_array($mCategories)){
		$mUrl = $mcRow['cname'];
		$mUrl = preg_replace("![^a-z0-9]+!i", "-", $mUrl);
		$mUrl = urlencode(strtolower($mUrl));
		
?>           
            <li><a href="cat-<?php echo $mcRow['id'];?>-<?php echo $mUrl;?>.html"><?php echo $mcRow['cname'];?></a></li>
<?php     
	}
$mCategories->close();
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}
?>                        
          
          </ul>
        </li>
        
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">More <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="aboutus.html">About Us</a></li>
            <li><a href="privacy.html">Privacy Policy</a></li>
            <li><a href="tos.html">Terms of Use</a></li>   
          	<li><a href="dmca.html">DMCA Policy</a></li> 
            <li><a href="contactus.html">Contact Us</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo $settings['fbpage'];?>"><span class="fa fa-facebook-square"></span>&nbsp;&nbsp;Facebook</a></li>
            <li><a href="<?php echo $settings['twitter'];?>"><span class="fa fa-twitter-square"></span>&nbsp;&nbsp;Twitter</a></li>   
          	<li><a href="<?php echo $settings['google_plus'];?>"><span class="fa fa-google-plus-square"></span>&nbsp;&nbsp;Google+</a></li> 
            <li><a href="<?php echo $settings['pinterest'];?>"><span class="fa fa-pinterest-square"></span>&nbsp;&nbsp;Pinterest</a></li>
             <li><a href="feeds.html"><span class="fa fa-rss-square"></span>&nbsp;&nbsp;Rss Feeds</a></li>
          </ul>
        </li>
        
      <li <?php if($pageName ==  '/submit_new.html'){ echo 'class="active"'; }?>><a href="submit_new.html">Add +</a></li>  
      </ul>
    
      <form class="navbar-form navbar-left" role="search" method="get" action="search.php">
        <div class="input-group add-on">
          <input type="text" class="form-control" id="term" name="term" placeholder="Search">
        
        <div class="input-group-btn">
        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
      </div>
      </div>
      </form>
 
      <ul class="nav navbar-nav navbar-right">
       <?php if(!isset($_SESSION['username'])){?>
   <li><a href="login.html">Login</a></li>
   <li><a href='register.html'>Register</a></li>
   <?php }else{ 
   
   if($NewMessages = $mysqli->query("SELECT * FROM users WHERE id='$Uid'")){
	
   $NewMsgsRow = mysqli_fetch_array($NewMessages);
   
   $CountNewMsgs = $NewMsgsRow['notifications'];    
     
   
   ?>
   	<li><a class="glyphicon glyphicon-comment msg-icon" href="messages.html"><?php  if($CountNewMsgs>0){ ?><span class="msg-notification pink"><?php echo $CountNewMsgs;?></span><?php }?></a></li>	
<?php
   
   $NewMessages->close();
   
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}   
?>
   
        <li class="dropdown <?php if($pageName ==  '/likes.html'){ echo 'class="active"'; } if($pageName ==  '/your_posts.html'){ echo 'class="active"'; }?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo ucfirst($Uname);?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
          	<li><a href="profile-<?php echo $Uid;?>-<?php echo strtolower($Uname);?>.html">Profile</a></li>
            <li><a href="your_posts.html">Your Posts</a></li>
            <li><a href="likes.html">Your Likes</a></li>
            <li><a href="edit_profile.html">Edit Your Profile</a></li>
          </ul>
        </li>
                <li><a href="logout.html">Logout</a></li>
         <?php } ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div id="freeow" class="freeow freeow-top-right"></div>