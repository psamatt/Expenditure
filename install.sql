CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_address` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `paid_day` int(11) DEFAULT NULL,
  `salt` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `month_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_date` date NOT NULL,
  `month_income` float NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `month_header_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `month_expenditure_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `month_expenditure_template_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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

 -- 1.1 feature

CREATE TABLE `savings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `target_date` date DEFAULT NULL,
  `target_amount` float NOT NULL,
  `saved_amount` float DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `savings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;