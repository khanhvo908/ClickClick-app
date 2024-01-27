-- Active: 1699102041762@@127.0.0.1@3306

-- tạo database
CREATE DATABASE IF NOT EXISTS `DUAN`;
USE `DUAN`;

-- tạo bảng USERS
CREATE TABLE IF NOT EXISTS `USERS` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `EMAIL` VARCHAR(255) UNIQUE NOT NULL,
  `PASSWORD` VARCHAR(255) NOT NULL,
  `NAME` VARCHAR(255) NOT NULL,
  `ROLE` VARCHAR(255) NOT NULL,
  `AVATAR` VARCHAR(255) NOT NULL,
  `SDT` INT NOT NULL,
  `AVAILABLE` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`ID`)
);
-- bảng reset Password
CREATE TABLE IF NOT EXISTS `PASSWORD_RESETS` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `EMAIL` VARCHAR(255) NOT NULL,
  `IDUSER` INT NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`IDUSER`) REFERENCES `USERS`(`ID`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4;

-- bảng Notifications
CREATE TABLE IF NOT EXISTS `NOTIFICATIONS` (
  `ID` INT NOT NULL,
  `USERID` INT NOT NULL,
  `CONTENT` VARCHAR(255) NOT NULL,
  `TIME` TIMESTAMP,
  `ISREAD` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`USERID`) REFERENCES `USERS`(`ID`),
);
-- bảng Frendships
CREATE TABLE IF NOT EXISTS `FRIENDSHIPS` (
  `USERID` INT NOT NULL,
  `FRIENDSHIPID` INT NOT NULL,
  `STATUS` VARCHAR(255) NOT NULL,
  `TIME` TIMESTAMP,
  PRIMARY KEY (`USERID`, `FRIENDSHIPID`), 
  FOREIGN KEY (`IDUSER`) REFERENCES `USERS`(`ID`),
  FOREIGN KEY (`FRIENDSHIPID`) REFERENCES `USERS`(`ID`),
);

-- bảng chats
CREATE TABLE IF NOT EXISTS `CHATS` (
  `ID` INT NOT NULL,
  `SENDERID` INT NOT NULL,
  `RECEIVERID` INT NOT NULL,
  `CONTENT` VARCHAR(255) NOT NULL,
  `ISREAD` TINYINT(1) DEFAULT 0,
  `TIME` TIMESTAMP,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`SENDERID`) REFERENCES `USERS`(`ID`),
  FOREIGN KEY (`RECEIVERID`) REFERENCES `USERS`(`ID`),
);

-- bảng POSTS
CREATE TABLE IF NOT EXISTS `POSTS` (
  `ID` INT NOT NULL,
  `USERID` INT NOT NULL,
  `CONTENT` VARCHAR(255) NOT NULL,
  `LIKES` INT NOT NULL,
  `AVAILABLE` TINYINT(1) DEFAULT 0,
  `SHARE` INT,
  `IMAGE` VARCHAR(255) NOT NULL,
  `TIME` TIMESTAMP,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`USERID`) REFERENCES `USERS`(`ID`),
);
-- bảng LIKES
CREATE TABLE IF NOT EXISTS `LIKES` (
  `ID` INT NOT NULL,
  `USERID` INT NOT NULL,
  `POSTID` INT NOT NULL,
  `TIME` TIMESTAMP,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`USERID`) REFERENCES `USERS`(`ID`),
  FOREIGN KEY (`POSTID`) REFERENCES `POSTS`(`ID`),
);

-- bảng Reports
CREATE TABLE IF NOT EXISTS `REPORTS` (
  `ID` INT NOT NULL,
  `USERID` INT NOT NULL,
  `POSTID` INT NOT NULL,
  `CONTENT` VARCHAR(255) NOT NULL,
  `TIME` TIMESTAMP,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`USERID`) REFERENCES `USERS`(`ID`),
  FOREIGN KEY (`POSTID`) REFERENCES `POSTS`(`ID`),
);

