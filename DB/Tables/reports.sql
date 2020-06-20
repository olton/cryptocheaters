CREATE TABLE reports (
  report_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  report_user_id BIGINT(20) UNSIGNED NOT NULL,
  report_created DATETIME NOT NULL DEFAULT current_timestamp(),
  report_updated TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  report_type INT(11) NOT NULL,
  report_tags VARCHAR(1000) DEFAULT NULL,
  report_name VARCHAR(100) NOT NULL,
  report_link VARCHAR(1000) DEFAULT NULL,
  report_desc MEDIUMTEXT DEFAULT NULL,
  PRIMARY KEY (report_id)
)
ENGINE = MYISAM,
AUTO_INCREMENT = 348,
AVG_ROW_LENGTH = 4334,
CHARACTER SET utf8,
CHECKSUM = 0,
COLLATE utf8_general_ci,
ROW_FORMAT = DYNAMIC;

ALTER TABLE reports 
  ADD INDEX i_reports_type(report_type);

ALTER TABLE reports 
  ADD INDEX i_reports_user_id(report_user_id);