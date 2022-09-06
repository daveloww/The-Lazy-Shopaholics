drop database if exists lazy_shopaholics;
create database lazy_shopaholics;
use lazy_shopaholics;

create table account (
    id integer auto_increment primary key,
    email varchar(1000),
    username varchar(1000),
    pass varchar(1000)
);

create table cart (
    id integer auto_increment primary key,
    account_id integer not null,
    constraint fk_account foreign key (account_id) references account(id),
    p_id varchar(2000),
    p_name varchar(2000),
    photo varchar(1000),
    p_url varchar(1000),
    price float,
    ecommerce varchar(1000)
);

create table favourite (
    id integer auto_increment primary key,
    account_id integer not null,
    constraint fk_favaccount foreign key (account_id) references account(id),
    p_id varchar(2000),
    p_name varchar(1000),
    photo varchar(1000),
    p_url varchar(1000),
    price float,
    ecommerce varchar(1000)
);