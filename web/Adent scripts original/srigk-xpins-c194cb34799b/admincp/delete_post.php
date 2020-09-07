<?php

include("../db.php");

$del = $mysqli->escape_string($_POST['id']);

if($ImageInfo = $mysqli->query("SELECT * FROM posts WHERE id='$del'")){

    $GetInfo = mysqli_fetch_array($ImageInfo);
	
	$Image = $GetInfo['image'];
	
	$ImageInfo->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
}

unlink("../uploaded_images/$Image");

$Delete = $mysqli->query("DELETE FROM posts WHERE id='$del'");

$DeleteVotes = $mysqli->query("DELETE FROM favip WHERE postid='$del'");


echo '<div class="alert alert-success" role="alert">Post successfully deleted</div>';

?>