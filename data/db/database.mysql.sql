
DROP DATABASE IF EXISTS `phpbootcamp_crm`;
CREATE DATABASE `phpbootcamp_crm`;

DROP USER IF EXISTS 'phpbootcamp_crm'@'localhost';
CREATE USER 'phpbootcamp_crm'@'localhost' IDENTIFIED BY 'ZF4Fun&Profit';
GRANT ALL ON `phpbootcamp_crm`.* TO 'phpbootcamp_crm'@'localhost';

USE `phpbootcamp_crm`;
