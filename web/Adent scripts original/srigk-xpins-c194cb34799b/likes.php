<?php include("header.php");?>

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

<?php
if(!isset($_SESSION['username'])){?>
	<script type="text/javascript">
		function leave() {
			window.location = "index.html";
		}
		setTimeout("leave()", 2);
	</script>
<?php }else{?>

	<div class="page-title"><h1>your likes collection</h1></div>

	<div id="content" class="pull-container">

		<?php

		$result = $mysqli->query("SELECT * FROM favip LEFT JOIN posts ON favip.postid=posts.id $sqlLeftJoinCategoryPost WHERE favip.uid=$Uid AND posts.active=1 ORDER BY favip.id DESC LIMIT 0, 20");

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

				if($VcSql= $mysqli->query("SELECT uid FROM favip WHERE postid='$PostId' AND uid='$Uid'")){

					$VcRow = mysqli_num_rows($VcSql);

				}else{

					printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");

				}

			}
			?>
			<div class="box">
				<?php
				$renderGeneralPost($row);
				$renderLikeBox($row);
				?>
			</div><!-- /box -->

		<?php } ?>
	</div><!-- /.content -->

	<nav id="page-nav">
		<a href="data_likes.php?cid=<?php echo $cid;?>&page=2"></a>
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

<?php } include("footer.php");?>