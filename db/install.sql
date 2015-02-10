CREATE TABLE IF NOT EXISTS extended_errors (
code VARCHAR(32) NOT NULL,
text VARCHAR(2048),
file VARCHAR(1024),
line INT(11),
user_id VARCHAR(32),
ip VARCHAR(45),
xhr ENUM('true','false') NOT NULL DEFAULT 'false',
requested_url VARCHAR(2048),
request_data text,
mkdate INT(11),
INDEX (`user_id`),
INDEX (code)
);