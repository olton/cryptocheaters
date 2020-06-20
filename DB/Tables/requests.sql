CREATE TABLE requests (
  request_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  request_uid VARCHAR(50) NOT NULL,
  request_date TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  request_name VARCHAR(255) NOT NULL DEFAULT '',
  request_email VARCHAR(255) NOT NULL DEFAULT '',
  request_type ENUM('VERIFICATION','SERTIFICATION') NOT NULL DEFAULT 'VERIFICATION',
  request_tr_type ENUM('BTC','ETH') NOT NULL DEFAULT 'BTC',
  request_tr_detail VARCHAR(255) NOT NULL DEFAULT '',
  request_tr_date DATETIME NOT NULL DEFAULT current_timestamp(),
  request_done TINYINT(1) DEFAULT 0,
  request_complete DATETIME DEFAULT NULL,
  request_desc TEXT DEFAULT NULL,
  PRIMARY KEY (request_id)
)
ENGINE = MYISAM,
AUTO_INCREMENT = 3,
AVG_ROW_LENGTH = 236,
CHARACTER SET utf8,
CHECKSUM = 0,
COLLATE utf8_general_ci,
ROW_FORMAT = FIXED;

ALTER TABLE requests 
  ADD INDEX IDX_requests_date(request_date);

ALTER TABLE requests 
  ADD UNIQUE INDEX UK_request_uid(request_uid);