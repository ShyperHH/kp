<?php
$title="Главная";
require  "../data/config.php";
require "../data/helpers.php";
require '../components/header.php';
if(!$user = currentUser()) {
    redirect('../../index.php');
}
$boards = findQueryAll("select boards.name from clients_boards, boards where boards.id=clients_boards.board_id and clients_boards.client_id=:userId", ['userId' => $user['id']]);
?>

<nav class="card home container block-style block_navigation">
    <ul>
        <li>ОРГАНАЙЗЕР</li>
        <li>
            <form class="mx-auto" action="../actions/logout.php" method="post">
                <button class="button_logout">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.32 2h.93a.75.75 0 1 1 0 1.5h-.9c-1 0-1.7 0-2.24.04a2.9 2.9 0 0 0-1.1.26A2.75 2.75 0 0 0 3.8 5c-.13.25-.21.57-.26 1.11-.04.55-.04 1.25-.04 2.24v3.3c0 1 0 1.7.04 2.24.05.53.13.86.26 1.1A2.75 2.75 0 0 0 5 16.2c.25.13.57.21 1.11.26.55.04 1.25.04 2.24.04h.9a.75.75 0 0 1 0 1.5h-.93c-.96 0-1.72 0-2.33-.05a4.39 4.39 0 0 1-1.67-.41 4.25 4.25 0 0 1-1.86-1.86A4.38 4.38 0 0 1 2.05 14C2 13.4 2 12.64 2 11.68V8.32c0-.96 0-1.72.05-2.33.05-.63.16-1.17.41-1.67a4.25 4.25 0 0 1 1.86-1.86c.5-.25 1.04-.36 1.67-.41C6.6 2 7.36 2 8.32 2Zm5.9 4.97a.75.75 0 0 1 1.06 0l2.5 2.5a.75.75 0 0 1 0 1.06l-2.5 2.5a.75.75 0 1 1-1.06-1.06l1.22-1.22H8.75a.75.75 0 0 1 0-1.5h6.69l-1.22-1.22a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path></svg>
                    Выйти
                </button>
            </form>
        </li>
    </ul>
</nav>

