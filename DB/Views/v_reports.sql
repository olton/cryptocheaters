CREATE 
VIEW v_reports
AS
	SELECT
	  `t1`.`report_id` AS `report_id`,
	  `t1`.`report_user_id` AS `report_user_id`,
	  `t1`.`report_created` AS `report_created`,
	  `t1`.`report_updated` AS `report_updated`,
	  `t1`.`report_type` AS `report_type`,
	  `t1`.`report_tags` AS `report_tags`,
	  `t1`.`report_name` AS `report_name`,
	  `t1`.`report_link` AS `report_link`,
	  `t1`.`report_desc` AS `report_desc`,
	  `t2`.`report_type_id` AS `report_type_id`,
	  `t2`.`report_type_name` AS `report_type_name`,
	  `t3`.`id` AS `id`,
	  `t3`.`nickname` AS `nickname`,
	  `t3`.`email` AS `email`,
	  `t3`.`password` AS `password`,
	  `t3`.`created` AS `created`,
	  `t3`.`updated` AS `updated`,
	  `t3`.`admin` AS `admin`,
	  (SELECT
	      COUNT(0)
	    FROM `likes` `l`
	    WHERE `t1`.`report_id` = `l`.`report_id`) AS `votes`
	FROM ((`reports` `t1`
	  LEFT JOIN `reports_types` `t2`
	    ON (`t1`.`report_type` = `t2`.`report_type_id`))
	  LEFT JOIN `users` `t3`
	    ON (`t1`.`report_user_id` = `t3`.`id`));