CREATE TABLE likes (
  user_id BIGINT(20) UNSIGNED NOT NULL,
  report_id BIGINT(20) UNSIGNED NOT NULL,
  like_type ENUM('like','dislike') DEFAULT NULL,
  PRIMARY KEY (user_id, report_id)
)
ENGINE = MYISAM,
AVG_ROW_LENGTH = 18,
CHARACTER SET utf8,
CHECKSUM = 0,
COLLATE utf8_general_ci,
ROW_FORMAT = FIXED;