<?php
include('db.php');

session_start();

$UserName = $_SESSION['username'];

if($GetUser = $mysqli->query("SELECT * FROM users WHERE username='$UserName'")){

    $UserInfo = mysqli_fetch_array($GetUser);

	$UserId = $UserInfo['id'];
	
    $GetUser->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

$path = "avatars/";

$valid_formats = array("jpg", "png", "gif", "jpeg");
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
{
$name = $_FILES['inputfile']['name'];
$size = $_FILES['inputfile']['size'];
if(strlen($name))
{
list($txt, $ext) = explode(".", $name);
if(in_array($ext,$valid_formats))
{
if($size<(1024*1024)) // Image size max 1 MB
{
$actual_image_name = time().$UserId.".".$ext;
$tmp = $_FILES['inputfile']['tmp_name'];
if(move_uploaded_file($tmp, $path.$actual_image_name))
{
$mysqli->query("UPDATE users SET avatar='$actual_image_name' WHERE id='$UserId'");

echo json_encode(array('img'=>"<img src='avatars/".$actual_image_name."' class='preview' width='150'/>",'msg'=>"<div class='alert alert-success'>Awesome, Profile picture has been uploaded.</div>"));
return;

if (!empty($avatrimage)) {
    unlink("avatars/$avatrimage");
}

}
else
echo json_encode(array('msg'=>"<div class='alert alert-danger'>There seems to be a problem. Please try again.</div>"));
return;

}
else
echo json_encode(array('msg'=>"<div class='alert alert-danger'>Image file size max 1 MB.</div>"));
return;

}
else
echo json_encode(array('msg'=>"<div class='alert alert-danger'>Invalid file format</div>"));
return;

}
else
echo json_encode(array('msg'=>"<div class='alert alert-danger'>Please select image.</div>"));
return;
exit;
}
?>