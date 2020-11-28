CREATE DATABASE IF NOT EXISTS `sso`;

USE `sso`;

--
-- Table structure for table `oc_user`
--

DROP TABLE IF EXISTS `user_ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;

CREATE TABLE `user_ip` (
  `user_ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`user_ip_id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

ALTER TABLE `user_ip`
ADD CONSTRAINT `fk_user_user_ip`
FOREIGN KEY (`user_id`) REFERENCES `oc_user`(`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
