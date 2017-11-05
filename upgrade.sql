

ALTER TABLE `profiles` ADD PRIMARY KEY(`profile_id`);
ALTER TABLE `profiles` CHANGE `profile_id` `profile_id` INT(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `views` ADD INDEX(`profile_id`);