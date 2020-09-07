<?php

include("../db.php");

$del = $mysqli->escape_string($_POST['id']);

if($ImageInfo = $mysqli->query("SELECT * FROM posts WHERE uid='$del'")){

    $GetInfo = mysqli_fetch_array($ImageInfo);
	
	$CountImages = mysqli_num_rows($ImageInfo);
	
	$Image = $GetInfo['image'];
	
	$ImageInfo->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

if ($CountImages>0){

unlink("../uploaded_images/$Image");

$DeletePosts = $mysqli->query("DELETE FROM posts WHERE uid='$del'");

}

$DeleteUsers = $mysqli->query("DELETE FROM users WHERE id='$del'");

$DeleteComments = $mysqli->query("DELETE FROM comments WHERE uid='$del'");

$DeleteFavorites = $mysqli->query("DELETE FROM favip WHERE uid='$del'");

echo '<div class="alert alert-success" role="alert">User successfully deleted</div>';

?>