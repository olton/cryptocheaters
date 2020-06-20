CREATE TABLE evidences (
  evidence_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  evidence_image LONGTEXT DEFAULT NULL,
  evidence_desc VARCHAR(100) DEFAULT NULL,
  evidence_report BIGINT(20) UNSIGNED NOT NULL,
  evidence_user BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (evidence_id)
)
ENGINE = MYISAM,
AUTO_INCREMENT = 479,
AVG_ROW_LENGTH = 250114,
CHARACTER SET utf8,
CHECKSUM = 0,
COLLATE utf8_general_ci,
ROW_FORMAT = DYNAMIC;

ALTER TABLE evidences 
  ADD INDEX i_evidences_report_id(evidence_report);

ALTER TABLE evidences 
  ADD INDEX i_evidences_user_id(evidence_user);