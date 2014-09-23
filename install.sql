# Dump of table account_holders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `account_holders`;

CREATE TABLE `account_holders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_address` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `salutation` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) NOT NULL DEFAULT '',
  `surname` varchar(255) DEFAULT NULL,
  `paid_day` int(11) DEFAULT NULL,
  `currency` varchar(3) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dump of table default_month_expenditures
# ------------------------------------------------------------

DROP TABLE IF EXISTS `default_month_expenditures`;

CREATE TABLE `default_month_expenditures` (
  `id` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `currency` varchar(3) NOT NULL,
  `account_holder_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `account_holder_id` (`account_holder_id`),
  CONSTRAINT `default_month_expenditures_ibfk_1` FOREIGN KEY (`account_holder_id`) REFERENCES `account_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table default_oneoff_month_expenditures
# ------------------------------------------------------------

DROP TABLE IF EXISTS `default_oneoff_month_expenditures`;

CREATE TABLE `default_oneoff_month_expenditures` (
  `id` varchar(255) NOT NULL DEFAULT '',
  `due_date` date NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `default_oneoff_month_expenditures_ibfk_1` FOREIGN KEY (`id`) REFERENCES `default_month_expenditures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table default_recurring_month_expenditures
# ------------------------------------------------------------

DROP TABLE IF EXISTS `default_recurring_month_expenditures`;

CREATE TABLE `default_recurring_month_expenditures` (
  `id` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  CONSTRAINT `default_recurring_month_expenditures_ibfk_1` FOREIGN KEY (`id`) REFERENCES `default_month_expenditures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table month_expenditure_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `month_expenditure_items`;

CREATE TABLE `month_expenditure_items` (
  `id` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `price` float NOT NULL,
  `paid_amount` float DEFAULT NULL,
  `month_expenditure_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `header_relation` (`month_expenditure_id`),
  CONSTRAINT `month_expenditure_items_ibfk_1` FOREIGN KEY (`month_expenditure_id`) REFERENCES `month_expenditures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table month_expenditures
# ------------------------------------------------------------

DROP TABLE IF EXISTS `month_expenditures`;

CREATE TABLE `month_expenditures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_date` date NOT NULL,
  `income` float NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT '',
  `total_paid` float NOT NULL,
  `total_outgoing` float NOT NULL,
  `account_holder_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_holder_id` (`account_holder_id`),
  CONSTRAINT `month_expenditures_ibfk_1` FOREIGN KEY (`account_holder_id`) REFERENCES `account_holders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table savings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `savings`;

CREATE TABLE `savings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `target_date` date DEFAULT NULL,
  `target_amount` float NOT NULL,
  `saved_amount` float DEFAULT NULL,
  `account_holder_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_holder_id` (`account_holder_id`),
  CONSTRAINT `savings_ibfk_1` FOREIGN KEY (`account_holder_id`) REFERENCES `account_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_address` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
