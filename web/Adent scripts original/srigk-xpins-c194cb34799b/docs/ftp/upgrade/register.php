<?php include("header.php");?>

<div id="content" class="main-container">


<?php
if(isset($_SESSION['username'])){?>
<script type="text/javascript">
function leave() {
window.location = "index.html";
}
setTimeout("leave()", 2);
</script>
<?php }else{?>
<script src="js/jquery.ui.shake.js"></script>
<script src="js/jquery.form.js"></script>
<script>
$(document).ready(function()
{
    $('#RegisterForm').on('submit', function(e)
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
<div class="col-md-4 col-centered">


<div class="panel panel-default">

<div class="panel-heading"><h1>Register Now. It's Free!!</h1></div>

    <div class="panel-body">


<div class="the-form">


<div id="output"></div>

<form id="RegisterForm" action="submit_register.php" method="post">

<div class="form-group">
            <label for="inputUsername">Username</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
<input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="Desired Username">
</div>
</div>

<div class="form-group">
            <label for="inputEmail">Email</label>
                <div class="input-group">
                   <span class="input-group-addon">@</span>
<input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Your Email Adress">
</div>
</div>

<div class="form-group">
            <label for="inputPassword">Password</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
<input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Enter a Strong Password">
</div>
</div>

<div class="form-group">
            <label for="inputConfirmPassword">Confirm Password</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
<input type="password" class="form-control" name="inputConfirmPassword" id="inputConfirmPassword" placeholder="Re-Type Password">
</div>
</div>

<div class="form-group">
                <div class="input-group">
                Can't read the image? <a href="" onClick="return reloadImg('cap');">Click here to refresh</a>
</div>
</div>                

<div class="form-group">
                <div class="input-group">
                     <img class="cap" id="cap" src="captcha.php" alt=""/>
</div>
</div>   

<div class="form-group">
            <label for="inputCode">I'm Not a Robot</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-info-sign"></span></span>
<input type="password" class="form-control" name="inputCode" id="inputCode" placeholder="Please Enter Security Code">
</div>
</div>         
       

<button type="submit" id="submitButton" class="btn btn-default btn-info btn-lg pull-right">Signup</button>

</form>

</div><!--the-form-->

</div>

</div><!--panel panel-default-->

</div>

<script>
// <![CDATA[
function reloadImg(id) {
   var obj = document.getElementById(id);
   var src = obj.src;
   var pos = src.indexOf('?');
   if (pos >= 0) {
      src = src.substr(0, pos);
   }
   var date = new Date();
   obj.src = src + '?v=' + date.getTime();
   return false;
}
// ]]>	
</script> 

<?php }?>

</div><!-- /.content -->

<?php include("footer_pages.php");?>  