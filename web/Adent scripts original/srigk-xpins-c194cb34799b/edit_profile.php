<?php include("header.php");?>

<div id="content" class="main-container">


<?php
if(!isset($_SESSION['username'])){?>
<script type="text/javascript">
function leave() {
window.location = "index.html";
}
setTimeout("leave()", 2);
</script>
<?php }else{?>
<script src="js/bootstrap-filestyle.min.js"></script>
<script>
$(function(){

$(":file").filestyle({iconName: "glyphicon-picture", buttonText: "Select Photo"});

});
</script>

<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
$('#inputfile').live('change', function()
{
$("#preview").html('');
$("#output-msg").html('<div class"alert alert-info">Uploading.. Please wait..</div>');


$("#PictureForm").ajaxForm(
{
    dataType:'json',
    success:function(json){
       $('#preview').html(json.img);
       $('#output-msg').html(json.msg);
    }
}).submit();

});
});
</script>

<?php

if($Profile = $mysqli->query("SELECT * FROM users WHERE id='$Uid'")){

    $ProfileRow = mysqli_fetch_array($Profile);
	
	$Profile->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
}	
	
?>

<div class="container pull-col">

<div class="col-md-8">

<div class="panel panel-default">

<div class="panel-heading"><h1>Update Your Profile Picture</h1></div>

    <div class="panel-body">


<div class="the-form">


<div id="output-msg"></div>

<form id="PictureForm" action="avatar.php" method="post" enctype="multipart/form-data">

<div class="form-group">
<label for="inputfile">Choose a Profile Photo</label>
<input type="file" id="inputfile" name="inputfile" class="filestyle" data-iconName="glyphicon-picture" data-buttonText="Select Photo">
</div>

</form>

<div id="preview"></div>

</div><!--the-form-->

</div>

</div><!--panel panel-default-->

<script>
$(document).ready(function()
{
    $('#ProfileForm').on('submit', function(e)
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

<div class="panel panel-default">

<div class="panel-heading"><h1>Update Profile Info</h1></div>

    <div class="panel-body">


<div class="the-form">

<div id="output"></div>

<form id="ProfileForm" action="submit_profile.php" method="post">

<div class="form-group">
            <label for="inputUsername">Username</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
<input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="Desired Username" disabled value="<?php echo ucfirst($ProfileRow['username']);?>">
</div>
</div>

<div class="form-group">
            <label for="inputEmail">Email</label>
                <div class="input-group">
                   <span class="input-group-addon">@</span>
<input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Edit Your Email Adress" value="<?php echo $ProfileRow['email'];?>">
</div>
</div>


<div class="form-group">
<label for="inputAbout">About</label>
<textarea class="form-control" id="inputAbout" name="inputAbout" rows="3" placeholder="Edit About Section"><?php echo $ProfileRow['about'];?></textarea>
</div>        
       

<button type="submit" id="submitButton" class="btn btn-default btn-info btn-lg pull-right">Update</button>

</form>

</div><!--the-form-->

</div>

</div><!--panel panel-default-->


<script>
$(document).ready(function()
{
    $('#PasswordForm').on('submit', function(e)
    {	
		e.preventDefault();
        $('#submitButton').attr('disabled', ''); // disable upload button
        //show uploading message
        $("#output-password").html('<div class="alert alert-info">Working.. Please wait..</div>');
		
        $(this).ajaxSubmit({
        target: '#output-password',
        success:  afterSuccess //call function after success
        });
    });
});
 
function afterSuccess()
{	
	 
    $('#submitButton').removeAttr('disabled'); //enable submit button
   
}
</script>


<div class="panel panel-default">

<div class="panel-heading"><h1>Update Password</h1></div>

<div class="panel-body">


<div class="the-form">

<div id="output-password"></div>

<form id="PasswordForm" action="submit_password.php" method="post">

<div class="form-group">
            <label for="inputCurrentPassword">Current Password</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
<input type="password" class="form-control" name="inputCurrentPassword" id="inputCurrentPassword" placeholder="Enter Your Current Password">
</div>
</div>

<div class="form-group">
            <label for="inputPassword">New Password</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
<input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Enter New Password">
</div>
</div>

<div class="form-group">
            <label for="inputConfirmPassword">Confirm Password</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
<input type="password" class="form-control" name="inputConfirmPassword" id="inputConfirmPassword" placeholder="Retype New Password">
</div>
</div>
   
<button type="submit" id="submitButton" class="btn btn-default btn-info btn-lg pull-right">Update</button>

</form>
</div><!--the-form-->

   
   
</div>

</div><!--panel panel-default-->   


</div><!-- /.col-md-8 -->

<div class="col-md-4">
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
<div class="right-box col-remove-display">
<?php echo $Ad2;?>
</div><!--r-box-->
<?php }?>
</div><!-- /.col-md-4 -->

</div><!-- /.container -->

<?php }?>

</div><!-- /.content -->

<?php include("footer_pages.php");?>  