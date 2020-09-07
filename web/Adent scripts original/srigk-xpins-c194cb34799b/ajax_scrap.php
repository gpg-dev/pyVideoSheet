<?php
/**
 * Created by PhpStorm.
 * User: quach
 * Date: 25-Jan-18
 * Time: 11:35 PM
 */
include 'constant.php';
if (@$_GET['url']) {
	die(json_encode($fetchUrlAndMakeHtml($_GET['url'], '', @$_GET['multi']))) ;
}
