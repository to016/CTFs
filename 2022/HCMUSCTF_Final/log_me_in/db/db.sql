create database if not exists myDB;

use myDB;

create table if not exists users(
   usrname varchar(50) not null,
   pssword varchar(40) not null,
   is_admin tinyint not null 
);

insert into users values ('admin', md5('fake_password'), 1);

create table if not exists reset_password(
   id varchar(36) not null,
   token varchar(40) not null,
   PRIMARY KEY (id)
)