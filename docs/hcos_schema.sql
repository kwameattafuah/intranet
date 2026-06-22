-- ================================================================
-- HCOS Service Requests Schema
-- Human Capital & Office Services — GACL Intranet
-- ================================================================

CREATE TABLE IF NOT EXISTS `hcos_requests` (
  `id`           INT(11) NOT NULL AUTO_INCREMENT,
  `request_code` VARCHAR(20) NOT NULL UNIQUE,
  `type`         VARCHAR(50) NOT NULL COMMENT 'travel|letter|loan|room|office',
  `staff_id`     INT(11) DEFAULT NULL,
  `staff_name`   VARCHAR(200) NOT NULL,
  `staff_email`  VARCHAR(200) DEFAULT NULL,
  `department`   VARCHAR(200) DEFAULT NULL,
  `status`       ENUM('pending','approved','declined','completed') NOT NULL DEFAULT 'pending',
  `submitted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `handled_by`   VARCHAR(200) DEFAULT NULL,
  `admin_notes`  TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Travel Requests
CREATE TABLE IF NOT EXISTS `hcos_travel` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `request_id`    INT(11) NOT NULL,
  `travel_type`   ENUM('inter_regional','international') NOT NULL DEFAULT 'inter_regional',
  `destination`   VARCHAR(200) NOT NULL,
  `airport_code`  VARCHAR(10) DEFAULT NULL,
  `purpose`       TEXT NOT NULL,
  `departure_date` DATE NOT NULL,
  `return_date`   DATE NOT NULL,
  `num_travellers` INT(11) NOT NULL DEFAULT 1,
  `travellers`    TEXT DEFAULT NULL COMMENT 'names of other travellers',
  `accommodation` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1=required',
  `transport`     TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1=required',
  `per_diem`      TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1=required',
  `extra_notes`   TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Letter Requests
CREATE TABLE IF NOT EXISTS `hcos_letters` (
  `id`           INT(11) NOT NULL AUTO_INCREMENT,
  `request_id`   INT(11) NOT NULL,
  `letter_type`  ENUM('visa_intro','reference','other') NOT NULL,
  `addressed_to` VARCHAR(200) DEFAULT NULL COMMENT 'embassy / institution name',
  `country`      VARCHAR(100) DEFAULT NULL COMMENT 'for visa letters',
  `purpose`      TEXT NOT NULL,
  `urgency`      ENUM('normal','urgent') NOT NULL DEFAULT 'normal',
  `extra_notes`  TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Loan Requests
CREATE TABLE IF NOT EXISTS `hcos_loans` (
  `id`           INT(11) NOT NULL AUTO_INCREMENT,
  `request_id`   INT(11) NOT NULL,
  `loan_type`    ENUM('salary_advance','car_loan','personal_loan','emergency_loan') NOT NULL,
  `amount`       DECIMAL(12,2) DEFAULT NULL,
  `reason`       TEXT NOT NULL,
  `repayment`    VARCHAR(100) DEFAULT NULL COMMENT 'proposed repayment period',
  `extra_notes`  TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Office Service Requests
CREATE TABLE IF NOT EXISTS `hcos_office` (
  `id`           INT(11) NOT NULL AUTO_INCREMENT,
  `request_id`   INT(11) NOT NULL,
  `service_type` ENUM('stationery','maintenance','it_support','transport','other') NOT NULL,
  `description`  TEXT NOT NULL,
  `location`     VARCHAR(200) DEFAULT NULL,
  `urgency`      ENUM('normal','urgent') NOT NULL DEFAULT 'normal',
  `extra_notes`  TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
