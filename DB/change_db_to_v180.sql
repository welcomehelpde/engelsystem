ALTER TABLE `User` ADD `CreateDate` DATETIME NULL AFTER `lastLogIn`;
ALTER TABLE `User` CHANGE `lastLogIn` `lastLogIn` DATETIME NOT NULL DEFAULT 'NOW()',
CHANGE `CreateDate` `CreateDate` DATETIME NOT NULL DEFAULT 'NOW()';