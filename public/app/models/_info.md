
# info about the structrure of the models

## user fields; insert / update sql queries

Insert user:

    INSERT INTO user
    ( id,
      prefix,
      first_name,
      last_name,
      email,
      father_name,
      registration_number,
      sector_id,
      specialty,
      belonging_school,
      working_shcool,
      position_type_id,
      phone,
      password,
      expiration,
      otp,
      otp_expiration,
      activation,
      creation,
    active) VALUES (:id,
      :prefix,
      :first_name,
      :last_name,
      :email,
      :father_name,
      :registration_number,
      :sector_id,
      :specialty,
      :belonging_school,
      :working_shcool,
      :position_type_id,
      :phone,
      :password,
      :expiration,
      :otp,
      :otp_expiration,
      :activation,
      :creation,
    :active )


Update user

    UPDATE user
    SET prefix = :prefix,
    first_name = :first_name,
    last_name = :last_name,
    email = :email,
    father_name = :father_name,
    registration_number = :registration_number,
    sector_id = :sector_id,
    specialty = :specialty,
    belonging_school = :belonging_school,
    working_shcool = :working_shcool,
    position_type_id = :position_type_id,
    phone = :phone,
    password = :password,
    expiration = :expiration,
    otp = :otp,
    otp_expiration = :otp_expiration,
    activation = :activation,
    creation = :creation,
    active = :active,


## record_types

document forms -> json

request = {
  user_id -> user: {
    ...
  }
  date: ...
  body: ...
  media: [ ... array of media-id(s) ]
}

penalty = {
  user_id -> user: {
    ...
  },
  date:
  time:
  subject: 
  of_student:
  place:
  president:
  members: [ list ]
  reason:
  apology:
  decision:
}


## records

id
user_id
type
source_json


## record_media

record_id
media_id


USER menu
---
profile -> name, hello, last-login, do ...
profile/information
profile/requests (all)
profile/request/id (one)

Admin menu
---
admin -> requests (all), MODAL: protocol
admin/request/id -> request (one)
admin/iinvite





# callable/dynamic select options


<?php

class myclass {
    static function say_hello()
    {
        echo "Hello!\n";
    }
}

$classname = "myclass";

call_user_func(array($classname, 'say_hello'));
call_user_func($classname .'::say_hello');

$myobject = new myclass();

call_user_func(array($myobject, 'say_hello'));

?>




# APPlication

\ controllers
  [ ] App
  [*] Auth

\ extends
  [ ] Cache_service
  [*] App_manager
  [*] App_user
  [ ] SendMail_service
  [*] Form_builder


\ models
  [ ] Access_model
  [ ] History_model



# DataBase Structure



    SET NAMES utf8;
    SET time_zone = '+00:00';
    SET foreign_key_checks = 0;
    SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

    SET NAMES utf8mb4;

    DROP TABLE IF EXISTS `history`;
    CREATE TABLE `history` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `type` smallint(6) NOT NULL,
      `message` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL,
      `note` text COLLATE utf8mb4_unicode_ci,
      `ip` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      CONSTRAINT `history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


    DROP TABLE IF EXISTS `media`;
    CREATE TABLE `media` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT,
      `title` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
      `user_id` int(11) NOT NULL,
      `path` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
      `type` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'media-type',
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      CONSTRAINT `media_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


    DROP TABLE IF EXISTS `petition`;
    CREATE TABLE `petition` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `type_id` smallint(6) NOT NULL,
      `form_structure` text COLLATE utf8mb4_unicode_ci NOT NULL,
      `protocol` int(11) DEFAULT NULL,
      `signature` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
      `record_date` date DEFAULT NULL,
      `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
      `update_date` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `signature` (`signature`),
      KEY `user_id` (`user_id`),
      CONSTRAINT `petition_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


    DROP TABLE IF EXISTS `petition_media`;
    CREATE TABLE `petition_media` (
      `petition_id` bigint(20) NOT NULL,
      `media_id` bigint(20) NOT NULL,
      KEY `petition_id` (`petition_id`),
      KEY `media_id` (`media_id`),
      CONSTRAINT `petition_media_ibfk_1` FOREIGN KEY (`petition_id`) REFERENCES `petition` (`id`),
      CONSTRAINT `petition_media_ibfk_2` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


    DROP TABLE IF EXISTS `role`;
    CREATE TABLE `role` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `alias` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
      `label` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


    DROP TABLE IF EXISTS `user`;
    CREATE TABLE `user` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `prefix` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `first_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
      `last_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
      `email` varchar(48) COLLATE utf8mb4_unicode_ci NOT NULL,
      `father_name` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `registration_number` bigint(20) DEFAULT NULL COMMENT 'AM',
      `sector` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'sector_id + speciality',
      `belonging_school` varchar(96) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `position` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'chief, permanent, deputy etc',
      `phone` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `password` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `role_id` int(11) DEFAULT NULL,
      `expiration` datetime DEFAULT NULL COMMENT 'account expiration datetime',
      `otp` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'one time password for reset password',
      `otp_expiration` datetime DEFAULT NULL,
      `invitation` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'invitation code',
      `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'creation datetime',
      `update_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `active` smallint(6) NOT NULL DEFAULT '0' COMMENT 'account status flag; 1=active 0=inactive',
      PRIMARY KEY (`id`),
      KEY `role_id` (`role_id`),
      CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

