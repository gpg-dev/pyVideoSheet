<?php include("header.php");?>

	<style type="text/css">
		.image-upload .box-image {
			width: 30%;
			display: inline-block;
			text-align: center;
			vertical-align: middle;
		}
		.image-upload .box-image input{
			margin-top: 10px;
		}
		.image-upload .box-image img {
			max-width: 100%;
			display: block;
			margin: auto;
		}
	</style>

	<section class="col-md-2">

		<?php include("left_menu.php");?>

	</section><!--col-md-2-->

	<section class="col-md-10">

		<ol class="breadcrumb">
			<li>Admin CP</li>
			<li>Posts</li>
			<li class="active">Edit Post</li>
		</ol>

		<div class="page-header">
			<h3>Edit Post <small>Edit/update Post</small></h3>
		</div>

		<script src="js/bootstrap-filestyle.min.js"></script>
		<script>
			$(function(){
				$(":file").filestyle({iconName: "glyphicon-picture", buttonText: "Select Photo"});
			});
		</script>

		<script type="text/javascript" src="js/jquery.form.js"></script>
		<script>
			$(document).ready(function()
			{
				$('#postUpdator').on('submit', function(e)
				{
					e.preventDefault();
					$('#submitButton').attr('disabled', ''); // disable upload button
					//show uploading message
					$("#output").html('<div class="alert alert-info" role="alert">Working.. Please wait..</div>');

					$(this).ajaxSubmit({
						target: '#output',
						success:  afterSuccess //call function after success
					});
				});

				$('select[name="type"]').on('change', function (e) {
					var val = $(e.target).val();
					var display = 'none';
					if (val.trim() === "<?php echo $postType['video'] ?>" ) {
						display = 'block';
					}
					$('.embed-auto').css('display', display);
				});
			});

			function afterSuccess()
			{

				$('#submitButton').removeAttr('disabled'); //enable submit button

			}

			function showTypeGet(show, hide, that) {
				$(show + $(that).val()).css('display', 'inline-block');
				$(hide).css('display', 'none');
			}
		</script>

		<section class="col-md-8">

			<div class="panel panel-default">

				<div class="panel-body">

					<?php

					$id = $mysqli->escape_string($_GET['id']);

					if($Post = $mysqli->query("SELECT * FROM posts WHERE id='$id'")){

						$PostRow = mysqli_fetch_array($Post);

						$CatId = $PostRow['catid'];

						$Post->close();

					}else{

						printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
					}


					if($Cat = $mysqli->query("SELECT * FROM categories WHERE id='$CatId'")){

						$CatRow = mysqli_fetch_array($Cat);

						$Cat->close();

					}else{

						printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
					}


					?>

					<div id="output"></div>

					<form id="postUpdator" action="update_post.php?id=<?php echo $id;?>" enctype="multipart/form-data" method="post">

						<div class="form-group">
							<label for="inputCategory">Category</label>
							<select class="form-control" id="inputCategory" name="inputCategory">

