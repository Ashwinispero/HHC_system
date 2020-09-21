ALTER TABLE `sp_service_professionals` ADD `location_id_home` INT NOT NULL AFTER `location_id` ;

ALTER TABLE `sp_service_professionals` ADD `set_location` ENUM( '1', '2' ) NOT NULL COMMENT '1-home locatoin , 2- work location' AFTER `location_id_home` ;

ALTER TABLE `sp_service_professionals` CHANGE `address` `address` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'home address';

ALTER TABLE `sp_service_professionals` CHANGE `location_id` `location_id` INT( 11 ) NOT NULL COMMENT 'work location';

ALTER TABLE `sp_patients` ADD `google_location` VARCHAR( 240 ) NOT NULL AFTER `location_id` ;

ALTER TABLE `sp_service_professionals` CHANGE `location_id` `location_id` VARCHAR( 240 ) NOT NULL COMMENT 'work location',
CHANGE `location_id_home` `location_id_home` VARCHAR( 240 ) NOT NULL ;

ALTER TABLE `sp_service_professionals` CHANGE `set_location` `set_location` ENUM( '1', '2' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '2' COMMENT '1-home locatoin , 2- work location';

ALTER TABLE  `sp_service_professionals` ADD  `google_home_location` VARCHAR( 240 ) NOT NULL ,
ADD  `google_work_location` VARCHAR( 240 ) NOT NULL ;


ALTER TABLE `sp_event_plan_of_care` ADD `service_cost` DOUBLE( 10, 2 ) NOT NULL AFTER `end_date` ;
