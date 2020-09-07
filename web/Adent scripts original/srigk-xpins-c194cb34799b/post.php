<?php include("header_post.php");?>
<style>
	#carousel-image .item img {
		/*max-height: 250px;*/
		height: 100%;
	}
	#carousel-image .item {
		/*max-height: 250px;*/
	}
</style>

<div id="content" class="main-container">

	<script type="text/javascript">
		$(function() {
			$(".vote").click(function()
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
						url: "up_vote.php",
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
		$(document).ready(function () {
			var item = '#carousel-image .item';
			$(item).css('height', $(item + '.active').height());
		});
	</script>

	<?php

	if($ThisUserSql = $mysqli->query("SELECT * FROM users WHERE id='$ThisUser'")){

		$ThisUserRow = mysqli_fetch_array($ThisUserSql);

		$ThisUserAvatar = $ThisUserRow['avatar'];

		$ThisUser = $ThisUserRow['username'];

		$ThisUserPro = preg_replace("![^a-z0-9]+!i", "-", $ThisUser);

		$ThisUserPro = strtolower($ThisUserPro);

		$ThisUserSql->close();
	}else{
		printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
	}

	//category info

	if($ThisCatSql = $mysqli->query("SELECT * FROM categories WHERE id='$ThisCid'")){

		$ThisCatRow = mysqli_fetch_array($ThisCatSql);

		$ThisCatSql->close();

	}else{

		printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

	}

	//URL info

	$parse = parse_url($ThisURL);
	$host = $parse['host'];
	$host = str_replace ('www.','', $host);


	//Check Votes

	if($VcSql= $mysqli->query("SELECT uid FROM favip WHERE postid='$id' and uid='$Uid'")){

		$VcRow = mysqli_num_rows($VcSql);

	}else{

		printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

	}

	?>

	<div class="container pull-col">

		<div class="col-md-8">

			<div class="panel panel-default">

				<div class="panel-body post-box">

					<div class="share-bar">

						<div class="pull-left">

						</div>

						<div class="pull-right" style="display: flex;">

							<div class="fb-like" data-href="http://<?php echo $SiteLink;?>/post-<?php echo $PostId;?>-<?php echo $PageLink;?>.html" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>

							<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://<?php echo $SiteLink;?>/post-<?php echo $PostId;?>-<?php echo $PageLink;?>.html">Tweet</a>

							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

							<?php if(!isset($_SESSION['username'])){?>
								<div class="vote two">
									<a href="" class="vote" data-id="<?php echo $PostId; ?>" data-name="up"><span class="likes two fa <?php echo $faIcon['like'] ?>"></span>&nbsp; <?php echo $up; ?></a>
								</div>
							<?php }else{ ?>
								<?php if ($VcRow == NULL){?>
									<a href="" class="vote" data-id="<?php echo $PostId; ?>" data-name="up"><span class="likes two fa <?php echo $faIcon['like'] ?>"></span>&nbsp; <?php echo $up; ?></a>
								<?php }elseif ($VcRow ==1) {?>
									<a href="" class="vote" data-id="<?php echo $PostId; ?>" data-name="up"><span class="likes one fa <?php echo $faIcon['like'] ?>"></span>&nbsp; <?php echo $up; ?></a>
								<?php }
							}
							?>

						</div>

					</div><!-- /.share-bar-->

					<h1><?php echo $PostRow['title'];?></h1>

					<?php
					if($Type==1) {
						$listImage = $getListImageFromPost($PostRow);
						?>
						<div id="carousel-image" class="carousel slide" data-ride="carousel">
							<!-- Indicators -->
							<ol class="carousel-indicators">

								<?php
								$count = 0;
								foreach ($listImage as $image) {
									?>
									<li data-target="#myCarousel" data-slide-to="<?php echo $count ?>" class="<?php echo $count++ == 0 ? 'active' : '' ?>"></li>
									<?php
								}
								?>
							</ol>

							<!-- Wrapper for slides -->
							<div class="carousel-inner">

								<?php
								$count = 0;
								foreach ($listImage as $image) {
									$imageLink = (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) ? $image : "uploaded_images/$image";
									?>
									<div class="item <?php echo $count++ == 0 ? 'active' : '' ?>" style="">
										<img src="<?php echo $imageLink; ?>?time=<?php echo date('ymdhis'); ?>" alt="<?php echo $PostRow['title']; ?>" width="613">
									</div>
									<?php
								}
								?>
							</div>

							<!-- Left and right controls -->

							<?php if (count($listImage) > 1) { ?>
								<a class="left carousel-control" href="#carousel-image" data-slide="prev">
									<span class="glyphicon glyphicon-chevron-left" style="margin-top: -20%;"></span>
									<span class="sr-only">Previous</span>
								</a>
								<a class="right carousel-control" href="#carousel-image" data-slide="next">
									<span class="glyphicon glyphicon-chevron-right" style="margin-top: -20%;"></span>
									<span class="sr-only">Next</span>
								</a>
							<?php } ?>

						</div>
						<?php
					}else{
						?>
						<div class="flex-embed widescreen">
							<?php echo $PostRow['embed'];?>
						</div>
					<?php } ?>

					<p><?php echo  nl2br($PostRow['description']);?></p>

				</div>
			</div>


			<?php if(!empty($Ad3)){ ?>
				<div class="panel panel-default">
					<div class="panel-body">
						<?php echo $Ad3;?>
					</div>
				</div><!-- /.panel panel-default -->
			<?php }?>

			<?php

			include "include/commments.class.php";

			$comments = array();
			$result = $mysqli->query("SELECT * FROM comments WHERE postid='$id' ORDER BY id DESC");

			while($row = mysqli_fetch_assoc($result))
			{
				$comments[] = new Comment($row);
			}

			?>

			<div class="panel panel-default">

				<div class="panel-body post-box">

					<h1>Lest Us Know What You Think</h1>

					<div id="main">

						<div id="addCommentContainer">

							<?php
							if(!isset($_SESSION['username'])){?>
								<div class="slog-reg">Please <a class="login-box" href="login.html">login</a> or <a href="register.html">register</a> to submit a comment</div>
							<?php }else{

								if (empty($Uavatar)){
									$AvatarImg =  'templates/'.$settings['template'].'/images/default-avatar.png" class="img-responsive"';
								}elseif (!empty($Uavatar)){
									$AvatarImg =  'avatars/'.$Uavatar;
								}

								?>

								<div class="comment-form">
									<form id="addCommentForm" method="post" action="">
										<div>
											<input type="hidden" name="name" id="name" value="<?php echo $Uname;?>" />

											<input type="hidden" name="ruid" id="ruid" value="<?php echo $Uid;?>" />

											<input type="hidden" name="avatarlink" id="avatarlink" value="<?php echo $AvatarImg;?>" />

											<input type="hidden" name="sid" id="sid" value="<?php echo $id;?>" />

											<input type="hidden" name="email" id="email" value="<?php echo $ThisUserRow['email'];?>"/>
											<div class="form-group">

												<textarea name="body" id="body" class="form-control"></textarea>

											</div>

											<input type="submit" id="submit" class="btn btn-default btn-info btn-lg pull-right" value="Submit" /></label>
										</div>
									</form>
								</div>
							<?php }?>
						</div>

					</div>
				</div>
			</div>

			<div id="jr"></div>

			<script type="text/javascript" src="js/jquery.comments.js"></script>

			<?php

			if($CmtSql = $mysqli->query("SELECT * FROM comments LEFT JOIN users ON comments.uid=users.id WHERE comments.postid=$id ORDER BY comments.id DESC LIMIT 0, 20")){

				while ($CmtRow = mysqli_fetch_array($CmtSql)){

					$name=$CmtRow['name'];
					$comment_dis=$CmtRow['body'];
					$comment_date=$CmtRow['dt'];
					$comment_avatar=$CmtRow['avatar'];

					?>

					<div class="comments-box">
						<div class="media">

							<?php	if (empty($comment_avatar)){
								echo  '<img src="templates/'.$settings['template'].'/images/default-avatar.png" width="64" class="media-object">';
							}elseif (!empty($comment_avatar)){

								echo  '<img src="timthumb.php?src=http://'.$settings['siteurl'].'/avatars/'.$comment_avatar.'&amp;h=64&amp;w=64&amp;q=100" class="media-object" alt="'.$name.'"/>';
							}?>

							<div class="media-body">
								<h4 class="media-heading"><?php echo ucfirst($name);?><small><i> Posted <?php echo $comment_date;?></i></small></h4>
								<p><?php echo $comment_dis;?></p>
							</div>
						</div>
					</div>

					<?php
				}
				$CmtSql->close();

			}else{

				printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

			}

			?>


		</div><!-- /.col-md-8 -->

		<div class="col-md-4">

			<div class="right-box user-info">
				<div class="avatar-img">
					<?php if (empty($ThisUserAvatar)){ ?>
						<img src="templates/<?php echo $settings['template'];?>/images/default-avatar.png" class="img-responsive" alt="<?php echo $ThisUser;?>" width="64" height="64">
					<?php }elseif (!empty($ThisUserAvatar)){?>

						<img src="timthumb.php?src=http://<?php echo $settings['siteurl'];?>/avatars/<?php echo $ThisUserAvatar;?>&amp;h=64&amp;w=64&amp;q=100" alt="<?php echo $ThisUser;?>" class="img-responsive"/>

					<?php }?>
				</div><!--avatar-img-->
				<div class="profile-user"><h2><a href="profile-<?php echo $ThisUserRow['id'];?>-<?php echo $ThisUserPro;?>.html"><?php echo ucfirst($ThisUserRow['username']);?></a></h2></div>
				<div class="profile-user"><p>Member Since <?php echo $ThisUserRow['reg_date'];?></p></div>
			</div><!--r-box-->

			<div class="right-box">

				<div class="panel-body img-box">

					<h1>You may also like</h1>

					<?php
					if($RandomPosts = $mysqli->query("SELECT * FROM posts WHERE catid=$ThisCid ORDER BY RAND() LIMIT 8")){

						while($RandomRow = mysqli_fetch_array($RandomPosts)){

							$RandTitle = substr($RandomRow['title'], 0, 50);
							$RandTitle != $RandomRow['title'] && $RandTitle .= '...';
							$RndLink = preg_replace("![^a-z0-9]+!i", "-", $RandTitle);
							$RndLink = strtolower($RndLink);

							?>
							<div class="img-gride">
								<a href="post-<?php echo $RandomRow['id'];?>-<?php echo $RndLink;?>.html">
									<img src="<?php echo $settings['siteurl'];?>uploaded_images/<?php echo $RandomRow['image'];?>" alt="<?php echo $RandTitle;?>" class="img-responsive"/>
									<span><?php echo $RandTitle;?></span>
								</a>
							</div><!-- /.col-md-4.col-sm-4-->
							<?php
						}
						$RandomPosts->close();

					}else{

						printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
					}
					?>

				</div>
			</div>

			<div class="right-box">
				<!--<div class="l-info">Post:</div><div class="r-info">--><?php //echo $PostRow['date'];?><!--</div>-->
				<div class="l-info">Category:</div><div class="r-info"><?php echo $ThisCatRow['cname'];?></div>
				<div class="l-info">Total Views:</div><div class="r-info"><?php echo $PostRow['hits'];;?></div>
				<!--<div class="l-info">Total Link Visits:</div><div class="r-info">--><?php //echo $PostRow['link_hits'];;?><!--</div>-->
				<div class="l-info">Found on:</div><div class="r-info"><a href="views.php?id=<?php echo $PostRow['id'];?>" target="_blank" title="<?php echo $PostRow['title'];?>"><?php echo $host;?></a></div>
			</div><!--r-box-->

			<?php if(!empty($Ad1)){ ?>
				<div class="right-box col-remove-display">
					<?php echo $Ad1;?>
				</div><!--r-box-->
			<?php }?>

			<?php if(!empty($FbPage)){ ?>
				<div class="right-box col-remove-display">
					<div class="fb-like-box" data-href="<?php echo $FbPage;?>" data-width="292" data-show-faces="false" data-stream="false" data-show-border="false" data-header="false"></div>
				</div><!--r-box-->
			<?php }?>

			<?php if(!empty($Ad2)){ ?>
				<div id="ad-scroll" class="right-box col-remove-display">
					<?php echo $Ad2;?>
				</div><!--r-box-->
			<?php }?>

		</div><!-- /.col-md-4 -->

	</div><!-- /.container -->

</div><!-- /.content -->

<?php include("footer_pages.php");?>