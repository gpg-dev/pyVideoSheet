<?php

include("../db.php");

$id = $mysqli->escape_string($_POST['id']);

$update = $mysqli->query("UPDATE posts SET active='0' WHERE id='$id'");

echo '<div class="alert alert-success" role="alert">Post successfully disapproved</div>';

?>