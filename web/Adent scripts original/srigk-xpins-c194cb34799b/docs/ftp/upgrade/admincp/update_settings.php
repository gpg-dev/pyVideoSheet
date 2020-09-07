<?php

include('../db.php');

//Get User Settings

if($SiteSettings = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $Settings = mysqli_fetch_array($SiteSettings);
	
	$Active = $Settings['active'];
	
	$SiteSettings->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

//Get User Info


$UploadDirectory	= '../images/'; //Upload Directory, ends with slash & make sure folder exist

if (!@file_exists($UploadDirectory)) {
	//destination folder does not exist
	die('<div class="alert alert-danger">Make sure Upload directory exist!</div>');
}

if($_POST)
{	

	if(!isset($_POST['inputTitle']) || strlen($_POST['inputTitle'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please enter your site title</div>');
	}
	
	if(!isset($_POST['inputSiteurl']) || strlen($_POST['inputSiteurl'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please enter your site link</div>');
	}

	if(!isset($_POST['inputFbpage']) || strlen($_POST['inputFbpage'])>1)
	{
	$CheckFacebookPage = $mysqli->escape_string($_POST['inputFbpage']);

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $CheckFacebookPage)) {
  		//do nothing
	}else {
  	
	die('<div class="alert alert-danger" role="alert">Please enter full Facebook url.</div>');
	
	}
	}
	
	if(!isset($_POST['inputTwitter']) || strlen($_POST['inputTwitter'])>1)
	{
	$CheckTwitter = $mysqli->escape_string($_POST['inputTwitter']);

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $CheckTwitter)) {
  		//do nothing
	}else {
  	
	die('<div class="alert alert-danger" role="alert">Please enter full Twitter url.</div>');
	
	}
	}
	
	if(!isset($_POST['inputPinterest']) || strlen($_POST['inputPinterest'])>1)
	{
	$CheckPinterest = $mysqli->escape_string($_POST['inputPinterest']);

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $CheckPinterest)) {
  		//do nothing
	}else {
  	
	die('<div class="alert alert-danger" role="alert">Please enter full Pinterest url.</div>');
	
	}
	}
	
	if(!isset($_POST['inputGplus']) || strlen($_POST['inputGplus'])>1)
	{
	$CheckGplus = $mysqli->escape_string($_POST['inputGplus']);

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $CheckGplus)) {
  		//do nothing
	}else {
  	
	die('<div class="alert alert-danger" role="alert">Please enter full Google+ url.</div>');
	
	}
	}
	
		
	$SiteTitle			= $mysqli->escape_string($_POST['inputTitle']); // file title
	$SiteLink           = $mysqli->escape_string($_POST['inputSiteurl']);
	$MetaDescription    = $mysqli->escape_string($_POST['inputDescription']);
	$MetaKeywords 		= $mysqli->escape_string($_POST['inputKeywords']);
	$SiteEmail		    = $mysqli->escape_string($_POST['inputEmail']);
	$FBApp       		= $mysqli->escape_string($_POST['inputFbapp']);
	$FBPage	       		= $mysqli->escape_string($_POST['inputFbpage']);
	$TwitterLink	    = $mysqli->escape_string($_POST['inputTwitter']);
	$PinterestLink	    = $mysqli->escape_string($_POST['inputPinterest']);	
	$GooglePlusLink	    = $mysqli->escape_string($_POST['inputGplus']);
	$Approve		    = $mysqli->escape_string($_POST['inputApprove']);
	$Template		    = $mysqli->escape_string($_POST['inputTemplate']);	 
	
	
	if(isset($_FILES['inputfile']))
	{

	
	if($_FILES['inputfile']['error'])
	{
		//File upload error encountered
		die(upload_errors($_FILES['inputfile']['error']));
	}
	
	$Logo				= strtolower($_FILES['inputfile']['name']); //uploaded file name
	$ImageExt			= substr($Logo, strrpos($Logo, '.')); //file extension
	$FileType			= $_FILES['inputfile']['type']; //file type
	$FileSize			= $_FILES['inputfile']["size"]; //file size
		
	switch(strtolower($FileType))
	{
		//allowed file types
		case 'image/png': //png file
			break;
		default:
			die('<div class="alert alert-danger" role="alert">Unsupported File! Please upload PNG file as your logo.</div>'); //output error
	}
	
  
	$NewLogoName = 'logo'.$ImageExt;
   //Rename and save uploded file to destination folder.
   if(move_uploaded_file($_FILES['inputfile']["tmp_name"], $UploadDirectory . $NewLogoName ))
   {
		
// Insert info into database table.. do w.e!
	$mysqli->query("UPDATE settings SET name='$SiteTitle',siteurl='$SiteLink',keywords='$MetaKeywords',descrp='$MetaDescription',email='$SiteEmail',fbapp='$FBApp',fbpage='$FBPage',twitter='$TwitterLink',pinterest='$PinterestLink',google_plus='$GooglePlusLink',active='$Approve',template='$Template' WHERE id=1");
	
	}	
   }else{ // end checking logo upload
   
  $mysqli->query("UPDATE settings SET name='$SiteTitle',siteurl='$SiteLink',keywords='$MetaKeywords',descrp='$MetaDescription',email='$SiteEmail',fbapp='$FBApp',fbpage='$FBPage',twitter='$TwitterLink',pinterest='$PinterestLink',google_plus='$GooglePlusLink',active='$Approve',template='$Template' WHERE id=1");
   
    }

		die('<div class="alert alert-success" role="alert">Site settings updated successfully.</div>');

		
   }else{
   		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
  
}

if(isset($_FILES['inputfile']))
	{
//function outputs upload error messages, http://www.php.net/manual/en/features.file-upload.errors.php#90522
function upload_errors($err_code) {
	switch ($err_code) { 
        case UPLOAD_ERR_INI_SIZE: 
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; 
        case UPLOAD_ERR_FORM_SIZE: 
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'; 
        case UPLOAD_ERR_PARTIAL: 
            return 'The uploaded file was only partially uploaded'; 
        case UPLOAD_ERR_NO_FILE: 
            return 'No file was uploaded'; 
        case UPLOAD_ERR_NO_TMP_DIR: 
            return 'Missing a temporary folder'; 
        case UPLOAD_ERR_CANT_WRITE: 
            return 'Failed to write file to disk'; 
        case UPLOAD_ERR_EXTENSION: 
            return 'File upload stopped by extension'; 
        default: 
            return 'Unknown upload error'; 
    } 
} 
	}//End Logo upload
?>