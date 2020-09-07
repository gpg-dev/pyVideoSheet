<?php
session_start();

include('../db.php');
include('../constant.php');
$Settings = $getSetting($mysqli);

if($getAdmin = $mysqli->query("SELECT * FROM admin WHERE id = 1")){

    $AdminInfo = mysqli_fetch_array($getAdmin);
    $userId = $AdminInfo['user_id'];
	$getAdmin->close();
	if (!$userId) {
		$email = 'admin@xpins.com';
		$fields = "username, email, password, avatar, about, notifications, reg_date";
		$values = "'$email','$email','','','','','".(new DateTime())->format('Y-m-d')."'";
		$sql = "INSERT INTO users($fields) VALUES ($values)";
		$mysqli->query($sql);

		$getUser = $mysqli->query("SELECT * FROM users WHERE email = '$email' and username='$email'");
		$userInfo = mysqli_fetch_array($getUser);
		$userId = $userInfo['id'];
		$getUser->close();

		$sql = "UPDATE admin SET user_id = $userId where id = 1";
		$mysqli->query($sql);
	}
}else{
	 die("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
}

$UploadDirectory	= '../uploaded_images/'; //Upload Directory, ends with slash & make sure folder exist

if (!@file_exists($UploadDirectory)) {
	//destination folder does not exist
	die('<div class="alert alert-danger">Make sure Upload directory exist!</div>');
}

if($_POST) {
	//required variables are empty
	if(!@$_POST['inputCategory']) {
		die('<div class="alert alert-danger" role="alert">Please select a category</div>');
	}

	//required variables are empty
	if(!@$_POST['inputLink']) {
		die('<div class="alert alert-danger" role="alert">Please enter source url</div>');
	}

	$CheckUrl = $_POST['inputLink'];
	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $CheckUrl)) {
		//do nothing
	}else {
		die('<div class="alert alert-danger" role="alert">Source url is not valid.</div>');
	}

	//required variables are empty
	if(!@$_POST['inputTitle']) {
		die('<div class="alert alert-danger" role="alert">Please enter title for your post</div>');
	}

	//required variables are empty
	if(!isset($_FILES['inputfile']) && ( !isset($_POST['image-link']) || !count($_POST['image-link']) ) ) {
		die('<div class="alert alert-danger" role="alert">Please select an image</div>');
	}

	//File upload error encountered
	if(@$_FILES['inputfile'][0]['error']) {
		die(upload_errors(@$_FILES['inputfile[]'][0]['error']));
	}

	//required variables are empty
	if(!@$_POST['inputDescription']) {
		die('<div class="alert alert-danger" role="alert">Please add a description for your post</div>');
	}

	$PostTitle			= $mysqli->escape_string($_POST['inputTitle']);
	$PostLink			= $mysqli->escape_string($_POST['inputLink']);
	$Category           = $mysqli->escape_string($_POST['inputCategory']);
	$Description        = $mysqli->escape_string($_POST['inputDescription']);
	$AddedOn			= date("F j, Y");

	$uploadFiles = [];
	$NewImageName = '';
	$imagesList = '';
	$listFile = @$_FILES['inputfile'] ? $_FILES['inputfile'] : [];
	if (count($listFile)) {
		foreach ($listFile as $key => $arr) {
			foreach ($arr as $k => $file) {
				$uploadFiles[$k][$key] = $file;
			}
		}

		foreach ($uploadFiles as $inputFile) {
			$FileType = $inputFile['type'];
			switch(strtolower($FileType))
			{
				//allowed file types
				case 'image/png': //png file
				case 'image/jpeg': //jpeg file
				case 'image/gif': //gif file
					break;
				default:
					die('<div class="alert alert-danger" role="alert">Unsupported file format! Please select an JPEG, GIF or PNG image.</div>'); //output error
			}
		}
		$listNewName = [];
		foreach ($uploadFiles as $inputFile) {
			$ImageName		= strtolower($inputFile['name']); //uploaded file name
			$FileType		= $inputFile['type'];
			$ImageExt		= substr($ImageName, strrpos($ImageName, '.')); //file extension
			$RandNumber		= mt_rand(); //Random number to make each filename unique.
			$NewImageName	= preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), strtolower($PostTitle));
			$NewImageName	= $NewImageName.'_'.$RandNumber.$ImageExt;
			move_uploaded_file($inputFile["tmp_name"], $UploadDirectory . $NewImageName );
			$listNewName[]	= $NewImageName;
		}
		$NewImageName = @$listNewName[0]; //I fix the code, but it may be have many position that i can not care to. Show this image will catch those cases.
		$imagesList = implode(',', $listNewName);
	} else {
		$url = $_POST['image-link'][0];
		$contents=file_get_contents($url);
		$arrName = explode('.', $url);
		$fileExtension = array_pop($arrName);
		if (strpos($fileExtension, '?')) {
			$arr = explode('?', $fileExtension);
			$fileExtension = $arr[0];
		}
		$ImageFile = md5((new DateTime())->format('Y-m-d H:i:s')).".$fileExtension";
		$save_path=$UploadDirectory.$ImageFile;
		file_put_contents($save_path,$contents);
		$NewImageName = $ImageFile;
		$imagesList = $ImageFile;
	}
	include('../include/media_embed.php');
	
	$em = new media_embed($PostLink);
	$site = $em->get_site();
	if($site != "") {
		//video
		$EmbedCode = $em->get_iframe();
		$Type	   = 2;	
	} else {
		//check audio
		$CheckSoundCloud = preg_match("/^https?:\/\/(soundcloud.com|snd.sc)\/(.*)$/", $PostLink);
		if ($CheckSoundCloud) {
			$json_data = file_get_contents("http://soundcloud.com/oembed?format=json&url=".$PostLink."&iframe=true");
			$data = json_decode($json_data,true);
			$EmbedCode = $data['html'];
			$Type	   = 3;
		} else {
			//image
			$EmbedCode = "";
			 $Type	   = 1;
		}
	}

	$listField = "image, images, url, embed, title, description, catid, uid, date, type, active";
	$listValue = "'$NewImageName', '$imagesList','$PostLink','$EmbedCode','$PostTitle','$Description','$Category','0','$AddedOn','$Type','".@$Settings['active']."'";
	$sql = "INSERT INTO posts($listField) VALUES ($listValue)";
	$mysqli->query($sql);
	$sql = ' SELECT id FROM posts ORDER BY id desc limit 1';
	$lastInfo = $mysqli->query($sql);
	$lastId = mysqli_fetch_array($lastInfo)['id'];
	$lastInfo->close();
?>
	<script>
		$('#postCreate').delay(500).resetForm(1000);
		$('#postCreate').delay(1000).slideUp(1000);
//		setTimeout(function () { window.location.href = "edit_post.php?id=<?php //echo $lastId ?>//"; }, 500);
	</script>

<?php
	die('<div class="alert alert-success" role="alert">Your post submitted successfully.</div>');
}

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