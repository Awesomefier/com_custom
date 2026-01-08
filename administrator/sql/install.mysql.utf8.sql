CREATE TABLE IF NOT EXISTS `#__custom_` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`state` TINYINT(1)  NULL  DEFAULT 1,
`checked_out` INT(11)  UNSIGNED,
`checked_out_time` DATETIME NULL  DEFAULT NULL ,
`created_by` INT(11)  NULL  DEFAULT 0,
`modified_by` INT(11)  NULL  DEFAULT 0,
`lft` INT(11)  NULL  DEFAULT 0,
`rgt` INT(11)  NULL  DEFAULT 0,
`level` INT(10)  NULL  DEFAULT 0,
`access` TINYINT(3)  NULL  DEFAULT 0,
`path` VARCHAR(255)  NULL  DEFAULT "",
`parent_id` INT(11)  NULL  DEFAULT 0,
`title` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255) COLLATE utf8_bin NULL ,
`xtextfieldx` VARCHAR(255)  NULL  DEFAULT "",
`xtextareax` TEXT NULL ,
`xcheckboxx` VARCHAR(255)  NULL  DEFAULT "",
`xcheckboxesx` VARCHAR(255)  NULL  DEFAULT "",
`xlistx` VARCHAR(255)  NULL  DEFAULT "",
`xcomboboxx` VARCHAR(255)  NULL  DEFAULT "",
`xgrouplistx` VARCHAR(255)  NULL  DEFAULT "",
`xmediamanagerx` TEXT NULL ,
`xaccesiblemediamanagerx` TEXT NULL ,
`ximagelistx` VARCHAR(255)  NULL  DEFAULT "",
`xsqlqueryx` TEXT NULL ,
PRIMARY KEY (`id`)
,KEY `idx_state` (`state`)
,KEY `idx_checked_out` (`checked_out`)
,KEY `idx_created_by` (`created_by`)
,KEY `idx_modified_by` (`modified_by`)
,KEY `idx_access` (`access`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `content_history_options`)
SELECT * FROM ( SELECT 'xDisplayNameSingularx','com_custom.xinternalnamesingularx','{"special":{"dbtable":"#__custom_","key":"id","type":"XinternalnamesingularxTable","prefix":"Joomla\\\\Component\\\\Ust\\\\Administrator\\\\Table\\\\"}}', CASE 
                                    WHEN 'rules' is null THEN ''
                                    ELSE ''
                                    END as rules, CASE 
                                    WHEN 'field_mappings' is null THEN ''
                                    ELSE ''
                                    END as field_mappings, '{"formFile":"administrator\/components\/com_custom\/forms\/xinternalnamesingularx.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"xtextareax"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_custom.xinternalnamesingularx')
) LIMIT 1;
