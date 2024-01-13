<?php
/*Подключение к БД*/
const DB_HOST = '';                    // The host/ip to your SQL server
const DB_USER = '';                        // The username to connect with
const DB_PASS = '';                        // The password
const DB_NAME = '';                        // Database name

/*Кол-во дней для предупреждения пользователя*/
const limitDays=7;

/*Размер аватара в мегабайтах*/
const sizeAvatar=1;
/*Допустимые форматы для аватара*/
const accessTypesAvatar=['image/jpeg', 'image/png'];

/*Размер документов для задач в мегабайтах*/
const sizeDoc=10;
/*Допустимые форматы для документов*/
const accessTypesDoc=['application/pdf'];
