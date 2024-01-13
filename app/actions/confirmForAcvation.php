<?php
require_once "../data/helpers.php";
if(checkSession()){
    redirect("../pages/home.php");
}
$token = $_GET["token"];
if(gettype(findQuery("select * from clients where `activation_hash`=:activation_hash",['activation_hash'=>$token]))=="array"){
    setNotification('notification-success', "Ваш аккаунт подтвержден!");
    insertData("update clients set activation_hash=:activation_hash where activation_hash=:token", ['activation_hash'=>"", 'token'=>$token]);
}else{
    setNotification('notification-error', "Ваш токен устарел!");
}
redirect("../../index.php");