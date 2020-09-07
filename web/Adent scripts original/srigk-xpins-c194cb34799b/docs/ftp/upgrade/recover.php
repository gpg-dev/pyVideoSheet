<?php include("header.php");?>

<div id="content" class="main-container">


<?php
if(isset($_SESSION['username'])){?>
<script type="text/javascript">
function leave() {
window.location = "discover.html";
}
setTimeout("leave()", 2);
</script>
<?php }else{?>
<script src="js/jquery.ui.shake.js"></script>
<script src="js/jquery.form.js"></script>
<script>
$(document).ready(function()
{
    $('#recoveredForm').on('submit', function(e)
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
<div class="col-md-3 col-sm-5 col-centered">


<div class="panel panel-default">

<div class="panel-heading"><h1>Login</h1></div>

    <div class="panel-body">


<div class="the-form">


<div id="output"></div>

<form id="recoveredForm" action="send_recovery.php" method="post">

<div class="form-group">
            <label for="inputRecovery">Registered Email</label>
                <div class="input-group">
                   <span class="input-group-addon">@</span>
<input type="email" class="form-control" name="inputRecovery" id="inputRecovery" placeholder="Email">
</div>
</div>
   
<button type="submit" id="submitButton" class="btn btn-default btn-success btn-lg pull-right">Reset</button>

</form>

</div><!--the-form-->

</div>

</div><!--panel panel-default-->

<div class="txt-centered">Forgot <a href="recover.html">Username / Password</a>?</div>

</div>

<?php }?>

</div><!-- /.content -->

<?php include("footer_pages.php");?>  