<!--								<option value="--><?php //echo $CatRow['id'];?><!--">--><?php //echo $CatRow['cname'];?><!--</option>-->

								<option value="" disabled selected>Choose Category</option>

								<?php
								if($SelectCategories = $mysqli->query("SELECT id, cname FROM categories ORDER BY cname ASC")){

									while($categoryRow = mysqli_fetch_array($SelectCategories)){

										?>
										<option value="<?php echo $categoryRow['id'];?>" <?php if ($CatId == $categoryRow['id']) echo 'selected' ?>>
											<?php echo $categoryRow['cname'];?>
										</option>
										<?php

									}

									$SelectCategories->close();

								}else{

									printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<label for="inputLink">Link</label>
							<input type="text" class="form-control" name="inputLink" id="inputLink" placeholder="Link to the original post or source url" value="<?php echo $PostRow['url'];?>">
						</div>
						<?php $htmlInfo = $getHtmlInfo($PostRow['url']); ?>

						<div class="form-group">
							<label for="inputTitle">Post Title</label>
							<br>
							<label>
								<input type="radio" name="getTitle" checked value="inputTitle" onclick="showTypeGet('#', '#urlTitle', this)">
								Title User inputted
							</label>

							<label>
								<input type="radio" name="getTitle" value="urlTitle" onclick="showTypeGet('#', '#inputTitle', this)">
								Title from given Url
							</label>
							<br>
							<input type="text" class="form-control" style="display: none" name="urlTitle" id="urlTitle" placeholder="Add a title to your post" value="<?php echo $htmlInfo['title'];?>">
							<input type="text" class="form-control" name="inputTitle" id="inputTitle" placeholder="Add a title to your post" value="<?php echo $PostRow['title'];?>">
						</div>

						<div class="form-group">
							<label for="inputfile">Featured Image (choose the image to change image)</label>
							<div class="image-upload">
								<label>
									<input type="radio" name="getImage" value="user" checked onclick="showTypeGet('.', '.url', this)">
									User Upload
								</label>
								<label>
									<input type="radio" name="getImage" value="url" onclick="showTypeGet('.', '.user', this)">
									Images fetch from given link
								</label>
								<br>
								<label class="box-image user">
									<?php $listImage = explode(',', $PostRow['images']); ?>
									<?php foreach ($listImage as $image) { ?>
										<?php $linkImg = strpos('|'.$image , 'http') == 1 ? $image : "/uploaded_images/$image" ?>
										<label class="box-image user">
											<img src="<?php echo $linkImg ?>">
											<input type="radio" name="image-link" value="<?php echo $image ?>" <?php echo $PostRow['image'] == $image ? 'checked' : ''?>>
										</label>
									<?php } ?>
								</label>
								<?php foreach ($htmlInfo['images'] as $image) { ?>
									<label class="box-image url" style="display: none">
										<img src="<?php echo $image ?>">
										<input type="radio" name="image-link" value="<?php echo $image ?>">
									</label>
								<?php } ?>
							</div>
							<!--							<input type="file" id="inputfile"-->
							<!--								   name="inputfile" class="filestyle" data-iconName="glyphicon-picture" data-buttonText="Select Image To Change">-->
						</div>

						<div class="form-group">
							<label for="type">Post Type</label>
							<select name="type" id="type" class="form-control">
								<?php foreach ($postType as $type => $value) { ?>
									<option value="<?php echo $value; ?>" <?php echo $PostRow['type'] == $value ? 'selected="selected"' : '';?>>
										<?php echo ucfirst($type); ?>
									</option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group embed-auto" style="<?php echo !in_array($PostRow['type'], [$postType['video']]) ? 'display: none' : '' ?>" >
							<label for="embed">Embed Code</label>
							<?php
//							$src = explode('src="', empty($PostRow['embed']) ? '' : $PostRow['embed']);
//							$str = count($src) > 1 ? explode('"', $src[1])[0] : '';
							?>
							<input id="embed" class="form-control" name="embed"
								   value="<?php echo urldecode(htmlentities($PostRow['embed'])) ?>">
<!--								   value="--><?php //echo in_array($PostRow['type'], [$postType['video']]) ? urldecode(htmlentities($str)) : '' ?><!--">-->
						</div>

						<div class="form-group">
							<label for="inputDescription">Description</label>
							<textarea class="form-control" id="inputDescription" name="inputDescription" rows="3" placeholder="Enter a description for your post"><?php echo $PostRow['description'];?></textarea>
						</div>

				</div><!-- panel body -->

				<div class="panel-footer clearfix">

					<button type="submit" id="submitButton" class="btn btn-default btn-success btn-lg pull-right">Update Post</button>

				</div><!--panel-footer clearfix-->

				</form>


			</div><!--panel panel-default-->

		</section>

	</section><!--col-md-10-->

<?php include("footer.php");?>