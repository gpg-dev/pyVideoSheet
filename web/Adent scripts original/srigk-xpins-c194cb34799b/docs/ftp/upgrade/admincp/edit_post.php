<?php include("header.php");?>

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

$id = $mysqli->escape_string($_GET['id']); 

if($Post = $mysqli->query("SELECT * FROM posts WHERE id='$id'")){

    $PostRow = mysqli_fetch_array($Post);
	
	$CatId = $PostRow['catid'];
	
    $Post->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}


if($Cat = $mysqli->query("SELECT * FROM categories WHERE id='$CatId'")){

    $CatRow = mysqli_fetch_array($Cat);
	
    $Cat->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}


?>    

<div id="output"></div>

<form id="postUpdator" action="update_post.php?id=<?php echo $id;?>" enctype="multipart/form-data" method="post">

<div class="form-group">
<label for="inputCategory">Category</label>
<select class="form-control" id="inputCategory" name="inputCategory">
   
  <option value="<?php echo $CatRow['id'];?>"><?php echo $CatRow['cname'];?></option>
  
  <option disabled>Change Category</option>
  
      <?php
if($SelectCategories = $mysqli->query("SELECT id, cname FROM categories WHERE id!='$CatId' ORDER BY cname ASC")){

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
<input type="text" class="form-control" name="inputLink" id="inputLink" placeholder="Link to the original post or source url" value="<?php echo $PostRow['url'];?>">
</div>

<div class="form-group">
<label for="inputTitle">Post Title</label>
<input type="text" class="form-control" name="inputTitle" id="inputTitle" placeholder="Add a title to your post" value="<?php echo $PostRow['title'];?>">
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