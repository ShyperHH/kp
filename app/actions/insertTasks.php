<?php
require_once "../data/helpers.php";
require_once "../data/config.php";
checkRequest();
if(!$user = currentUser()) {
    redirect('../../index.php');
}
$nameBoard = trim(urldecode(html_entity_decode(strip_tags($_POST['BoardForInsertTask'])))) ?? null;
$descriptionTask = $_POST['descriptionTask'];
$deadline= strtotime($_POST['deadline']);
$docs=$_FILES['doc'];
$creatorBoard=$_POST['creatorBoard'];
if($nameBoard==""){
    setValidationError("validationInsertTask","boardError", "Название доски не может быть пустой!!");
}
else if($creatorBoard==""){
    setValidationError("validationInsertTask","creatorBoard", "Создатель доски не может быть пуст!");
}
else if(!$board=findQuery("select boards.id from boards, clients where boards.client_id=clients.id && boards.name=:nameboard && clients.username=:user", ['nameboard'=>$nameBoard,'user'=>$creatorBoard])) {
    setValidationError("validationInsertTask","boardError", "У $creatorBoard нет $nameBoard");
}
else if(!findQuery("select clients_boards.id from clients_boards, clients where clients_boards.client_id=clients.id && clients_boards.board_id=:boardId && clients.username=:user", ['boardId'=>$board['id'],'user'=>$user['username']??null])) {
    setValidationError("validationInsertTask","boardError", "У вас нет доступа к доске $nameBoard от $creatorBoard");
}
else if($descriptionTask==""){
    setValidationError("validationInsertTask","descriptionTaskError", "Задача не может быть пустой!");
}
else{
    if(!empty($docs) && $docs['type'][0]!=""){
        for( $i=0 ; $i < count($docs['name']) ; $i++ ){
            if(!in_array($docs['type'][$i], accessTypesDoc)){
                setValidationError("validationInsertTask","doc", "Файл имеет неверный тип");
            }
            if($docs['size'][$i]/1000000>=sizeDoc){
                setValidationError("validationInsertTask","doc", "Файл должно быть меньше 10 мб");
            }
        }
    }
    if(count($_SESSION['validationInsertTask'])!=0){
        setNotification('notification-error', "Исправьте ошибки в вводимых данных, при внесении задачи!");
    }
    else{
        $documentPath = uploadFile($docs, "documents", "document");
        $params=['name'=>$descriptionTask, 'boardId'=>$board['id'],'created'=>time(), 'deadline'=>$deadline,'status'=>"В разработке", 'docs'=>$documentPath];
        insertData("insert into tasks (`name`, `board_id`, `created`, `deadline`, `status`, `docs`) values (:name, :boardId, :created, :deadline, :status, :docs)", $params);
        setNotification('notification-success', "Задача добалена!");
    }
}
redirect("../pages/home.php");