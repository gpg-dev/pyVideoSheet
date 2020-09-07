<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26-Nov-17
 * Time: 8:44 PM
 */

include("../db.php");
$getCategoryList = function () {
	global $mysqli;
	$result = $mysqli->query("SELECT * FROM posts WHERE active=1 ORDER BY id DESC LIMIT 0, 20");
};

class common {
	public static function getCategoryList () {
		global $mysqli;
		$query = "";
	}
}