<?php
mysql_query("INSERT INTO `Privileges` (`id`, `name`, `desc`)
VALUES
  (42, 'access_userdata', 'See private user data (realname, email, etc.)');
");
$applied = mysql_affected_rows() > 0;
?>
