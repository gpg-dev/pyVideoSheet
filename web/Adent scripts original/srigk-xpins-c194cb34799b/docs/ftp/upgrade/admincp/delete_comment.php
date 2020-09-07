<?php

include("../db.php");

$del = $mysqli->escape_string($_POST['id']);

$delete=$mysqli->query("DELETE FROM comments WHERE id='$del'");

echo '<div class="alert alert-success" role="alert">Comment successfully deleted</div>';

?>