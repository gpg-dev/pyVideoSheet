<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26-Nov-17
 * Time: 11:39 PM
 */
$postType = [
	'image' => 1,
	'video' => 2,
	'audio' => 3
];
$faIcon = [
	'view' => 'fa-eye',
	'like' => 'fa-thumbs-o-up',
	'audio' => 'fa-volume-up',
	'video' => 'fa-youtube-play',
];

$countPost = 1;

$renderGeneralPost = function ($row) {
	global $faIcon, $countPost, $listAd, $getImageFromPost;
	$long = stripslashes($row['description']);
	$strd = strlen ($long);
	if ($strd > 140) {
		$dlong = substr($long,0,137).'...';
	}else{
		$dlong = $long;
	}

	$LongTitle = stripslashes($row['title']);
	$strt = strlen ($LongTitle);
	if ($strt > 40) {
		$tlong = substr($LongTitle,0,37).'...';
	}else{
		$tlong = $LongTitle;}

	$PageLink = preg_replace("![^a-z0-9]+!i", "-", $LongTitle);
	$PageLink = strtolower($PageLink);
	$PageLink = strtolower($PageLink);

	$Type = $row['type'];
	$PostId = $row[0];

	$link = "post-".$PostId."-$PageLink.html";

	if ($countPost++ % 4 === 0 && count($listAd)) {//render advertisement
		$ad = $listAd[array_rand($listAd)];
		?>
		<div class="adv advertisement" style="max-height: 100%; max-width: 100%;">
			<?php echo $ad; ?>
		</div>
		</div>
		<div class="box">
		<?php
	}

	$images = $getImageFromPost($row);

	if($Type==2){
		echo '<div class="play-button"><a href="'.$link.'" target="_self"><span class="fa '.$faIcon['video'].'"></span></a></div>';
	}else if($Type==3){
		echo '<div class="play-button"><a href="'.$link.'" target="_self"><span class="fa '.$faIcon['audio'].'"></span></a></div>';
	}
	$imageLink = $images[array_rand($images)];
	if (strpos($imageLink, 'http://') === false && strpos($imageLink, 'https://') === false) {
		$imageLink = "uploaded_images/$imageLink";
	}
	echo '<a href="'.$link.'" title="'.$row['title'].'">'.
		'	<img alt="'.$row['title'].'" src="'.$imageLink.'" class="box-image" >'.
		'</a>'.
		'<h3>'.
		'	<a href="'.$link.'" target="_self">'.$tlong.'</a>'.
		'</h3>'.
		'<div class="text-left"></div>';

};

$renderLikeBox = function ($row) {
	global $Uid, $mysqli, $faIcon;
	$PostId = $row[0];
	$category = $row['cname'];
	$up = $row['likes'];
	if ($Uid >0){
		//Check Votes
		if($VcSql= $mysqli->query("SELECT uid FROM favip WHERE postid='$PostId' and uid='$Uid'")){
			$VcRow = mysqli_num_rows($VcSql);
		}else{
			printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again</div>");
		}
	}
	?>
	<div class="like-box">
		<span class="category" style="border: solid 1px #f3f1f2; border-radius: 5px; padding: 5px">
			<i class="fa fa-leaf" aria-hidden="true"></i>&nbsp;<?php echo $category;?>
		</span>
		<div class='up' style="display: inline-block">
			<?php if(!isset($_SESSION['username'])){?>
				<a href="" class="like" data-id="<?php echo $PostId;?>" data-name="up"><span class="likes two fa <?php echo $faIcon['like'] ?>"> <?php echo $up; ?></span></a>
			<?php }else{ ?>
				<?php if ($VcRow == NULL){?>
					<a href="" class="like" data-id="<?php echo $PostId;?>" data-name="up"><span class="likes two fa <?php echo $faIcon['like'] ?>"> <?php echo $up; ?></span></a>
				<?php }elseif ($VcRow ==1) {?>
					<a href="" class="like" data-id="<?php echo $PostId;?>" data-name="up"><span class="likes one fa <?php echo $faIcon['like'] ?>"> <?php echo $up; ?></span></a>
				<?php } }?>
			<div class="tot-views fa <?php echo $faIcon['view'] ?> "> <?php echo $row['tot'];?></div>
		</div><!-- /up-->
	</div><!-- /like-box-->
<?php
};

$sqlGetProduct = function ($where = '', $sortCondition = 'posts.id', $limit = 0) {
	$leftJoin = " LEFT JOIN categories ON posts.catid = categories.id ";
	if (is_array($where)) {
		$where = " AND " . implode(" AND ", $where);
	} elseif ($where) {
		$where = " AND $where ";
	}
	!$sortCondition && $sortCondition = 'posts.id';
	if (!$limit) {
		$limit = isset($_GET['page']) ? ($_GET['page'] - 1) * 20 : 0;
	}
	return "SELECT * FROM posts $leftJoin WHERE active=1 $where ORDER BY $sortCondition DESC LIMIT $limit, 20";
};

$sqlLeftJoinCategoryPost = " LEFT JOIN categories ON posts.catid = categories.id ";

$getImageFromPost = function ($row) {
	$list = @$row['image'] ? $row['image'] : @$row['images'];
	return explode(',', $list);
};

$getListImageFromPost = function ($row) {
	$list = @$row['images'] ? $row['images'] : @$row['image'];
	return explode(',', $list);
};

