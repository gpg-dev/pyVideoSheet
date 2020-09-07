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

		$query = $sqlGetProduct("uid=$upid");
		$result = $mysqli->query($query);

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

					printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

				}

			}
			?>
			<div class="box-profile">
				<?php
				$renderGeneralPost($row);
				$renderLikeBox($row);
				?>
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

					printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

				}

			}


			?>

			<div class="box-profile">
				<?php
				$renderGeneralPost($row);
				$renderLikeBox($row);
				?>
			</div><!-- /box -->

		<?php } ?>

	</div><!-- /.col-md-8 -->

</div><!-- /.content -->

<?php include("footer_pages.php");?>