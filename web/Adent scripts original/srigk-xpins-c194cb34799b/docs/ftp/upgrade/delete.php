<?php 

include('db.php');

$id = $mysqli->escape_string($_POST['id']);


if($DData = $mysqli->query("SELECT image FROM posts WHERE id='$id'")){

    $DRow = mysqli_fetch_array($DData);
	
	$DelImg = $DRow['image'];

    $DData->close();
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

unlink("uploaded_images/$DelImg");

$mysqli->query("DELETE FROM posts WHERE id=$id");

$mysqli->query("DELETE FROM favip WHERE postid=$id");

?>