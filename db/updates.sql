30-11-2024
ALTER TABLE `tbl_freelancer` ADD `rating` FLOAT NOT NULL AFTER `profile_picture`;
ALTER TABLE `tbl_freelancer` ADD `user_id` FLOAT NOT NULL AFTER `job_id`;
ALTER TABLE `tbl_client` ADD `user_id` INT(11) NOT NULL AFTER `id`;
ALTER TABLE `tbl_client` ADD `rating` FLOAT NOT NULL AFTER `category_id`;
ALTER TABLE `tbl_freelancer` ADD `email` VARCHAR(255) NOT NULL AFTER `username`;
ALTER TABLE `tbl_client` ADD `email` VARCHAR(255) NOT NULL AFTER `client_name`, ADD `username` VARCHAR(255) NOT NULL AFTER `email`, ADD `mobile_no` VARCHAR(255) NOT NULL AFTER `username`;

02/12/2024

ALTER TABLE `tbl_client` ADD `first_name` VARCHAR(255) NOT NULL AFTER `status`, ADD `middle_name` VARCHAR(255) NOT NULL AFTER `first_name`, ADD `last_name` VARCHAR(255) NOT NULL AFTER `middle_name`;