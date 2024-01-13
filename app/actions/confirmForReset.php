<?php
require_once "../data/helpers.php";
require_once "../../lib/PHPMailer/src/Exception.php";
require_once "../../lib/PHPMailer/src/SMTP.php";
require_once '../../lib/PHPMailer/src/PHPMailer.php';

if(checkSession()){redirect("../pages/home.php");}

$token = $_GET["token"];
if(gettype($user=findQuery("select * from clients where `lostpassword_hash`=:lostpassword_hash",['lostpassword_hash'=>$token]))=="array"){
    $password=rand(100000, 999999);
    insertData("update clients set password=:password where lostpassword_hash=:lostpassword_hash", [ 'password'=>password_hash($password, PASSWORD_DEFAULT), 'lostpassword_hash'=>$token]);
    sendMessage($user['email'],"Органайзер: Изменение пароля!", "<h2>Ваш пароль был успешно изменен!</h2><p>Ваш логин: {$user['username']}</p><p>Ваш пароль: {$password}</p><p>Благодарим за использование Органайзера!</p><p>С уважением, </p><p>Коллектив Органазера</p><p>Важно: это сообщение было отправлено с электронного адреса, предназначенного только для рассылки исходящих сообщений. Пожалуйста не отвечайте на это сообщение.</p>");
    setNotification('notification-usually', "Новый пароль отправлен вам на email!");
}
else{
    setNotification('notification-error', "Ваш токен устарел!");
}
redirect("../../index.php");