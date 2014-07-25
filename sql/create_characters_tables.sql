CREATE TABLE `phpbb_characters` (
  `character_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(45) DEFAULT NULL,
  `class` varchar(45) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `image` varchar(1000) DEFAULT NULL,
  `display_on_main_page` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `species` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`character_id`)
);

CREATE TABLE `phpbb_user_characters` (
  `user_id` int(11) NOT NULL,
  `main_page_name` varchar(255) DEFAULT NULL,
  `display_on_main_page` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
);
