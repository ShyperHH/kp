<?php
require_once "../data/config.php";
require_once  '../data/helpers.php';
require_once "../../lib/PHPMailer/src/Exception.php";
require_once "../../lib/PHPMailer/src/SMTP.php";
require_once '../../lib/PHPMailer/src/PHPMailer.php';
checkRequest();

$username = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($_POST['username'])))))) ?? null;
$email=$_POST['email'];
$avatar=$_FILES['avatar'];
$password=$_POST['password'];
$passwordConfirmation=$_POST['passwordConfirmation'];
$uploadPath="../uploads";

if(empty($username)){
    setValidationError("validationRegister",'username', 'Неверное имя');
}
if(!filter_var($email, filter:FILTER_VALIDATE_EMAIL)){
    setValidationError("validationRegister",'email', 'Указана неправильная почта');
}
if(empty($password)){
    setValidationError("validationRegister",'password', 'Пароль пустой');
}
if(empty($password===$passwordConfirmation)){
    setValidationError("validationRegister",'password', 'Пароли не совпадают');
}
if(!empty($_SESSION['validation'])){
    setOldValue('username', $username);
    setOldValue('email', $email);
    setNotification('notification-error', "Исправьте ошибки в вводимых данных!");
    redirect("../pages/register.php");
}

if(!empty($avatar) && $avatar['type'][0]!=""){
    if(!in_array($avatar['type'], accessTypesAvatar)){
        setValidationError("validationRegister","avatar", "Изображение имеет неверный тип");
    }
    if($avatar['size']/1000000>=sizeAvatar){
        setValidationError("validationRegister","avatar", "Изображение должно быть меньше 1 мб");
    }
}
$params=['username'=>$username];
if(gettype(findQuery("SELECT * FROM clients WHERE `username`=:username",$params))=="array"){
    setValidationError("validationRegister","username", "Такой логин уже зарегистрирован");
}
$params=['email'=>$email];
if(gettype(findQuery("SELECT * FROM clients WHERE `email`=:email",$params))=="array"){
    setValidationError("validationRegister","email", "Такой email уже зарегистрирован");
}
if(filter_var($username, filter:FILTER_VALIDATE_EMAIL)){
    setValidationError("validationRegister","username", "email нельзя писать в логин");
}

if(!empty($_SESSION['validation'])){
    setOldValue('username', $username);
    setOldValue('email', $email);
    setNotification('notification-error', "Исправьте ошибки в вводимых данных!");
    redirect("../pages/register.php");
}

if(!empty($avatar) && $avatar['type'][0]!=""){
    $avatarPath = uploadFile($avatar, "avatars", "avatar");
}
else{
    $avatarPath="default.jpg";
}

$params=[
    'username'=>$username,
    'email'=>$email,
    'avatar'=>$avatarPath ?? null,
    'password'=>password_hash($password, PASSWORD_DEFAULT),
    'created'=>time(),
    'activation_hash'=>password_hash(rand(100000,999999), PASSWORD_DEFAULT)
];

insertData("INSERT INTO clients (username, email, avatar, password, post_id, created, activation_hash) VALUES (:username, :email, :avatar, :password, 1, :created, :activation_hash)", $params);
setNotification('notification-usually', "На ваш Email отправлено сообщение о регистрации!");
sendMessage($email,"Органайзер: Подтверждение Email!", "<h2>Подтверждение Email!</h2><p>Ваш логин: $username</p><p>Ваш пароль: $password</p><p>Нажмите <a href='https://starichky.myarena.site/kp/app/actions/confirmForAcvation.php?token={$params['activation_hash']}'>здесь</a> для активации вашего аккаунта.</p><p>Благодарим за использование Органайзера!</p><p>С уважением, </p><p>Коллектив Органазера</p><p>Важно: это сообщение было отправлено с электронного адреса, предназначенного только для рассылки исходящих сообщений. Пожалуйста не отвечайте на это сообщение.</p>");
redirect("../../index.php");