<!--Взаимодействие с досками и задачами-->
<div class="modal colors_blocks <?php if( isset($_SESSION['validationInsertTask']) && !empty($_SESSION['validationInsertTask']) || isset($_SESSION['validationShareClient']) && !empty($_SESSION['validationShareClient']) || isset($_SESSION['validationInsertBoard']) && !empty($_SESSION['validationInsertBoard']) ) echo 'hrefOnTasks';  ?>"  tabindex="1">
    <div class="modal-content colors_blocks">
            <div class="modal-header colors_blocks">
                <h5 class="modal-title">Взаимодействие с досками и задачами</h5>
                <button type="button" class="btn-close btn-close-white" onclick="hideWindow('modal')" data-bs-dismiss="modal" aria-label="Close"></button>
                <hr>
            </div>
            <div class="modal-body colors_blocks">
                <h5>Создать доску</h5>
                <hr>
                <form method="post" action="../actions/insertBoard.php">
                    <label for="nameBoardForCreate"> Название доски?
                        <input type="text"   name="nameBoardForCreate" id="nameBoardForCreate" placeholder="Название доски">
                    </label>
                    <?php if(hasValidationError("validationInsertBoard", "createBoardError")){ ?>
                        <small><?php validationErrorMessage("validationInsertBoard", "createBoardError"); ?></small>
                    <?php } ?>
                    <button class="colors_blocks">
                    Создать доску
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 64 64"
                         style="fill:#FFFFFF;">
                        <path d="M 30 4 C 14.561 4 2 16.561 2 32 C 2 47.439 14.561 60 30 60 L 33 60 C 48.439 60 61 47.439 61 32 C 61 26.600951 59.460842 21.556431 56.802734 17.275391 L 62.039062 12.039062 L 59.210938 9.2109375 L 54.423828 13.998047 C 49.283425 7.890176 41.589024 4 33 4 L 30 4 z M 33 8 C 40.486401 8 47.18112 11.446946 51.585938 16.835938 L 33.375 35.046875 L 21.664062 23.335938 L 18.835938 26.164062 L 33.375 40.703125 L 53.882812 20.195312 C 55.862819 23.683904 57 27.710097 57 32 C 57 45.234 46.233 56 33 56 C 19.767 56 9 45.234 9 32 C 9 18.766 19.767 8 33 8 z"></path>
                    </svg>
                    </button>
                </form>
                <hr>
                <h5>Добавить задачу</h5>
                <hr>
                <form  enctype="multipart/form-data" method="post" action="../actions/insertTasks.php">
                    <label for="creatorBoard">Кто создатель доски?
                        <input value="<?= $user['username']?>" type="text" id="creatorBoard" name="creatorBoard" >
                    </label>
                    <?php if(hasValidationError("validationInsertTask","creatorBoard")){ ?>
                        <small><?php validationErrorMessage("validationInsertTask","creatorBoard"); ?></small>
                    <?php } ?>
                    <label for="BoardForInsertTask">Выберите доску для задачи:
                        <input list="listBoardForInsertTask" name="BoardForInsertTask" id="BoardForInsertTask">
                    </label>
                    <datalist id="listBoardForInsertTask">
                        <?php foreach (array_unique($boards, SORT_REGULAR) as $board){ ?>
                        <option name="nameBoard" id="nameBoard" placeholder="Название доски" value="<?= $board['name']; ?>">
                            <?php  } ?>
                    </datalist>
                    <?php
                    if(hasValidationError("validationInsertTask","boardError")){ ?>
                        <small><?php validationErrorMessage("validationInsertTask","boardError"); ?></small>
                    <?php } ?>
                    <label for="descriptionTask">Опишите задачу
                        <textarea id="descriptionTask" name="descriptionTask"></textarea>
                    </label>
                    <?php if(hasValidationError("validationInsertTask","descriptionTaskError")){ ?>
                        <small><?php validationErrorMessage("validationInsertTask","descriptionTaskError"); ?></small>
                    <?php } ?>

                    <label for="deadline">Дата и время:
                        <input type="datetime-local" value="<?php echo date('Y-m-d\TH:i'); ?>" id="deadline" name="deadline"/>
                    </label>
                    <label for="doc">Загрузка документа:
                        <input type="file" name="doc[]" id="doc[]" multiple >
                    </label>
                    <?php if(hasValidationError("validationInsertTask","doc")){ ?>
                        <small><?php validationErrorMessage("validationInsertTask","doc"); ?></small>
                    <?php } ?>
                    <button class="colors_blocks" type="submit">
                        Добавить задачу
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 64 64"
                             style="fill:#FFFFFF;">
                            <path d="M 30 4 C 14.561 4 2 16.561 2 32 C 2 47.439 14.561 60 30 60 L 33 60 C 48.439 60 61 47.439 61 32 C 61 26.600951 59.460842 21.556431 56.802734 17.275391 L 62.039062 12.039062 L 59.210938 9.2109375 L 54.423828 13.998047 C 49.283425 7.890176 41.589024 4 33 4 L 30 4 z M 33 8 C 40.486401 8 47.18112 11.446946 51.585938 16.835938 L 33.375 35.046875 L 21.664062 23.335938 L 18.835938 26.164062 L 33.375 40.703125 L 53.882812 20.195312 C 55.862819 23.683904 57 27.710097 57 32 C 57 45.234 46.233 56 33 56 C 19.767 56 9 45.234 9 32 C 9 18.766 19.767 8 33 8 z"></path>
                        </svg>
                    </button>

                </form>
                <hr>
                <h5>Поделиться доской</h5>
                <hr>
                <form action="../actions/toShareClient.php" method="post">
                    <?php $clients = findQueryAll('select * from clients');  ?>
                    <label for="nameWithWhomShared"> С кем?
                        <input list="listNameWithWhomShared" onclick="changeBackground()" name="nameWithWhomShared" id="nameWithWhomShared">
                        <datalist id="listNameWithWhomShared">
                            <?php foreach ($clients as $client){ if($client['username']==$user['username']) {continue;}?>
                            <option value="<?= $client['username']; ?>">
                                <?php } ?>
                        </datalist>
                    </label>
                    <?php if(hasValidationError("validationShareClient","userNotFounded")){ ?>
                        <small><?php validationErrorMessage("validationShareClient","userNotFounded"); ?></small>
                    <?php } ?>

                    <label for="creatorBoard">Создатель доски?
                        <input list="listCreatorBoard" value="<?= $user['username']?>" name="creatorBoard" id="creatorBoard">
                        <datalist id="listCreatorBoard">
                            <?php foreach ($clients as $client){ if($client['username']==$user['username']) {continue;}?>
                            <option value="<?= $client['username']; ?>">
                                <?php } ?>
                        </datalist>
                    </label>
                    <?php if(hasValidationError("validationShareClient","creatorNotFounded")){ ?>
                        <small><?php validationErrorMessage("validationShareClient","creatorNotFounded"); ?></small>
                    <?php } ?>

                    <label for="nameBoardForShare">Название доски?
                        <input list="listBoardForShare" name="nameBoardForShare" id="nameBoardForShare">
                        <datalist id="listBoardForShare">
                            <?php foreach (array_unique($boards, SORT_REGULAR) as $board){ ?>
                            <option value="<?php echo $board['name']; ?>">
                                <?php  } ?>
                        </datalist>
                    </label>
                    <?php if(hasValidationError("validationShareClient","boardError")){ ?>
                        <small><?php validationErrorMessage("validationShareClient","boardError"); ?></small>
                    <?php } ?>
                    <button class="colors_blocks">Поделиться доской
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 64 64"
                         style="fill:#FFFFFF;">
                        <path d="M 30 4 C 14.561 4 2 16.561 2 32 C 2 47.439 14.561 60 30 60 L 33 60 C 48.439 60 61 47.439 61 32 C 61 26.600951 59.460842 21.556431 56.802734 17.275391 L 62.039062 12.039062 L 59.210938 9.2109375 L 54.423828 13.998047 C 49.283425 7.890176 41.589024 4 33 4 L 30 4 z M 33 8 C 40.486401 8 47.18112 11.446946 51.585938 16.835938 L 33.375 35.046875 L 21.664062 23.335938 L 18.835938 26.164062 L 33.375 40.703125 L 53.882812 20.195312 C 55.862819 23.683904 57 27.710097 57 32 C 57 45.234 46.233 56 33 56 C 19.767 56 9 45.234 9 32 C 9 18.766 19.767 8 33 8 z"></path>
                    </svg>
                    </button>
                </form>
            </div>
    </div>
    <?php $_SESSION['validationInsertBoard']=[];$_SESSION['validationInsertTask']=[];$_SESSION['validationShareClient']=[]; ?>
