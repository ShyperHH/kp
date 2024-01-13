<?php
require_once "../data/translations/ru/liteVersion.php";
require_once "../data/helpers.php";
checkRequest();
if(!$user = currentUser()){
    redirect('../../index.php');
}
// имя с кем поделились, название доски
// nameWithWhomShared nameBoard

$creatorBoard=$_POST['creatorBoard'];
$withWhomShared=$_POST['nameWithWhomShared'];
$boardForShare=$_POST['nameBoardForShare'];
if($withWhomShared==""){
    setValidationError("validationShareClient", "userNotFounded", "Пользователь, с которым вы хотите поделиться не может быть пуст.");
}
else if(!$idWithWhomShared=findQuery("select id from clients where username=:username",['username'=>$withWhomShared])){
    setValidationError("validationShareClient", "userNotFounded", "Пользователь {$withWhomShared} не существует.");
}
else if($creatorBoard==""){
    setValidationError("validationShareClient", "creatorNotFounded", "Создатель доски не может быть пуст.");
}
else if(!findQuery("select id from clients where username=:username",['username'=>$creatorBoard])){
    setValidationError("validationShareClient", "creatorNotFounded", "Пользователь {$creatorBoard} не существует.");
}
else if(!$board=findQuery("select boards.id from clients, boards where boards.client_id=clients.id && clients.username=:user && boards.name=:boardForShare", ['user'=>$creatorBoard,"boardForShare"=>$boardForShare])){
    setValidationError("validationShareClient","boardError", "Доска {$boardForShare} не существует.");
}
else if(!findQuery("select clients_boards.id from clients_boards, clients where clients.id=clients_boards.client_id && clients.username=:username && clients_boards.board_id=:idBoard", ["username"=>$user['username'],"idBoard"=>$board['id']])){
    setValidationError("validationShareClient","boardError", "У вас нет доступа к доске {$boardForShare} от {$creatorBoard}.");
}
else if(findQuery("select clients_boards.id from clients_boards, clients where clients.id=clients_boards.client_id && clients.username=:username && clients_boards.board_id=:idBoard", ["username"=>$withWhomShared,"idBoard"=>$board['id']])){
    setValidationError("validationShareClient","boardError", "{$withWhomShared} уже имеет доступ к доске {$boardForShare}.");
}
else{
    insertData("insert into clients_boards(`client_id`, `board_id`, `nameWithWhomShared`) values (:idWithWhomShared, :idBoard, :nameWithWhomShared)", ['idWithWhomShared'=>$idWithWhomShared['id'],'idBoard'=>$board['id'], 'nameWithWhomShared'=>$user['username']]);
    setNotification('notification-success', "Вы поделились {$boardForShare} с {$withWhomShared}!");
}

if(count($_SESSION['validationShareClient'])!=0){
    setNotification('notification-error', "Исправьте ошибки в вводимых данных, что поделиться доской с клиентом!");
}
redirect("../pages/home.php");