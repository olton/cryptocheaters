CREATE TABLE users (
  id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  nickname VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL,
  created DATETIME NOT NULL DEFAULT current_timestamp(),
  updated TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  admin TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
)
ENGINE = MYISAM,
AUTO_INCREMENT = 120,
AVG_ROW_LENGTH = 94,
CHARACTER SET utf8,
CHECKSUM = 0,
COLLATE utf8_general_ci,
ROW_FORMAT = DYNAMIC;

ALTER TABLE users 
  ADD INDEX i_users_created(created);

ALTER TABLE users 
  ADD UNIQUE INDEX ui_users_email(email);

ALTER TABLE users 
  ADD UNIQUE INDEX ui_users_nickname(nickname);