<?php include("header.php");?>

	<section class="col-md-2">

		<?php include("left_menu.php");?>

	</section><!--col-md-2-->

	<section class="col-md-10">

		<ol class="breadcrumb">
			<li>Admin CP</li>
			<li class="active">Advertisements</li>
		</ol>

		<div class="page-header">
			<h3>Advertisements <small>Update website advertisements</small></h3>
		</div>

		<script type="text/javascript" src="js/jquery.form.js"></script>

		<script>
			$(document).ready(function()
			{
				$('#adsForm').on('submit', function(e)
				{
					e.preventDefault();
					$('#submitButton').attr('disabled', ''); // disable upload button
					//show uploading message
					$("#output").html('<div class="alert alert-info" role="alert">Submitting.. Please wait..</div>');

					$(this).ajaxSubmit({
						target: '#output',
						success:  afterSuccess //call function after success
					});
				});
			});

			function afterSuccess()
			{

				$('#submitButton').removeAttr('disabled'); //enable submit button

			}
		</script>

		<section class="col-md-8">

			<div class="panel panel-default">

				<div class="panel-body">

					<?php
					$type = @$_GET['page'] == 'post' ? 'post' : 'list';

					if($Ads = $mysqli->query("SELECT * FROM siteads where type='$type'")){

						$AdRow = mysqli_fetch_array($Ads);

						$Ads->close();

					}
					?>

					<div id="output"></div>

					<form id="adsForm" action="update_ads.php" method="post">

						<input hidden name="type" value="<?php echo $AdRow['type'] ?>">
						<input hidden name="id" value="<?php echo $AdRow['id'] ?>">
						<?php if($type == 'post') { ?>
						<div class="form-group">
							<label for="inputAd1">HTML/JavaScript Based Advertisements</label>
							<textarea class="form-control" id="inputAd1" name="inputAd1" rows="3" placeholder="(300 x 250) or Responsive advertisement code"><?php echo $AdRow['ad1']?></textarea>
						</div>

						<div class="form-group">
							<label for="inputAd2">HTML/JavaScript Based Advertisements</label>
							<textarea class="form-control" id="inputAd2" name="inputAd2" rows="3" placeholder="(300 x 250) or Responsive advertisement code"><?php echo $AdRow['ad2']?></textarea>
						</div>

						<div class="form-group">
							<label for="inputAd3">HTML/JavaScript Based Advertisements</label>
							<textarea class="form-control" id="inputAd3" name="inputAd3" rows="3" placeholder="(600 x 90) or Responsive advertisement code"><?php echo $AdRow['ad3']?></textarea>
						</div>
						<?php } else { ?>
							<div class="form-group">
								<label for="inputAd1">HTML/JavaScript Based Advertisements</label>
								<textarea class="form-control" id="inputAd1" name="inputAd1" rows="3" placeholder="(250 x 250) or Responsive advertisement code"><?php echo $AdRow['ad1']?></textarea>
							</div>
							<div class="form-group">
								<label for="inputAd2">HTML/JavaScript Based Advertisements</label>
								<textarea class="form-control" id="inputAd2" name="inputAd2" rows="3" placeholder="(250 x 250) or Responsive advertisement code"><?php echo $AdRow['ad2']?></textarea>
							</div>
							<div class="form-group">
								<label for="inputAd3">HTML/JavaScript Based Advertisements</label>
								<textarea class="form-control" id="inputAd3" name="inputAd3" rows="3" placeholder="(250 x 250) or Responsive advertisement code"><?php echo $AdRow['ad3']?></textarea>
							</div>
						<?php } ?>

						<div class="panel-footer clearfix">

							<button type="submit" id="submitButton" class="btn btn-default btn-success btn-lg pull-right">Update Advertisements</button>

						</div><!--panel-footer clearfix-->

					</form>

				</div><!-- panel body -->

			</div><!--panel panel-default-->

		</section>

	</section><!--col-md-10-->

<?php include("footer.php");?>