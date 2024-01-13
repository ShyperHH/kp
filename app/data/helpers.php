<?php

use JetBrains\PhpStorm\NoReturn;

session_start();
require_once "config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
#[NoReturn] function redirect(string $path): void
{
    header("Location: $path");
    die();
}

function validationErrorAttr(string $firstKey, string $secondKey, ): void
{
    echo isset($_SESSION[$firstKey][$secondKey])?'aria-invalid="true"':'';
}

function setValidationError(string $firstKey, string $secondKey, string $message): void
{
    $_SESSION[$firstKey][$secondKey]=$message;
}

function hasValidationError(string $firstKey, string $secondKey, ): bool
{
    return isset($_SESSION[$firstKey][$secondKey]);
}
function validationErrorMessage(string $firstKey, string $secondKey, ): void
{
    echo $_SESSION[$firstKey][$secondKey] ?? '';
    unset($_SESSION[$firstKey][$secondKey]);
}

function setOldValue(string $key, mixed $value):void
{
    $_SESSION['old'][$key]=$value;
}

function old(string $key): void
{
    echo $_SESSION['old'][$key] ?? '';
    unset($_SESSION['old'][$key]);
}
function uploadFile(array $files, string $folder="", string $prefix=""): string
{
    if($files["size"][0]=="0" && $folder=="documents"){
        return "";
    }
    $uploadPath = "../uploads";
    $names="";
    if(!is_dir($uploadPath)){
        mkdir($uploadPath, 0777, true);
    }
    if($folder=="avatars"){
        $ext = pathinfo($files['name'], PATHINFO_EXTENSION);
        $filename= $prefix."_".time().".$ext";
        $names.=$filename;
        move_uploaded_file($files['tmp_name'], "$uploadPath/$folder/$filename");
    }
    else{
        for( $i=0 ; $i < count($files['name']) ; $i++ ) {
            $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $filename= $prefix."_".time()+$i.".$ext";
            $names.=$filename;
            move_uploaded_file($files['tmp_name'][$i], "$uploadPath/$folder/$filename");
        }
    }
    return $names ?? "";
}

function getPDO(): PDO
{
    try {
        return new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER,  DB_PASS);
    }catch (PDOException $e){
        die("Connection error: {$e->getMessage()}");
    }
}
function insertData($sql, $params): void
{
    $pdo = getPDO();
    $pdo->prepare($sql)->execute($params);
}
function findQuery(string $sql, $params=[]){
    $pdo = getPDO();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findQueryAll(string $sql, $params=[]): false|array
{
    $pdo = getPDO();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

#[NoReturn] function logout(): void
{
    unset($_SESSION['user']['id']);
    redirect("../../index.php");
}

function currentUser(): array|false{
    $pdo = getPDO();
    if(!isset($_SESSION['user'])){
        return false;
    }
    $userId = $_SESSION['user']['id'] ?? null;

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE `id`=:id");
    $stmt->execute(['id'=>$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function checkSession(): bool
{
    return isset($_SESSION['user']['id']);
}

function checkRequest(): void{
    if($_SERVER['REQUEST_METHOD']!="POST"){
        redirect("../pages/home.php");
    }
}

function sendMessage($to, $header, $message): void
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'danaherr666@gmail.com';
        $mail->Password = "phhp qcoa etdq rghi";
        $mail->Port = 587;
        $mail->setFrom("danaherr666@gmail.com", "Органайзер");
        $mail->addAddress("$to");//Кому отправляем
        $mail->SMTPSecure = 'tls';
        $mail->isHTML();//HTML формат
        $mail->Subject = $header;
        $mail->Body = $message;
        $mail->AltBody = "Альтернативное содержание сообщения";

        $mail->send();
        echo "Сообщение отправлено";
    } catch (Exception $e) {
        echo "Ошибка отправки: $mail->ErrorInfo --- $e";
    }
}

function setNotification(string $key, string $value): void{
    $_SESSION['notification']=[$key=>$value];
}