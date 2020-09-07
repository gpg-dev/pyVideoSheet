<?php

error_reporting(0);
session_start();

include("db.php");
include("constant.php");

if($squ = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

	$settings = mysqli_fetch_array($squ);

	$FbPage = $settings['fbpage'];

	$SiteLink = $settings['siteurl'];

	$SiteTitle = $settings['name'];

	$squ->close();

}else{

	printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
}

//Ads

if($AdsSql = $mysqli->query("SELECT * FROM siteads WHERE type='post'")){

	$AdsRow = mysqli_fetch_array($AdsSql);

	$Ad1 = stripslashes($AdsRow['ad1']);
	$Ad2 = stripslashes($AdsRow['ad2']);
	$Ad3 = stripslashes($AdsRow['ad3']);

	$AdsSql->close();

}else{

	printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
}


$id = $mysqli->escape_string($_GET['id']);

if($PostSql = $mysqli->query("SELECT * FROM posts WHERE id='$id'")){

	$PostRow = mysqli_fetch_array($PostSql);

	$ThisTitle = stripslashes($PostRow['title']);

	$ThisUser = $PostRow['uid'];

	$ThisURL = $PostRow['url'];

	$ThisCid = $PostRow['catid'];

	$PostId = $PostRow['id'];

	$up = $PostRow['likes'];

	$Type = $PostRow['type'];

	$long = $PostRow['description'];
	$strd = strlen ($long);
	if ($strd > 160) {
		$dlong = stripslashes(substr($long,0,160)).'...';
	}else{
		$dlong = stripslashes($long);
	}

	$PageLink = preg_replace("![^a-z0-9]+!i", "-", $ThisTitle);
	$PageLink = strtolower($PageLink);
	$PageLink = strtolower($PageLink);

	$PostSql->close();

}else{

	printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

}

//Tot Views
$tot_month = $PostRow['tot_month'] % 12 + 1;
$month_tot_month = $PostRow['month_tot_month'];
$current = new DateTime();
if ($current->format('m') != $month_tot_month) {
	$tot_month = 1;
	$month_tot_month = ($current->format('m'));
}

$tot_week = $PostRow['tot_week'] + 1;
$date_tot_week = $PostRow['date_tot_week'];
$current = new DateTime();
if ($current->format('w') == 0 && $current->format('Y-m-d') != $date_tot_week || $date_tot_week = '0000-00-00') {
	$tot_week = 1;
	$date_tot_week = (new DateTime())->format('Y-m-d');
} else {
	$tot_week++;
}

$update = "SET hits=hits+1, tot=tot+1, ";
$update .= "tot_month = $tot_month, month_tot_month = $month_tot_month, ";
$update .= "tot_week = $tot_week, date_tot_week = '$date_tot_week'";
$query = "UPDATE posts $update WHERE id='$id'";
$mysqli->query($query);

//Post Page


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