</div>

<!--Описание задачи-->
<div class="modal1 modal" tabindex="-1">
    <div class="modal-dialog colors_blocks">
        <div class="modal-content colors_blocks">
            <div class="modal-header colors_blocks">
                <h5 class="modal-title">Описание задачи</h5>
                <button type="button" class="btn-close btn-close-white" onclick="hideWindow('modal1')" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body colors_blocks">
                <hr>
                <label for="status">Статус:
                    <select name="status" id="status">
                        <option value="В разработке">В разработке</option>
                        <option value="На проверке">На проверке</option>
                        <option value="Переработать">Переработать</option>
                        <option value="Закрыт">Закрыт</option>
                    </select>
                </label>
                <p>Здесь основной текст модального окна</p>
                <hr>
                <span></span>
            </div>

        </div>
    </div>
</div>

<!--Карточка с профилем-->
<div class="card home container block-style">
    <div class="container profile_container">
        <img width="150" height="150" alt="avatar" src="../uploads/avatars/<?= $user['avatar']??null; ?>">
        <div class="profile_info">
            <p>Имя: <?= $user['username']; ?></p>
            <p><?php echo "Вы с нами с: ". (date('d.m.Y H:i:s', $user['created']));?></p>
            <button class="colors_blocks" onclick="ShowWindWithAddTask()">
                Взаимодействие с досками
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 64 64"
                     style="fill:#FFFFFF;">
                    <path d="M 30 4 C 14.561 4 2 16.561 2 32 C 2 47.439 14.561 60 30 60 L 33 60 C 48.439 60 61 47.439 61 32 C 61 26.600951 59.460842 21.556431 56.802734 17.275391 L 62.039062 12.039062 L 59.210938 9.2109375 L 54.423828 13.998047 C 49.283425 7.890176 41.589024 4 33 4 L 30 4 z M 33 8 C 40.486401 8 47.18112 11.446946 51.585938 16.835938 L 33.375 35.046875 L 21.664062 23.335938 L 18.835938 26.164062 L 33.375 40.703125 L 53.882812 20.195312 C 55.862819 23.683904 57 27.710097 57 32 C 57 45.234 46.233 56 33 56 C 19.767 56 9 45.234 9 32 C 9 18.766 19.767 8 33 8 z"></path>
                </svg>
            </button>
        </div>
    </div>

    <div class="container profile_container">
        <?php
            $boardsOpen = findQueryAll("select count(boards.name) from clients_boards, boards where boards.id=clients_boards.board_id and clients_boards.client_id=:userId and status=0", ['userId' => $user['id']]);
            $boardsClose = findQueryAll("select count(boards.name) from clients_boards, boards where boards.id=clients_boards.board_id and clients_boards.client_id=:userId and status=1", ['userId' => $user['id']]);
        ?>
        <div class="profile_block"><?= $boardsOpen[0]['count(boards.name)'] ?><br>открытые доски</div>
        <div class="profile_block"><?=$boardsClose[0]['count(boards.name)'] ?><br>закрытые закрытые</div>
        <div class="profile_block"><?= 10 ?><br>срочных досок</div>
    </div>

