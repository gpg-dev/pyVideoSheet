
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

<?php include 'filter.php'; ?>

<div style="clear: both"></div>

<div id="content" class="main-container" style="top: 0;">
	<div class="main">
		<?php
		$typeCondition = '';
		if (!empty($_GET['filter'])) {
			$typeCondition = @$postType[$_GET['filter']];
		}
		if ($typeCondition) {
			$typeCondition = "type=$typeCondition";
		}

		$sortCondition = '';
		switch (@$_GET['sort']) {
			case 'month':
				$sortCondition = 'tot_month';
				break;
			case 'week':
				$sortCondition = 'tot_week';
				break;
			case 'new':
				$sortCondition = "id";
				break;
			default:
				$sortCondition = "RAND()";
				break;
		}
		$query = $sqlGetProduct($typeCondition, $sortCondition);
		$result = $mysqli->query($query);

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
			$PostId = $row[0];
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
			<div class="box">
				<?php
				$renderGeneralPost($row);
				$renderLikeBox($row);
				?>
			</div><!-- /box -->

		<?php } ?>
	</div>
</div><!-- /.content -->

<nav id="page-nav">
	<a href="data_index.php?page=2"></a>
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
		});

		$container.infinitescroll(
			{
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
				$($container).imagesLoaded( function()
				{
					$container.isotope('appended', $(newElements))
				});

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
			}
		)
	})
</script>

<?php include("footer.php");?>