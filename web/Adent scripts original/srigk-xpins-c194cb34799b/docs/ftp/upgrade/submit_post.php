<?php
session_start();

include('db.php');

//Get User Settings

if($SiteSettings = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $Settings = mysqli_fetch_array($SiteSettings);
	
	$Active = $Settings['active'];
	
	$SiteSettings->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

//Get User Info

$LoggedUser = $_SESSION['username'];

if($GetUser = $mysqli->query("SELECT * FROM users WHERE username='$LoggedUser'")){

    $UserInfo = mysqli_fetch_array($GetUser);

	$UserId = $UserInfo['id'];
	
	$GetUser->close();
	
}else{
     
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
	 
}

$UploadDirectory	= 'uploaded_images/'; //Upload Directory, ends with slash & make sure folder exist


if (!@file_exists($UploadDirectory)) {
	//destination folder does not exist
	die('<div class="alert alert-danger">Make sure Upload directory exist!</div>');
}

if($_POST)
{	

	if(!isset($_POST['inputCategory']) || strlen($_POST['inputCategory'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please select a category</div>');
	}
	
	if(!isset($_FILES['inputfile']))
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please select an image</div>');
	}
	
	if($_FILES['inputfile']['error'])
	{
		//File upload error encountered
		die(upload_errors($_FILES['inputfile']['error']));
	}

	if(!isset($_POST['inputLink']) || strlen($_POST['inputLink'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please enter source url</div>');
	}
	
	$CheckUrl = $_POST['inputLink'];

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $CheckUrl)) {
  		//do nothing
	}else {
  	
	die('<div class="alert alert-danger" role="alert">Source url is not valid.</div>');
	
	}
	
	if(!isset($_POST['inputTitle']) || strlen($_POST['inputTitle'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please enter title for your post</div>');
	}
	
	
	if(!isset($_POST['inputDescription']) || strlen($_POST['inputDescription'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please add a description for your post</div>');
	}
	
		
	$ImageName			= strtolower($_FILES['inputfile']['name']); //uploaded file name
	$PostTitle			= $mysqli->escape_string($_POST['inputTitle']); // file title
	$PostLink			= $mysqli->escape_string($_POST['inputLink']); // file title
	$ImageExt			= substr($ImageName, strrpos($ImageName, '.')); //file extension
	$FileType			= $_FILES['inputfile']['type']; //file type
	$FileSize			= $_FILES['inputfile']["size"]; //file size
	$RandNumber   		= rand(0, 9999999999); //Random number to make each filename unique.
	$Catagory           = $mysqli->escape_string($_POST['inputCategory']);
	$Description        = $mysqli->escape_string($_POST['inputDescription']);
	$AddedOn			= date("F j, Y");
	
	switch(strtolower($FileType))
	{
		//allowed file types
		case 'image/png': //png file
		case 'image/jpeg': //jpeg file
		case 'image/gif': //jpeg file
			break;
		default:
			die('<div class="alert alert-danger" role="alert">Unsupported file format! Please select an JPEG, GIF or PNG image.</div>'); //output error
	}
	
	include('include/media_embed.php');
	
	$em = new media_embed($PostLink);
	$site = $em->get_site();
	if($site != "")
	{
		$EmbedCode = $em->get_iframe();
		$Type	   = 2;	
	}
	else
	{
	//check audio
	$CheckSoundCloud = preg_match("/^https?:\/\/(soundcloud.com|snd.sc)\/(.*)$/", $PostLink);
	
	if ($CheckSoundCloud) {
    	$json_data = file_get_contents("http://soundcloud.com/oembed?format=json&url=".$PostLink."&iframe=true");
		$data = json_decode($json_data,true);

		 $EmbedCode = $data['html'];
		 $Type	   = 3;	
		 
	} else {
		
    	$EmbedCode = "";
		 $Type	   = 1;
	
	}	
	}	
	
  
	$NewImageName = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), strtolower($PostTitle));
	$NewImageName = $NewImageName.'_'.$RandNumber.$ImageExt;

	if(move_uploaded_file($_FILES['inputfile']["tmp_name"], $UploadDirectory . $NewImageName ))
    {
		
	$mysqli->query("INSERT INTO posts(image, url, embed, title, description, catid, uid, date, type, active) VALUES ('$NewImageName','$PostLink','$EmbedCode','$PostTitle','$Description','$Catagory','$UserId','$AddedOn','$Type','$Active')");
		

?>

<script>
$('#postSubmitter').delay(500).resetForm(1000);
$('#postSubmitter').delay(1000).slideUp(1000);
</script>

<?php
		die('<div class="alert alert-success" role="alert">Your post submitted successfully.</div>');

		
   }else{
   		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
   }
}

//function outputs upload error messages, http://www.php.net/manual/en/features.file-upload.errors.php#90522
function upload_errors($err_code) {
	switch ($err_code) { 
        case UPLOAD_ERR_INI_SIZE: 
            return '<div class="alert alert-danger" role="alert">The uploaded file exceeded.</div>'; 
        case UPLOAD_ERR_FORM_SIZE: 
            return '<div class="alert alert-danger" role="alert">The uploaded file exceeded.</div>'; 
        case UPLOAD_ERR_PARTIAL: 
            return '<div class="alert alert-danger" role="alert">The uploaded file was only partially uploaded.</div>'; 
        case UPLOAD_ERR_NO_FILE: 
            return '<div class="alert alert-danger" role="alert">No file was uploaded.</div>'; 
        case UPLOAD_ERR_NO_TMP_DIR: 
            return '<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>'; 
        case UPLOAD_ERR_CANT_WRITE: 
            return '<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>'; 
        case UPLOAD_ERR_EXTENSION: 
            return '<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>'; 
        default: 
            return '<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>';  
    } 
} 
?>