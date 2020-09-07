<?php
include('db.php');

$id = $mysqli->escape_string($_GET['id']);

if($Sql = $mysqli->query("SELECT * FROM posts WHERE id='$id'")){

   $row = mysqli_fetch_array($Sql);
   
   $Url = $row['url'];

   $Sql->close();
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

//Tot Hits
	$LinkHits = $row['link_hits'];
	$LinkHits = $LinkHits + 1;
	$Tot = $row['tot'];
	$Tot = $Tot + 1;
	
	$mysqli->query("UPDATE posts SET link_hits='$LinkHits', tot='$Tot' WHERE id='$id'") or die(mysqli_error());
	
	header( 'Location: '.$Url.'' ) ;
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Redirect URL</title>
</head>

<body>
</body>
</html>