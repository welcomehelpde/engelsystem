<?php
if(sql_num_query("SHOW INDEX FROM `Room` WHERE `Column_name` = 'location'") == 0) {
  sql_query("ALTER TABLE `Room` ADD `location` TEXT NOT NULL");
  $applied = true;
}

if(sql_num_query("SHOW INDEX FROM `Room` WHERE `Column_name` = 'lat'") == 0) {
  sql_query("ALTER TABLE `Room` ADD `lat` VARCHAR(20) NOT NULL");
  $applied = true;
}

if(sql_num_query("SHOW INDEX FROM `Room` WHERE `Column_name` = 'long'") == 0) {
  sql_query("ALTER TABLE `Room` ADD `long` VARCHAR(20) NOT NULL");
  $applied = true;
}
