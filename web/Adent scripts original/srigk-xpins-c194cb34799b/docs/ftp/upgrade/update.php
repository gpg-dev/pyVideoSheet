<?php

include('db.php');


$mysqli->query("CREATE TABLE IF NOT EXISTS pms (
pm_id int(11) NOT NULL AUTO_INCREMENT,
from_id INT(11) DEFAULT NULL,
from_name VARCHAR(500) DEFAULT NULL,
to_id INT(11) DEFAULT NULL,
to_name VARCHAR(500) DEFAULT NULL,
avatar VARCHAR(999) DEFAULT NULL,
message LONGTEXT DEFAULT NULL,
pm_date VARCHAR(255) DEFAULT NULL,
from_read INT(11) DEFAULT NULL,
to_read INT(11) DEFAULT NULL,
from_delete INT(11) DEFAULT NULL,
to_delete INT(11) DEFAULT NULL,
last_msg VARCHAR(255) DEFAULT NULL,
msg_order VARCHAR(255) DEFAULT NULL,    
PRIMARY KEY (pm_id)
)DEFAULT CHARSET=utf8;");

$mysqli->query("CREATE TABLE IF NOT EXISTS pm_replies (
reply_id int(11) NOT NULL AUTO_INCREMENT,
from_id INT(11) DEFAULT NULL,
from_name VARCHAR(500) DEFAULT NULL,
to_id INT(11) DEFAULT NULL,
to_name VARCHAR(500) DEFAULT NULL,
avatar VARCHAR(999) DEFAULT NULL,
message LONGTEXT DEFAULT NULL,
pm_date VARCHAR(255) DEFAULT NULL,
pm_rid INT(11) DEFAULT NULL,    
PRIMARY KEY (reply_id)
)DEFAULT CHARSET=utf8;");

$mysqli->query("ALTER TABLE `users` ADD `notifications` INT(11) AFTER `reg_date`");  


?>

<div><center>Database Updated Successfully</center></div>