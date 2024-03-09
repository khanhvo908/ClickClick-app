-- Active: 1708618356731@@127.0.0.1@3306@duandemo
CREATE DATABASE DUANDEMO;

USE `DUANDEMO`;

-- tạo bảng USERS
CREATE TABLE
  IF NOT EXISTS `USERS` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `EMAIL` VARCHAR(255) UNIQUE NOT NULL,
    `PASSWORD` VARCHAR(255),
    `NAME` VARCHAR(255),
    `ROLE` VARCHAR(255),
    `AVATAR` VARCHAR(255),
    `SDT` INT,
    `AVAILABLE` TINYINT (1) DEFAULT 0,
    `OTP` VARCHAR(10),
    `otp_expiration` INT,
    `TEXT` VARCHAR (255),
    PRIMARY KEY (`ID`)
  );

-- bảng Notifications
CREATE TABLE
  IF NOT EXISTS `NOTIFICATIONS` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `USERID` INT NOT NULL,
    `CONTENT` VARCHAR(255) NOT NULL,
    `TIME` TIMESTAMP,
    `RECEIVERID` INT,
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`USERID`) REFERENCES `USERS` (`ID`)
  );

-- bảng Frendships
CREATE TABLE
  IF NOT EXISTS `FRIENDSHIPS` (
    `USERID` INT NOT NULL,
    `FRIENDSHIPID` INT NOT NULL,
    `STATUS` VARCHAR(255) NOT NULL,
    `TIME` TIMESTAMP,
    PRIMARY KEY (`USERID`, `FRIENDSHIPID`),
    FOREIGN KEY (`USERID`) REFERENCES `USERS` (`ID`),
    FOREIGN KEY (`FRIENDSHIPID`) REFERENCES `USERS` (`ID`)
  );

-- bảng POSTS
CREATE TABLE
  IF NOT EXISTS `POSTS` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `USERID` INT NOT NULL,
    `CONTENT` VARCHAR(255),
    `LIKES` INT DEFAULT 0,
    `AVAILABLE` TINYINT (1) DEFAULT 0,
    `IMAGE` VARCHAR(255),
    `TIME` TIMESTAMP,
    `NAME` VARCHAR(255),
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`USERID`) REFERENCES `USERS` (`ID`)
  );

-- bảng chats
CREATE TABLE
  IF NOT EXISTS `CHATS` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `SENDERID` INT NOT NULL,
    `RECEIVERID` INT NOT NULL,
    `POSTID` INT,
    `CONTENT` VARCHAR(255),
    `TIME` TIMESTAMP,
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`SENDERID`) REFERENCES `USERS` (`ID`),
    FOREIGN KEY (`RECEIVERID`) REFERENCES `USERS` (`ID`),
    FOREIGN KEY (`POSTID`) REFERENCES `POSTS` (`ID`)
  );

-- bảng LIKES
CREATE TABLE
  IF NOT EXISTS `LIKES` (
    `ID` INT AUTO_INCREMENT,
    `USERID` INT NOT NULL,
    `POSTID` INT NOT NULL,
    `TIME` TIMESTAMP,
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`USERID`) REFERENCES `USERS` (`ID`),
    FOREIGN KEY (`POSTID`) REFERENCES `POSTS` (`ID`)
  );

-- bảng Reports
CREATE TABLE
  IF NOT EXISTS `REPORTS` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `USERID` INT NOT NULL,
    `POSTID` INT NOT NULL,
    `CONTENT` VARCHAR(255) NOT NULL,
    `TIME` TIMESTAMP,
    PRIMARY KEY (`ID`),
    FOREIGN KEY (`USERID`) REFERENCES `USERS` (`ID`),
    FOREIGN KEY (`POSTID`) REFERENCES `POSTS` (`ID`)
  );
