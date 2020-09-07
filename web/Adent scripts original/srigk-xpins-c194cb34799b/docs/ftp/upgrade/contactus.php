<?php include("header.php");?>

<div id="content" class="main-container">


<div class="container pull-col">

<div class="col-md-8">

<div class="panel panel-default">

<div class="panel-heading"><h1>Contact us</h1></div>

<div class="panel-body">
<script type="text/javascript" src="js/jquery.form.js"></script>
<script>
$(document).ready(function()
{
    $('#ContactForm').on('submit', function(e)
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

<div class="the-form">

<div id="output"></div>

<form id="ContactForm" action="send_mail.php" method="post">

<div class="form-group">
            <label for="inputYourname">Your Name</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
<input type="text" class="form-control" name="inputYourname" id="inputYourname" placeholder="Your Name">
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
            <label for="inputSubject">Subject</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-info-sign"></span></span>
<input type="text" class="form-control" name="inputSubject" id="inputSubject" placeholder="Subject">
</div>
</div>

<div class="form-group">
<label for="inputMessage">Message</label>
<textarea class="form-control" id="inputMessage" name="inputMessage" rows="3" placeholder="Your Message"></textarea>
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
       

<button type="submit" id="submitButton" class="btn btn-default btn-info btn-lg pull-right">Send</button>

</form>
</div><!--the-form-->

</div>

</div><!--panel panel-default-->

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

</div><!-- /.content -->

<?php include("footer_pages.php");?>  