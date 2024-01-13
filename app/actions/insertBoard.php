<?php

require_once "../data/helpers.php";
checkRequest();
if(!$user = currentUser()){
    redirect('../../index.php');
}
$nameBoard = trim(urldecode(html_entity_decode(strip_tags($_POST['nameBoardForCreate'])))) ?? null;
if($nameBoard==""){
    setValidationError("validationInsertBoard", "createBoardError", "Название доски не может быть пустой!");
}
else if($board=findQuery("select boards.id from boards, clients where boards.client_id=clients.id && boards.name=:nameboard && clients.username=:user", ['nameboard'=>$nameBoard,'user'=>$user['username']??null])) {
    setValidationError("validationInsertBoard", "createBoardError", "У вас уже есть доска с названием $nameBoard");
}
else{
    $params=['name'=>$nameBoard,'created'=>time(),'status'=>0, 'clientId'=>$user['id']];
    insertData("insert into boards (`name`, `created`, `status`, `client_id`) values (:name, :created, :status, :clientId)", $params);
    $idBoard=findQuery("select id from boards where boards.client_id=:clientId and boards.name=:boardName", ['clientId'=>$user['id'],'boardName'=>$nameBoard]);
    insertData("insert into clients_boards(`client_id`, `board_id`, `nameWithWhomShared`) values (:idWithWhomShared, :idBoard, :nameWithWhomShared)", ['idWithWhomShared'=>$user['id'],'idBoard'=>$idBoard['id'], 'nameWithWhomShared'=>$user['username']]);
    setNotification('notification-success', "Доска добалена!");
}
if(count($_SESSION['validationInsertBoard'])!=0){
    setNotification('notification-error', "Исправьте ошибки в вводимых данных, при добавлении доски!");
}
redirect("../pages/home.php");