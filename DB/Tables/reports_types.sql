CREATE TABLE reports_types (
  report_type_id INT(10) UNSIGNED NOT NULL,
  report_type_name VARCHAR(50) NOT NULL,
  PRIMARY KEY (report_type_id)
)
ENGINE = MYISAM,
AVG_ROW_LENGTH = 20,
CHARACTER SET utf8,
CHECKSUM = 0,
COLLATE utf8_general_ci,
ROW_FORMAT = DYNAMIC;