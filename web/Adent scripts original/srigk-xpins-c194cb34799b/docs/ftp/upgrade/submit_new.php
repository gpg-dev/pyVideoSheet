<?php include("header.php");?>

<div id="content" class="main-container">


<?php
if(!isset($_SESSION['username'])){?>
<script type="text/javascript">
function leave() {
window.location = "login.html";
}
setTimeout("leave()", 2);
</script>
<?php }else{?>
<script src="js/bootstrap-filestyle.min.js"></script>
<script>
$(function(){

$(":file").filestyle({iconName: "glyphicon-picture", buttonText: "Select Image"});

});
</script>

<script type="text/javascript" src="js/jquery.form.js"></script>
<script>
$(document).ready(function()
{
    $('#postSubmitter').on('submit', function(e)
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
});
 
function afterSuccess()
{	
	 
    $('#submitButton').removeAttr('disabled'); //enable submit button
   
}
</script>

<div class="container pull-col">

<div class="col-md-8">


<div class="panel panel-default">

<div class="panel-heading"><h1>Post Something Interesting &amp; Inspiring</h1></div>

    <div class="panel-body">

<div class="the-form">

<div id="output"></div>

<form id="postSubmitter" action="submit_post.php" enctype="multipart/form-data" method="post">

<div class="form-group">
<label for="inputCategory">Category</label>
<select class="form-control" id="inputCategory" name="inputCategory">
  <option value="">Select a Category</option>
      <?php
if($SelectCategories = $mysqli->query("SELECT id, cname FROM categories ORDER BY cname ASC")){

    while($categoryRow = mysqli_fetch_array($SelectCategories)){
				
?>
      <option value="<?php echo $categoryRow['id'];?>"><?php echo $categoryRow['cname'];?></option>
      <?php

}

	$SelectCategories->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}
?>
</select> 
</div>
    
<div class="form-group">
<label for="inputfile">Featured Image</label>
<input type="file" id="inputfile" name="inputfile" class="filestyle" data-iconName="glyphicon-picture" data-buttonText="Select Image">
</div>

<div class="form-group">
<label for="inputLink">Link</label>
<input type="text" class="form-control" name="inputLink" id="inputLink" placeholder="Link to the original post or source url">
</div>

<div class="form-group">
<label for="inputTitle">Post Title</label>
<input type="text" class="form-control" name="inputTitle" id="inputTitle" placeholder="Add a title to your post">
</div>

<div class="form-group">
<label for="inputDescription">Description</label>
<textarea class="form-control" id="inputDescription" name="inputDescription" rows="3" placeholder="Enter a description for your post"></textarea>
</div>

<button type="submit" id="uploadButton" class="btn btn-default btn-info btn-lg pull-right">Submit</button>

</form>
</div><!--the-form-->
   
   </div>

</div><!--panel panel-default--> 



</div><!-- /.col-md-8 -->

<div class="col-md-4">
<?php if(!empty($Ad1)){ ?>
<div class="right-box">
<?php echo $Ad1;?>
</div><!--r-box-->
<?php }?>

<?php if(!empty($FbPage)){ ?>
<div class="right-box">
<div class="fb-like-box" data-href="<?php echo $FbPage;?>" data-width="292" data-show-faces="false" data-stream="false" data-show-border="false" data-header="false"></div>
</div><!--r-box-->
<?php }?>

<?php if(!empty($Ad2)){ ?>
<div class="right-box">
<?php echo $Ad2;?>
</div><!--r-box-->
<?php }?>
</div><!-- /.col-md-4 -->

</div><!-- /.container -->

<?php }?>

</div><!-- /.content -->

<?php include("footer_pages.php");?>  