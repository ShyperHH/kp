<?php
require_once "../data/helpers.php";
checkRequest();
setNotification('notification-success', "Успешный выход!");
setcookie("username", "", time() - 60 * 60 * 24 * 7, '/');
setcookie("password", "", time() - 60 * 60 * 24 * 7, '/');
logout();