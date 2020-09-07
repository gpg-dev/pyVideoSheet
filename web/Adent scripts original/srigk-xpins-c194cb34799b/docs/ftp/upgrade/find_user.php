<?php include("db.php");

$keyword = '%'.$_POST['keyword'].'%';


if($squ = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $settings = mysqli_fetch_array($squ);
	
	$FbPage = $settings['fbpage'];
	
	$SiteLink = $settings['siteurl'];
	
	$SiteTitle = $settings['name'];

    $squ->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

if(!isset($_POST['keyword']) || strlen($_POST['keyword'])<1)
	{
	}else{

if($ThisUserSql = $mysqli->query("SELECT * FROM users WHERE username LIKE '%$keyword%'")){

   while($ThisUserRow = mysqli_fetch_array($ThisUserSql)){

   $ThisUN = stripslashes($ThisUserRow['username']);
   
   $ThisUserAvatar = $ThisUserRow['avatar'];
   
if (empty($ThisUserAvatar)){
	
	$Avatar = "<img src='http://".$settings['siteurl']."/templates/".$settings['template']."/images/default-avatar.png' class='img-responsive user-avatar-small' width='32' height='32'>";

}elseif (!empty($ThisUserAvatar)){

 	$Avatar = "<img src='timthumb.php?src=http://".$settings['siteurl']."/avatars/".$ThisUserAvatar."&amp;h=32&amp;w=32&amp;q=100' class='img-responsive user-avatar-small'/>";

}
   
   echo '<li class="pick-user" onclick="set_item(\''.str_replace("'", "\'", $ThisUN).'\')">'.$Avatar.'&nbsp;&nbsp;'.$ThisUN.'</li>';
   
}
   $ThisUserSql->close();
   
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

	}
 
$mysqli->close();



?>