<?php
require_once "../../lib/PHPMailer/src/Exception.php";
require_once "../../lib/PHPMailer/src/SMTP.php";
require_once '../../lib/PHPMailer/src/PHPMailer.php';
require_once "../data/helpers.php";
checkRequest();

$usernameOrEmail = $_POST['usernameOrEmail'];
$user = findQuery("select * from clients where username=:usernameOrEmail or email=:usernameOrEmail", ['usernameOrEmail'=>$usernameOrEmail,'usernameOrEmail'=>$usernameOrEmail]);
if($user==""){
    setValidationError("validationLostPass", 'usernameOrEmail', "Логин/email: $usernameOrEmail не найден");
    setNotification('notification-error', "Исправьте ошибки в вводимых данных!");
    redirect("../pages/lostPassword.php");
}
$params=['lostpassword_hash'=>password_hash(rand(100000,999999), PASSWORD_DEFAULT), 'usernameOrEmail'=>$usernameOrEmail];

(filter_var($usernameOrEmail, filter:FILTER_VALIDATE_EMAIL))?
    insertData("update clients set lostpassword_hash=:lostpassword_hash where email=:usernameOrEmail", $params) :
    insertData("update clients set lostpassword_hash=:lostpassword_hash where username=:usernameOrEmail", $params);
sendMessage($user['email'],"Органайзер: Восстановление пароля!", "<h2>Восстановление пароля!</h2><p>Вы активировали функцию восстановление пароля для входа в личный кабинет.</p><p>Нажмите <a href='https://starichky.myarena.site/kp/app/actions/confirmForReset.php?token={$params['lostpassword_hash']}'>здесь</a> для того, чтобы установить новый пароль.</p><p>Если Вы считаете, что данное сообщение отправлено Вам ошибочно, просто проигнорируйте его.</p><p>Благодарим за использование Органайзера!</p><p>С уважением, </p><p>Коллектив Органазера</p><p>Важно: это сообщение было отправлено с электронного адреса, предназначенного только для рассылки исходящих сообщений. Пожалуйста не отвечайте на это сообщение.</p>");
setNotification('notification-usually', "Письмо с дальнейшими инструкциями отправлено на email!");
redirect("../../index.php");