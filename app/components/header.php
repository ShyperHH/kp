<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?= $title ?? null; ?></title>
    <meta content="text/css" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ежедневник там да">
    <meta name="author" content="Den4ik">
    <meta name="keywords" content="ежедневник, хз, да">
    <meta name="robots" content="index, follow">
    <link rel="shortcut icon" href="/kp/app/pub/ico/organizer.ico">
    <link href="/kp/app/pub/css/bootstrap.css" rel="stylesheet">
    <link href="/kp/app/pub/css/main.css" rel="stylesheet">
</head>
<body>



<?php if(isset($_SESSION['notification'])):?>
    <div class="toast align-items-center <?= array_key_first($_SESSION['notification']) ?>" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
                </svg>
                <?= $_SESSION['notification'][array_key_first($_SESSION['notification'])]  ?>
            </div>
            <button type="button" onclick="closeNotification(<?= "'".array_key_first($_SESSION['notification'])."'"; ?>)" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Закрыть"></button>
        </div>
    </div>
<?php endif; $_SESSION['notification']=[]; ?>