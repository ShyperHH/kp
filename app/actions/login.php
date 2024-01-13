<?php
require_once "../../lib/PHPMailer/src/Exception.php";
require_once "../../lib/PHPMailer/src/SMTP.php";
require_once '../../lib/PHPMailer/src/PHPMailer.php';
require_once "../data/helpers.php";

checkRequest();
$username = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($_POST['username'])))))) ?? null;

$password = $_POST['password'] ?? null;
$params=['username'=>$username];
$user = findQuery("SELECT * FROM clients WHERE `username`=:username",$params);

if(!$user) {
    setValidationError("validationLogin",'username', "Пользователь $username не найден");
    setNotification('notification-error', "Исправьте ошибки в вводимых данных!");
    redirect("../../index.php");
}
if(!password_verify($_POST['password'], $user['password'])){
    setNotification('notification-error', "Исправьте ошибки в вводимых данных!");
    setOldValue("username", $username);
    setValidationError("validationLogin",'password', "Неверный пароль");
    redirect("../../index.php");
}
if($user['activation_hash']!=""){
    setNotification('notification-error', "Пользователь $username не активирован. Сообщение об активации направлено на почту.");
    //setMessage('username', "Пользователь $username не активирован. Сообщение об активации направлено на почту.");
    //sendMessage($user['email'],"Органайзер: Подтверждение Email!", "<h2>Подтверждение Email!</h2><p>Ваш логин: $username</p><p>Ваш пароль: $password</p><p>Нажмите <a href='https://starichky.myarena.site/kp/app/actions/confirmForAcvation.php?token={$user['activation_hash']}'>здесь</a> для активации вашего аккаунта.</p><p>Благодарим за использование Органайзера!</p><p>С уважением, </p><p>Коллектив Органазера</p><p>Важно: это сообщение было отправлено с электронного адреса, предназначенного только для рассылки исходящих сообщений. Пожалуйста не отвечайте на это сообщение.</p>");
    redirect("../../index.php");
}
setcookie("username", $user['username'], time()+86400*7, '/');
setcookie("password", $_POST['password'], time()+86400*7, '/');
setNotification('notification-success', "Успешный вход!");
$_SESSION['user']['id']=$user['id'];
redirect('../pages/home.php');