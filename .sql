-- Pages/Coupons/Index
ALTER TABLE `brands` ADD `is_vendor` VARCHAR(100) NULL DEFAULT NULL AFTER `deleted_at`,
ADD `statusupdate_by` BIGINT NULL DEFAULT NULL AFTER `status`,
ADD `statusupdate_at` TIMESTAMP NULL DEFAULT NULL AFTER `statusupdate_by`,
ADD `created_by` BIGINT(100) NULL DEFAULT NULL AFTER `is_vendor`,
ADD `who_edited` VARCHAR(100) NULL DEFAULT NULL AFTER `created_by`,
ADD `edited_by` BIGINT(100) NULL DEFAULT NULL AFTER `who_edited`,
ADD `edited_at` TIMESTAMP NULL DEFAULT NULL AFTER `edited_by`;


ALTER TABLE `brands`
    ADD COLUMN `statusupdate_by` bigint(20) DEFAULT NULL AFTER `status`,
    ADD COLUMN `statusupdate_at` timestamp NULL DEFAULT NULL AFTER `statusupdate_by`,
    ADD COLUMN `who_create` varchar(100) DEFAULT NULL,
    ADD COLUMN `who_delete` varchar(100) DEFAULT NULL,
    ADD COLUMN `deleted_at` timestamp NULL DEFAULT NULL,
    ADD COLUMN `is_vendor` varchar(100) DEFAULT NULL,
    ADD COLUMN `created_by` bigint(100) DEFAULT NULL,
    ADD COLUMN `who_edited` varchar(100) DEFAULT NULL,
    ADD COLUMN `edited_by` bigint(100) DEFAULT NULL,
    ADD COLUMN `edited_at` timestamp NULL DEFAULT NULL,
    ADD COLUMN `is_deleted` tinyint(1) DEFAULT 0;