</div>

<!--Вывод досок-->
<div class="container-board">
    <?php
    //select boards.name, tasks.name as taskName, clients_boards.nameWithWhomShared from tasks, clients, boards, clients_boards where clients_boards.board_id=boards.id and tasks.board_id=boards.id and tasks.board_id=boards.id and clients.id=boards.client_id and clients.id= :userId", ['userId'=>$user['id']]);
    //select boards.name, tasks.name as taskName, clients_boards.nameWithWhomShared from tasks, boards, clients_boards where clients_boards.board_id=boards.id and tasks.board_id=boards.id and clients_boards.client_id= :userId", ['userId'=>$user['id']]);
    $boards = findQueryAll("select clients.username, tasks.docs, tasks.id, tasks.status, tasks.created, tasks.deadline, boards.name, tasks.name as taskName, clients_boards.nameWithWhomShared from clients, tasks, boards, clients_boards where clients.id=boards.client_id && clients_boards.board_id=boards.id and tasks.board_id=boards.id and clients_boards.client_id= :userId order by boards.name, clients.username", ['userId'=>$user['id'],'userId'=>$user['id']]);
    $nameBoard="";
    $boardsLastDays=[[]];
    $creatorBoard="";
    $j=0;
    for ($i=0;isset($boards[$i]);){
        $creatorBoard=$boards[$i]['username'];
        $nameBoard=$boards[$i]['name']; ?>
        <div class="block-board  block-style scrollbar" id="style-1">
            <span><?= "Доска: ".$boards[$i]["name"]; ?></span>
            <hr>
            <div><?php while(isset($boards[$i]) && $boards[$i]['name']==$nameBoard && $boards[$i]['username']==$creatorBoard){?>
                <a href="#" class="hrefOnTasks" onclick='ShowDescTask(<?php echo '[`'.$boards[$i]['taskName'].'`,'.'`.'.$boards[$i]['name'].'`,'.'`'.$boards[$i]['status'].'`,'.'`'.$boards[$i]['docs'].'`]';?>)' ><?php echo substr($boards[$i]['taskName'], 0, 32)."...";?></a>
                <?php if($boards[$i]['deadline']!=0){
                    $current_time_date = date_create(date('Y-m-d H:i:s', time()));
                    $timestamp_date = date_create(date('Y-m-d H:i:s', $boards[$i]['deadline']));
                    $diff = date_diff($timestamp_date, $current_time_date);  ?>
                    <p <?php if($diff->format('%a')<limitDays){ echo "class='redP'";
                        $boardsLastDays[$j]['taskName']=$boards[$i]['taskName'];
                        $boardsLastDays[$j]['name']=$boards[$i]['name'];
                        $boardsLastDays[$j]['days']=$diff->format('%a') ." ".  $diff->format('%h') . ":" . $diff->format('%i') .":" . $diff->format('%s');
                        $j++;
                    } ?>>
                    <?php


                    if($timestamp_date>$current_time_date)
                    {echo "Осталось: " . $diff->format('%a') ." ".  $diff->format('%h') . ":" . $diff->format('%i') .":" . $diff->format('%s');}else{echo "Срок вышел";} ?></p>
                <?php }$i++;}?>
            </div>
            <hr>
            <?php if($boards[$i-1]['nameWithWhomShared']!=$user['username']){ ?><small><?php echo "С вами поделился: ".$boards[$i-1]['nameWithWhomShared'];}else{echo "Ваша доска";} ?></small><br>

            <?php if($boards[$i-1]['username']!=$user['username']){ ?><small><?php echo "Доска создана: ".$boards[$i-1]['username'];}else{echo "Ваша доска";} ?></small>

        </div>
    <?php } ?>
