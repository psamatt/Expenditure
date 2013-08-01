CREATE TABLE `month_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_date` date NOT NULL,
  `month_income` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `month_expenditure_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `month_expenditure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `price` float NOT NULL,
  `amount_paid` float DEFAULT NULL,
  `header_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `header_relation` (`header_id`),
  CONSTRAINT `header_relation` FOREIGN KEY (`header_id`) REFERENCES `month_header` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;