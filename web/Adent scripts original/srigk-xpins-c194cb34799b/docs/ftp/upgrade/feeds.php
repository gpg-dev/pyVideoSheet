<?php include("header.php");?>

<div id="content" class="main-container">


<div class="container pull-col">

<div class="col-md-8">

<div class="panel panel-default">

<div class="panel-heading"><h1>RSS Feeds</h1></div>

<div class="panel-body">

<ul class="feeds-nav">

<li><span class="fa fa-rss-square"></span><a href="rss_all.html">&nbsp;&nbsp;Newest Posts</a></li>

<?php
if($CatSql = $mysqli->query("SELECT * FROM categories ORDER BY cname ASC")){

   
while ($CatROW = mysqli_fetch_array($CatSql)) {
	$id= $CatROW['id'];
	$cname = $CatROW['cname'];
?>

<li><span class="fa fa-rss-square"></span><a href="rss_cat-<?php echo $id;?>.html">&nbsp;&nbsp;<?php echo $cname;?></a></li>

<?php }

     $CatSql->close();
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

?>

</ul>

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

</div><!-- /.content -->

<?php include("footer_pages.php");?>  