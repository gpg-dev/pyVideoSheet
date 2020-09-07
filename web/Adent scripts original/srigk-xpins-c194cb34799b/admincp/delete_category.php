<?php

include("../db.php");

$del = $mysqli->escape_string($_POST['id']);

if($ImageInfo = $mysqli->query("SELECT * FROM photos WHERE category_id='$del'")){

    $GetInfo = mysqli_fetch_array($ImageInfo);
	
	$CountImages = mysqli_num_rows($ImageInfo);
	
	$Image = $GetInfo['photo'];
	
	$ImageInfo->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
}

if ($CountImages>0){

unlink("../photos/$Image");

$DeletePhotos = $mysqli->query("DELETE FROM photos WHERE category_id='$del'") or die(mysqli_error());

}

$Deletecategory = $mysqli->query("DELETE FROM categories WHERE category_id='$del'") or die(mysqli_error());


echo '<div class="alert alert-success" role="alert">Category successfully deleted</div>';

?>