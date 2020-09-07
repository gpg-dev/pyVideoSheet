<?php include("header.php");?>

<div id="content" class="main-container">


<div class="container pull-col">

<div class="col-md-8">

<div class="panel panel-default">

<div class="panel-heading"><h1>Terms of Use</h1></div>

<div class="panel-body">
<?php

if($pages = $mysqli->query("SELECT * FROM  pages WHERE id='3'")){

    $pagerow = mysqli_fetch_array($pages);

    $pages->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

?>

<p><?php echo stripslashes($pagerow['page']);?></p>

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

</div><!-- /.content -->

<?php include("footer_pages.php");?>  