$getHtmlInfo = function ($url) {
	$data = [];
	try {
		$html = file_get_contents($url);
	} catch (Exception $ex) {
		$html = '';
	}

	$title = @explode('<title>', $html)[1];
	$data['title'] = @explode('</title>', $title)[0];

	$arr = explode('<img ', $html);
	$listImages = [];
	if (strpos($url, 'youtube.com') !== false) {
		global $getYoutubeImage;
		$listImages[] = $getYoutubeImage($url);
		foreach ($arr as $item) {
			$urlLong = @explode('data-thumb="', $item)[1];
			$imageUrl = @explode('"', $urlLong)[0];
			$splitUrl = explode('?', $imageUrl);
			$typeImg = explode('.', $splitUrl[0]);
			$typeImg = array_pop($typeImg);
			if (in_array($typeImg, ['jpg', 'png', 'gif']) && !in_array($imageUrl, $listImages)) {
				if (strpos('|'.$imageUrl, $url) == 1 || strpos('|'.$imageUrl, 'http')) {
					$listImages[] = $imageUrl;
				} elseif (strpos('|'.$imageUrl, '//') == 1) {
					$listImages[] = 'https:' . $imageUrl;
				} else {
					if (substr($imageUrl, 0, 1) == '/') $imageUrl = substr($imageUrl, 1);
					$listImages[] = $imageUrl;
				}
			}
		}
	} else {
		$baseUrl = explode('/', $url);
		if (strpos("|$baseUrl[0]", 'http')) {
			$baseUrl = "$baseUrl[0]//$baseUrl[2]";
		} else {
			$baseUrl = $baseUrl[0];
		}
		foreach ($arr as $item) {
			$urlLong = @explode('src="', $item)[1];
			$imageUrl = @explode('"', $urlLong)[0];
			$splitUrl = explode('?', $imageUrl);
			$typeImg = explode('.', $splitUrl[0]);
			$typeImg = array_pop($typeImg);
			if (in_array($typeImg, ['jpg', 'png', 'gif']) && !in_array($imageUrl, $listImages)) {
				if (strpos('|'.$imageUrl, $url) == 1 || strpos('|'.$imageUrl, 'http')) {
					$listImages[] = $imageUrl;
				} elseif (strpos('|'.$imageUrl, '//') == 1) {
					$listImages[] = 'https:' . $imageUrl;
				} else {
					if (substr($baseUrl, strlen($baseUrl) - 1) != '/') $baseUrl .= '/';
					if (substr($imageUrl, 0, 1) == '/') $imageUrl = substr($imageUrl, 1);
					$listImages[] = $baseUrl.$imageUrl;
				}
			}
		}
	}
	$data['images'] = $listImages;

	return $data;
};

$getYoutubeImage = function ($url) {
	$arr = explode('?v=', $url);
	$id = @$arr[1];
	$arr = explode('&', $id);
	$id = @$arr[0];
	if (!$id) {
		return [];
	}
	return "https://i1.ytimg.com/vi/$id/0.jpg";
};

$newMethod = function ($url) {

	$data = [];
	try {
		$html = file_get_contents($url);
	} catch (Exception $ex) {
		$html = '';
	}

	$doc = new DOMDocument();
	@$doc->loadHTML($html);

	$title = $doc->getElementsByTagName('title')[0];//@explode('<title>', $html)[1];
	/** @var DOMElement $title */
	$data['title'] = $title->firstChild->data;//@explode('</title>', $title)[0];

	$listImages = [];
	$tagImg = $doc->getElementsByTagName('img');
	$baseUrl = explode('/', $url);
	if (strpos("|$baseUrl[0]", 'http')) {
		$baseUrl = "$baseUrl[0]//$baseUrl[2]";
	} else {
		$baseUrl = $baseUrl[0];
	}
	foreach ($tagImg as $tag) {
		$imageUrl = $tag->getAttribute('src');
		$splitUrl = explode('?', $imageUrl);
		$typeImg = explode('.', $splitUrl[0]);
		$typeImg = array_pop($typeImg);
		if (in_array($typeImg, ['jpg', 'png', 'gif']) && !in_array($imageUrl, $listImages)) {
			if (strpos('|'.$imageUrl, $url) == 1 || strpos('|'.$imageUrl, 'http')) {
				$listImages[] = $imageUrl;
			} elseif (strpos('|'.$imageUrl, '//') == 1) {
				$listImages[] = 'https:' . $imageUrl;
			} else {
				if (substr($baseUrl, strlen($baseUrl) - 1) != '/') $baseUrl .= '/';
				if (substr($imageUrl, 0, 1) == '/') $imageUrl = substr($imageUrl, 1);
				$listImages[] = $baseUrl.$imageUrl;
			}
		}
	}
	$data['images'] = $listImages;
	return $data;
};

$fetchUrlAndMakeHtml = function ($url, $style = '', $isCheckbox = false) {
	global $getHtmlInfo;
	$data = $getHtmlInfo($url);
	$htmlImage = '';
	$type = $isCheckbox ? 'checkbox' : 'radio';
	foreach ($data['images'] as $image) {
		$htmlImage .= <<<HTML
		<label class="box-image url" style="$style">
			<img src=" $image">
			<input type="$type" name="image-link[]" value="$image">
		</label>
HTML;
	}
	$title = $data['title'];
	return ['title' => $title, 'htmlImg' => nl2br($htmlImage)];
};

$buildQueryUpdateFromArray = function ($update = array()) {
	$arr = [];
	foreach ($update as $key => $value) {
		$arr[] = "$key='$value'";
	}
	return implode(',', $arr);
};

/**
* @param mysqli $mysqli
* @return array
**/
$getSetting = function ($mysqli) {
	$SiteSettings = $mysqli->query("SELECT * FROM settings WHERE id='1'");
	if (!$SiteSettings) return [];
    $Settings = mysqli_fetch_array($SiteSettings);
	$SiteSettings->close();
	return $Settings;
};