</div>

<!--
<div class="containerForImportantBoards">
    <div><?php $nameBoard="";
for ($i=0;count($boardsLastDays)>$i && isset($boardsLastDays[0]['name']) && $boardsLastDays[$i]['name']!=$nameBoard;){
    $nameBoard=$boardsLastDays[$i]['name']; ?>
            <h5><?= "Доска: ".$boardsLastDays[$i]["name"]; ?></h5>
            <?php while(isset($boardsLastDays[$i]) && $boardsLastDays[$i]['name']==$nameBoard){ ?>
                <a href="#" class="hrefOnTasks" onclick='ShowDescTask(<?php echo '[`'.$boardsLastDays[$i]['taskName'].'`,'.'`.'.$boardsLastDays[$i]['name'].'`]';?>)' ><?php echo substr($boardsLastDays[$i]['taskName'], 0, 32)."...";?></a>
                <?php $i++;
    }
}?>
    </div>
</div>

-->
<!--Вывод досок, у которых срок на подходе-->



<?php require '../components/footer.php';
/*
create table post(id int primary key AUTO_INCREMENT, name varchar(255));
create table boards(id int primary key AUTO_INCREMENT, name varchar(255), created int, status bool);
create table tasks(id int PRIMARY key AUTO_INCREMENT, name varchar(255), board_id int, created int, status bool, FOREIGN KEY (board_id) REFERENCES boards(id));
create table clients(id int primary key AUTO_INCREMENT, username varchar(255) UNIQUE, email varchar(255) UNIQUE, avatar varchar(255), password varchar(255), post_id int, created int, activation_hash varchar(255), lostpassword_hash varchar(255), FOREIGN KEY (post_id) REFERENCES post(id));
create table clients_boards(id int primary key AUTO_INCREMENT, client_id unique int, board_id int, nameWithWhomShared varchar(255), foreign key (board_id) REFERENCES boards(id), foreign key (client_id) REFERENCES clients(id));
*/
?>