//Tot Site Views
$mysqli->query("UPDATE settings SET site_hits=site_hits+1 WHERE id='1'");

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $ThisTitle;?> | <?php echo $settings['name']; ?></title>
	<meta name="description" content="<?php echo $dlong; ?>" />
	<meta name="keywords" content="<?php echo $settings['keywords']; ?>" />

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!--Facebook Meta Tags-->
	<meta property="fb:app_id"          content="<?php echo $settings['fbpage']; ?>" />
	<meta property="og:url"             content="http://<?php echo $SiteLink;?>/post-<?php echo $PostId;?>-<?php echo $PageLink;?>.html" />
	<meta property="og:title"           content="<?php echo $ThisTitle;?> | <?php echo $settings['name']; ?>" />
	<meta property="og:description" 	content="<?php echo $dlong; ?>" />
	<meta property="og:image"           content="http://<?php echo $SiteLink; ?>/uploaded_images/<?php echo $PostRow['image'];?>" />
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
	<style>
		.navbar {border-bottom: solid 2px darkred}
		body {  background: #fff !important;  }
		#wrap { padding-top: 5px; background: #fff; }
		.box { border-radius: 0 !important; }
		.search-group .glyphicon-search { padding: 3px }
		.search-group button, .search-group button, .filter-sort button, .filter-sort ul, .right-box, .panel { border-radius: 0; }
		#content { background: #fff }
		.alert {  display: none;  }
	</style>

	<script>

		$(document).ready(function(){
			$('img').each(function () {
				var url = $(this).attr('src');
				url = url.replace('http://http', 'http');
				url = url.replace('https://http', 'http');
				$(this).attr('src', url);
			})

			$().UItoTop({ easingType: 'easeOutQuart' });

			setTimeout(function () {
				$('.alert').css('display', 'block');
			}, 1000);

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

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Post <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li>
								<a href="submit_new.html">Write a post</a>
							</li>
							<li class="dropdown sub-level">
								<a href="#" onclick="openSub(event)" class="dropdown-toggle" data-toggle="dropdown">Filter By Category »</span></a>
								<ul class="dropdown-menu" id="dp1" role="menu">
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
										printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
									}
									?>

								</ul>
							</li>
							<li class="dropdown sub-level">
								<a href="#" onclick="openSub(event)" class="dropdown-toggle" data-toggle="dropdown">Filter By Type »</a>
								<ul class="dropdown-menu" id="dp2" role="menu">
									<li><a href="popular.html">Popular</a></li>
									<li><a href="videos.html">Videos</a></li>
									<li><a href="audios.html">Audios</a></li>
								</ul>
							</li>
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
						</ul>
					</li>

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Social <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?php echo $settings['fbpage'];?>"><span class="fa fa-facebook-square"></span>&nbsp;&nbsp;Facebook</a></li>
							<li><a href="<?php echo $settings['twitter'];?>"><span class="fa fa-twitter-square"></span>&nbsp;&nbsp;Twitter</a></li>
							<li><a href="<?php echo $settings['google_plus'];?>"><span class="fa fa-google-plus-square"></span>&nbsp;&nbsp;Google+</a></li>
							<li><a href="<?php echo $settings['pinterest'];?>"><span class="fa fa-pinterest-square"></span>&nbsp;&nbsp;Pinterest</a></li>
							<li><a href="feeds.html"><span class="fa fa-rss-square"></span>&nbsp;&nbsp;Rss Feeds</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-left">
					<?php if(!isset($_SESSION['username'])){?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Login / Register <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="login.html">Login</a></li>
								<li><a href='register.html'>Register</a></li>
							</ul>
						</li>
					<?php }else{

						if($NewMessages = $mysqli->query("SELECT * FROM users WHERE id='$Uid'")){

							$NewMsgsRow = mysqli_fetch_array($NewMessages);

							$CountNewMsgs = $NewMsgsRow['notifications'];

							$NewMessages->close();

						}else{
							$CountNewMsgs = 0;
							printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
						}
						?>

						<li class="dropdown <?php if($pageName ==  '/likes.html'){ echo 'class="active"'; } if($pageName ==  '/your_posts.html'){ echo 'class="active"'; }?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo ucfirst($Uname);?> <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a class="" href="messages.html">
										New messages
										<?php  if($CountNewMsgs > 0){ ?>
											<span class="msg-notification pink"><?php echo $CountNewMsgs;?></span>
										<?php }?>
									</a>
								</li>
								<li><a href="profile-<?php echo $Uid;?>-<?php echo strtolower($Uname);?>.html">Profile</a></li>
								<li><a href="your_posts.html">Your Posts</a></li>
								<li><a href="likes.html">Your Likes</a></li>
								<li><a href="edit_profile.html">Edit Your Profile</a></li>
							</ul>
						</li>
						<li><a href="logout.html">Logout</a></li>
					<?php } ?>
				</ul>

				<form class="navbar-form navbar-right search-group" role="search" method="get" action="search.php">
					<div class="input-group add-on">
						<input type="text" style="height: 36px; border-radius: 0" class="form-control" id="term" name="term" placeholder="Search">

						<div class="input-group-btn">
							<button class="btn btn-default btn-search" type="submit"><i class="glyphicon glyphicon-search"></i></button>
						</div>
					</div>
				</form>

			</div>
			<!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>

	<div id="freeow" class="freeow freeow-top-right"></div>