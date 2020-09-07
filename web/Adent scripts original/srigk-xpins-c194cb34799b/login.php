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
    $('#LoginForm').on('submit', function(e)
    {
        e.preventDefault();
        $('#submitButton').attr('disabled', ''); // disable upload button
        //show uploading message
        $("#output").html('<div class="alert alert-info" role="alert">Uploading.. Please wait..</div>');
		
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
<div class="col-md-3 col-sm-5 col-centered">


<div class="panel panel-default">

<div class="panel-heading"><h1>Login</h1></div>

    <div class="panel-body">


<div class="the-form">


<div id="output"></div>

<form id="LoginForm" action="submit_login.php" method="post">

<div class="form-group">
            <label for="inputUsername">Username</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
<input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="Username">
</div>
</div>

<div class="form-group">
            <label for="inputPassword">Password</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
<input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Password">
</div>
</div>
   
<button type="submit" id="submitButton" class="btn btn-default btn-info btn-lg pull-right">Login</button>

</form>
</div><!--the-form-->

</div>

</div><!--panel panel-default-->

<div class="txt-centered">Forgot <a href="recover.html">Username / Password</a>?</div>

</div>

<?php }?>

</div><!-- /.content -->

<?php include("footer_pages.php");?>  