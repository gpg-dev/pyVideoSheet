<?php include("header.php");?>
	<style type="text/css">
		.box-image {
			width: 10%;
			display: inline-block;
			text-align: center;
			vertical-align: middle;
		}
		.box-image input{
			margin-top: 10px;
		}
		.box-image img {
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
			<li class="active">Create Post</li>
		</ol>

		<div class="page-header">
			<h3>Create Post <small>Create Post</small></h3>
		</div>

		<script src="js/bootstrap-filestyle.min.js"></script>
		<script>
			$(function(){
				$(":file").filestyle({iconName: "glyphicon-picture", buttonText: "Select Photo"});
			});
		</script>

		<script type="text/javascript" src="js/jquery.form.js"></script>
		<script>
			$(document).ready(function() {
				$('#postCreate').on('submit', function(e) {
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

			function afterSuccess() {
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

					<div id="output"></div>

					<form id="postCreate" action="submit_create_post.php" enctype="multipart/form-data" method="post">

						<div class="form-group">
							<label for="inputCategory">Category</label>
							<select class="form-control" id="inputCategory" name="inputCategory">
								<option value="" disabled selected>Choose Category</option>
								<?php
								if($SelectCategories = $mysqli->query("SELECT id, cname FROM categories WHERE id!='$CatId' ORDER BY cname ASC")){

									while($categoryRow = mysqli_fetch_array($SelectCategories)){
										?>
										<option value="<?php echo $categoryRow['id'];?>"><?php echo $categoryRow['cname'];?></option>
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
							<input type="text" class="form-control" onchange="getWebsiteDataFromUrl($(this).val())" name="inputLink" id="inputLink" placeholder="Link to the original post or source url" value="">
						</div>

						<div class="form-group">
							<label for="inputTitle">Post Title</label>
							<br>
							<input type="text" class="form-control" name="inputTitle" id="inputTitle" placeholder="Add a title to your post" value="">
						</div>

						<div class="form-group">
							<label for="inputfile[]">Featured Image (choose the image to change image)</label>
							<div class="image-upload">
								<input type="file" id="inputfile[]" <?php echo $Settings['bulk_upload'] ? 'multiple' : '' ?> accept="image/png,image/jpeg,image/gif"
									   name="inputfile[]" class="filestyle" data-iconName="glyphicon-picture" data-buttonText="Select Image To Change">
							</div>
						</div>

						<div class="form-group" id="fetch-image"></div>

						<div class="form-group">
							<label for="type">Post Type</label>
							<select name="type" id="type" class="form-control">
								<?php foreach ($postType as $type => $value) { ?>
									<option value="<?php echo $value; ?>">
										<?php echo ucfirst($type); ?>
									</option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group embed-auto" style="display: none" >
							<label for="embed">Embed Code</label>
							<input id="embed" class="form-control" name="embed" value="">
						</div>

						<div class="form-group">
							<label for="inputDescription">Description</label>
							<textarea class="form-control" id="inputDescription" name="inputDescription" rows="3" placeholder="Enter a description for your post"></textarea>
						</div>

				</div><!-- panel body -->

				<div class="panel-footer clearfix">

					<button type="submit" id="submitButton" class="btn btn-default btn-success btn-lg pull-right">Submit post</button>
				</div><!--panel-footer clearfix-->

				</form>
			</div><!--panel panel-default-->

		</section>
	</section><!--col-md-10-->
<script>
	function getWebsiteDataFromUrl(url) {
		$.ajax({
			url: "/ajax_scrap.php",
			method: 'get',
			data: {url: url, multi: '<?php echo @$settings['bulk_upload']; ?>'},
			success: function (result) {
				var obj = JSON.parse(result);
				var objContent = $('#fetch-image');
				objContent.html(obj.htmlImg);
				$('#inputTitle').val(obj.title);
			}
		});
	}
</script>
<?php include("footer.php");?>