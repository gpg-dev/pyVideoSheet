<?php include("header.php");?>

<section class="col-md-2">

<?php include("left_menu.php");?>
                    
</section><!--col-md-2-->

<section class="col-md-10">

<ol class="breadcrumb">
  <li>Admin CP</li>
  <li class="active">Dashboard</li>
</ol>

<div class="page-header">
  <h3>Dashboard <small>Your website dashboard</small></h3>
</div>

<section class="col-md-8">

<section class="col-md-6 box-space-right">

<div class="panel panel-default">

<div class="panel-heading"><h4>Post Status</h4></div>

    <div class="panel-body">

<ul>

<?php
if($PostsNumber = $mysqli->query("SELECT id FROM posts")){

    $TotalNumber = $PostsNumber->num_rows;
  
?> 
     <li class="fa fa-file-text-o"><span>Total Number of Posts: <?php echo $TotalNumber;?></span></li>

<?php

    $PostsNumber->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

if($ApprovedPosts = $mysqli->query("SELECT id FROM posts WHERE active=1")){

    $ApprovedNumber = $ApprovedPosts->num_rows;
?>     

	<li class="fa fa-file-text-o"><span>Total Approved Posts: <?php echo $ApprovedNumber;?></span></li>

<?php

    $ApprovedPosts->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

if($PendingPosts = $mysqli->query("SELECT id FROM posts WHERE active=0")){

    $PendingNumber= $PendingPosts->num_rows;
?>      
    <li class="fa fa-file-text-o"><span>Total Approval Pending posts: <?php echo $PendingNumber;?></span></li>
<?php

    $PendingPosts->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

?> 
</ul>

</div>

</div><!--panel panel-default-->  

</section><!--col-md-6-->

<section class="col-md-6">

<div class="panel panel-default">

<div class="panel-heading"><h4>Site Status</h4></div>

    <div class="panel-body">

<ul>

<?php 
if($TotalPostHits = $mysqli->query("SELECT SUM(hits) AS Hits FROM posts")){

    $NumberPostHits = mysqli_fetch_assoc($TotalPostHits);
  
?>      
    <li class="fa fa-eye"><span>Total Number Post Views: <?php echo $NumberPostHits['Hits'];?></span></li>
<?php

    $TotalPostHits->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

if($TotalUsers = $mysqli->query("SELECT id FROM posts WHERE active=1")){

    $NumberofUsers = $TotalUsers->num_rows;
?>

<li class="fa fa-eye"><span>Total Number of Users: <?php echo $NumberofUsers;?></span></li> 

<?php

    $TotalUsers->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

$url="http://".$SiteLink;
$xml = simplexml_load_file('http://data.alexa.com/data?cli=10&dat=snbamz&url='.$url);
$rank=isset($xml->SD[1]->POPULARITY)?$xml->SD[1]->POPULARITY->attributes()->TEXT:0;
//$web=(string)$xml->SD[0]->attributes()->HOST;
  
?>    
    <li class="fa fa-bar-chart-o"><span>Alexa Rank: <?php echo $rank;?></span></li>



</ul>

</div>

</div><!--panel panel-default--> 

</section><!--col-md-6-->
</section><!--col-md-8-->

<section class="col-md-8 box-space-top">

<div class="panel panel-default">

<div class="panel-heading"><h4>Last 10 Approved Posts</h4></div>

    <div class="panel-body">

<?php

$DisplayApproved= $mysqli->query("SELECT * FROM posts WHERE active=1 ORDER BY id DESC LIMIT 10");


	$NumberOfApp = mysqli_num_rows($DisplayApproved);
	
	if ($NumberOfApp==0)
	{
	echo '<div class="alert alert-danger">There are no approved posts to display at this moment.</div>';
	}
	if ($NumberOfApp>0)
	{
	?>
       <table class="table table-bordered">

        <thead>

            <tr>
				<th>Thumb</th>
                
                <th>Title</th>

                <th>Added On</th>
                
            </tr>

        </thead>

        <tbody>
    <?php
	}
	
	while($AppRow = mysqli_fetch_assoc($DisplayApproved)){
	
	$AppLongTitle = stripslashes($AppRow['title']);
	$CropAppTitle = strlen ($AppLongTitle);
	if ($CropAppTitle > 200) {
	$SortAppTitle = substr($AppLongTitle,0,200).'...';
	}else{
	$SortAppTitle = $AppLongTitle;}
	
	$AppPostLink = preg_replace("![^a-z0-9]+!i", "-", $AppLongTitle);
	$AppPostLink = urlencode($AppPostLink);
	$AppPostLink = strtolower($AppPostLink);

?>        

            <tr>
				<td><a href="../post-<?php echo $AppRow['id'];?>-<?php echo $AppPostLink;?>.html" target="_blank">
                <img src="timthumb.php?src=http://<?php echo $SiteLink;?>/uploaded_images/<?php echo $AppRow['image'];?>&amp;h=50&amp;w=50&amp;q=100" alt="<?php echo $AppLongTitle;?>" class="img-responsive">
                </a></td>
                
                <td><a href="../post-<?php echo $AppRow['id'];?>-<?php echo $AppPostLink;?>.html" target="_blank"><?php echo ucfirst($SortAppTitle);?></a></td>


                <td><?php echo $AppRow['date'];?></td>

            </tr>
<?php } ?>
    
         
        </tbody>

    </table>
    

</div>

</div><!--panel panel-default--> 

</section><!--col-md-8-->


<section class="col-md-8 box-space-top">

<div class="panel panel-default">

<div class="panel-heading"><h4>Last 10 Approval Pending Posts</h4></div>

    <div class="panel-body">

<?php

$DisplayPending= $mysqli->query("SELECT * FROM posts WHERE active=0 ORDER BY id DESC LIMIT 10");


	$NumberOfPen = mysqli_num_rows($DisplayPending);
	
	if ($NumberOfPen==0)
	{
	echo '<div class="alert alert-danger">There are no pending posts to display at this moment.</div>';
	}
	if ($NumberOfPen>0)
	{
	?>
       <table class="table table-bordered">

        <thead>

            <tr>
				<th>Thumb</th>
                
                <th>Title</th>

                <th>Added On</th>
                
            </tr>

        </thead>

        <tbody>
    <?php
	}
	
	while($PenRow = mysqli_fetch_assoc($DisplayPending)){
	
	$PenLongTitle = stripslashes($PenRow['title']);
	$CropPenTitle = strlen ($PenLongTitle);
	if ($CropPenTitle > 200) {
	$SortPenTitle = substr($PenLongTitle,0,200).'...';
	}else{
	$SortPenTitle = $PenLongTitle;}
	
	$PenPostLink = preg_replace("![^a-z0-9]+!i", "-", $PenLongTitle);
	$PenPostLink = urlencode($PenPostLink);
	$PenPostLink = strtolower($PenPostLink);

?>        

            <tr>
				<td><a href="../post-<?php echo $PenRow['id'];?>-<?php echo $PenPostLink;?>.html" target="_blank">

                <img src="timthumb.php?src=http://<?php echo $SiteLink;?>/uploaded_images/<?php echo $PenRow['image'];?>&amp;h=50&amp;w=50&amp;q=100" alt="<?php echo $PenLongTitle;?>" class="img-responsive">
                </a></td>
                
                <td><a href="../photo-<?php echo $PenRow['id'];?>-<?php echo $PenPostLink;?>.html" target="_blank"><?php echo ucfirst($SortPenTitle);?></a></td>

                <td><?php echo $PenRow['date'];?></td>

            </tr>
<?php } ?>
    
         
        </tbody>

    </table>
       

</div>

</div><!--panel panel-default--> 

</section><!--col-md-8-->

</section><!--col-md-10-->

<?php include("footer.php");?>