DROP DATABASE IF EXISTS dating;
create database dating;
DROP USER IF EXISTS FADating;
CREATE USER 'FADating'@'%' IDENTIFIED BY 'password';

USE dating;

CREATE TABLE `fa_dating_users` (
    `fa_dating_username` varchar(25) NOT NULL,
    `fa_dating_password` varchar(32) NOT NULL,
    `fa_dating_pin_number` varchar(12) NOT NULL,
    `fa_dating_yob` int(4) NOT NULL,
    `fa_dating_interest`varchar(6) NOT NULL,
    `fa_dating_id_card` int(4) NOT NULL,
    `fa_dating_name` varchar (20) NOT NULL 
);

CREATE TABLE `fa_dating_comments` (
    `fa_dating_username` varchar(10) NOT NULL,
    `fa_dating_comment` varchar(100) NOT NULL
);

INSERT INTO `fa_dating_users` VALUES
('admin', md5('admin_password'), '123456789103', 2000,'female', '1234', 'sugardady');

INSERT INTO `fa_dating_comments` VALUES 
('admin', 'just want to look for some one special');