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
<?php }else{

//user info

/*
$Mpid = $mysqli->escape_string($_GET['mid']);

if($ThisUserSql = $mysqli->query("SELECT * FROM users WHERE id='$Mpid'")){

   $ThisUserRow = mysqli_fetch_array($ThisUserSql);
   
   $avatar = $ThisUserRow['avatar'];
   $ThisUN = stripslashes($ThisUserRow['username']);
   
   $ThisUserSql->close();
   
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}
*/
?>

<script type="text/javascript" src="js/jquery.form.js"></script>
<script>
$(document).ready(function()
{
    $('#postMessage').on('submit', function(e)
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

function autocomplet() {
	var min_length = 0; // min caracters to display the autocomplete
	var keyword = $('#to-user').val();
	if (keyword.length >= min_length) {
		$.ajax({
			url: 'find_user.php',
			type: 'POST',
			data: {keyword:keyword},
			success:function(data){
				$('#to-user').show();
				$('#username_list').html(data);
				$('#username_list').show();
			}
		});
	} else {
		$('#username_list').hide();
	}
}

// set_item : this function will be executed when we select an item
function set_item(item) {
	// change input value
	$('#to-user').val(item);
	// hide proposition list
	$('#username_list').hide();
}

</script>

<div class="container pull-col">

<div class="col-md-8">


<div class="panel panel-default">

<div class="panel-heading"><h1>Send an Message</h1></div>

    <div class="panel-body">

<div class="the-form">

<div id="output"></div>

<form id="postMessage" action="submit_new_message.php" enctype="multipart/form-data" method="post">

<div class="form-group">
<input id="to-user" name="to-user" class="form-control" onkeyup="autocomplet()" placeholder="Participant"/>
<ul class="pick-user" id="username_list"></ul>
</div>

<div class="form-group">
<textarea class="form-control" id="inputMessage" name="inputMessage" rows="3" placeholder="Enter your message"></textarea>
</div>

<button type="submit" id="uploadButton" class="btn btn-default btn-info btn-lg pull-right">Send</button>

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