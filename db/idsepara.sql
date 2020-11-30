CREATE TABLE `user_login` (
  `user_login_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(96) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `total` int(4) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`user_login_id`),
  KEY `email` (`email`),
  KEY `ip` (`ip`)
);

CREATE TABLE `user_ip` (
  `user_ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`user_ip_id`),
  KEY `ip` (`ip`)
);

CREATE TABLE `user_group` (
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`user_group_id`)
);

CREATE TABLE `address` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  -- `customer_id` int(11) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `company` varchar(40) NOT NULL,
  `address_1` varchar(128) NOT NULL,
  `address_2` varchar(128) NOT NULL,
  `city` varchar(128) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  -- `country_id` int(11) NOT NULL DEFAULT 0,
  `zone_id` int(11) NOT NULL DEFAULT 0,
  `custom_field` text NOT NULL,
  PRIMARY KEY (`address_id`)
  -- ,KEY `customer_id` (`customer_id`)
);

CREATE TABLE `country` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `iso_code_2` varchar(2) NOT NULL,
  `iso_code_3` varchar(3) NOT NULL,
  `address_format` text NOT NULL,
  `postcode_required` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`country_id`)
);

CREATE TABLE `zone` (
  `zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `code` varchar(32) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`zone_id`)
);

-- CREATE TABLE `oc_customer` (
--   `customer_id` int(11) NOT NULL AUTO_INCREMENT,
--   `customer_group_id` int(11) NOT NULL,
--   `store_id` int(11) NOT NULL DEFAULT 0,
--   `language_id` int(11) NOT NULL,
--   `firstname` varchar(32) NOT NULL,
--   `lastname` varchar(32) NOT NULL,
--   `email` varchar(96) NOT NULL,
--   `telephone` varchar(32) NOT NULL,
--   `fax` varchar(32) NOT NULL,
--   `password` varchar(40) NOT NULL,
--   `salt` varchar(9) NOT NULL,
--   `cart` text DEFAULT NULL,
--   `wishlist` text DEFAULT NULL,
--   `newsletter` tinyint(1) NOT NULL DEFAULT 0,
--   `address_id` int(11) NOT NULL DEFAULT 0,
--   `custom_field` text NOT NULL,
--   `ip` varchar(40) NOT NULL,
--   `status` tinyint(1) NOT NULL,
--   `safe` tinyint(1) NOT NULL,
--   `token` text NOT NULL,
--   `code` varchar(40) NOT NULL,
--   `date_added` datetime NOT NULL,
--   PRIMARY KEY (`customer_id`)
-- ) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- CREATE TABLE `oc_customer_ip` (
--   `customer_ip_id` int(11) NOT NULL AUTO_INCREMENT,
--   `customer_id` int(11) NOT NULL,
--   `ip` varchar(40) NOT NULL,
--   `date_added` datetime NOT NULL,
--   PRIMARY KEY (`customer_ip_id`),
--   KEY `ip` (`ip`)
-- ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE `oc_customer_login` (
--   `customer_login_id` int(11) NOT NULL AUTO_INCREMENT,
--   `email` varchar(96) NOT NULL,
--   `ip` varchar(40) NOT NULL,
--   `total` int(4) NOT NULL,
--   `date_added` datetime NOT NULL,
--   `date_modified` datetime NOT NULL,
--   PRIMARY KEY (`customer_login_id`),
--   KEY `email` (`email`),
--   KEY `ip` (`ip`)
-- ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- CREATE TABLE `oc_user` (
--   `user_id` int(11) NOT NULL AUTO_INCREMENT,
--   `user_group_id` int(11) NOT NULL,
--   `username` varchar(20) NOT NULL,
--   `password` varchar(40) NOT NULL,
--   `salt` varchar(9) NOT NULL,
--   `firstname` varchar(32) NOT NULL,
--   `lastname` varchar(32) NOT NULL,
--   `email` varchar(96) NOT NULL,
--   `image` varchar(255) NOT NULL,
--   `code` varchar(40) NOT NULL,
--   `ip` varchar(40) NOT NULL,
--   `status` tinyint(1) NOT NULL,
--   `date_added` datetime NOT NULL,
--   `store` varchar(45) DEFAULT NULL,
--   PRIMARY KEY (`user_id`)
-- ) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- CREATE TABLE `oc_user_group` (
--   `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
--   `name` varchar(64) NOT NULL,
--   `permission` text NOT NULL,
--   PRIMARY KEY (`user_group_id`)
-- ) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;