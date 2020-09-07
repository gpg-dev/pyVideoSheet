<?php include("header.php");

$term = $mysqli->escape_string($_GET['term']);

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

<div class="page-title"><h1>Search for <?php echo $term;?></h1></div>

<div id="content" class="pull-container">

	<?php

	$query = $sqlGetProduct("(title like '%$term%' or description like '%$term%')");
	$result = $mysqli->query($query);

	$row_cnt = $result->num_rows;

	if($row_cnt < 1){ ?>

		<div class="col-md-5 col-sm-5 col-centered">


			<div class="panel panel-default">

				<div class="panel-body">
					<h3>Your search for "<span class="tt-text"><?php echo $term;?></span>" did not produce any results</h3>
					<ul class="search-again">
						<li>Make sure all words are spelled correctly</li>
						<li>Try different keywords</li>
						<li>Try more general keywords</li>
					</ul>
				</div>

			</div><!--panel panel-default-->

			<?php }elseif (empty($term)){ ?>

				<div class="no-results"><h3>Please enter keyword to search</h3></div>

			<?php }else{

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
			<a href="data_search.php?term=<?php echo $term;?>&page=2"></a>
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