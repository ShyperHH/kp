<?php
$title="Восстановление пароля";
require_once "../data/helpers.php";
require_once "../components/header.php";
if(checkSession()){
    redirect("../pages/home.php");
}
?>
    <div class="container">

        <form class="card mx-auto colors_blocks" action="../actions/lostPassword.php" method="post">
            <h2>Восстановление пароля</h2>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="usernameOrEmail" id="usernameOrEmail" <?php validationErrorAttr("validationLostPass", 'usernameOrEmail');?> placeholder="name@example.com" required>
                <label for="usernameOrEmail">Введите: Логин/Email</label>
            </div>
            <?php if(hasValidationError("validationLostPass", 'usernameOrEmail')):?>
                <small><?php validationErrorMessage("validationLostPass", 'usernameOrEmail');?></small>
            <?php endif;?>

            <button type="submit" class="btn colors_blocks" id="submit" >Восстановить пароль
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 64 64"
                     style="fill:#FFFFFF;">
                    <path d="M 30 4 C 14.561 4 2 16.561 2 32 C 2 47.439 14.561 60 30 60 L 33 60 C 48.439 60 61 47.439 61 32 C 61 26.600951 59.460842 21.556431 56.802734 17.275391 L 62.039062 12.039062 L 59.210938 9.2109375 L 54.423828 13.998047 C 49.283425 7.890176 41.589024 4 33 4 L 30 4 z M 33 8 C 40.486401 8 47.18112 11.446946 51.585938 16.835938 L 33.375 35.046875 L 21.664062 23.335938 L 18.835938 26.164062 L 33.375 40.703125 L 53.882812 20.195312 C 55.862819 23.683904 57 27.710097 57 32 C 57 45.234 46.233 56 33 56 C 19.767 56 9 45.234 9 32 C 9 18.766 19.767 8 33 8 z"></path>
                </svg>
            </button>

            <p>Необходим аккаунт? <a class="colors-Text" href="../pages/register.php">Зарегистрируйтесь
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 64 64"
                         style="fill:#FFFFFF;">
                        <path d="M 30 4 C 14.561 4 2 16.561 2 32 C 2 47.439 14.561 60 30 60 L 33 60 C 48.439 60 61 47.439 61 32 C 61 26.600951 59.460842 21.556431 56.802734 17.275391 L 62.039062 12.039062 L 59.210938 9.2109375 L 54.423828 13.998047 C 49.283425 7.890176 41.589024 4 33 4 L 30 4 z M 33 8 C 40.486401 8 47.18112 11.446946 51.585938 16.835938 L 33.375 35.046875 L 21.664062 23.335938 L 18.835938 26.164062 L 33.375 40.703125 L 53.882812 20.195312 C 55.862819 23.683904 57 27.710097 57 32 C 57 45.234 46.233 56 33 56 C 19.767 56 9 45.234 9 32 C 9 18.766 19.767 8 33 8 z"></path>
                    </svg>
                </a></p>
            <p>Зарегистрированы? <a class="colors-Text" href="../../index.php">Авторезируйтесь
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 64 64"
                         style="fill:#FFFFFF;">
                        <path d="M 30 4 C 14.561 4 2 16.561 2 32 C 2 47.439 14.561 60 30 60 L 33 60 C 48.439 60 61 47.439 61 32 C 61 26.600951 59.460842 21.556431 56.802734 17.275391 L 62.039062 12.039062 L 59.210938 9.2109375 L 54.423828 13.998047 C 49.283425 7.890176 41.589024 4 33 4 L 30 4 z M 33 8 C 40.486401 8 47.18112 11.446946 51.585938 16.835938 L 33.375 35.046875 L 21.664062 23.335938 L 18.835938 26.164062 L 33.375 40.703125 L 53.882812 20.195312 C 55.862819 23.683904 57 27.710097 57 32 C 57 45.234 46.233 56 33 56 C 19.767 56 9 45.234 9 32 C 9 18.766 19.767 8 33 8 z"></path>
                    </svg>
                </a></p>
        </form>

    </div>



<?php
require_once "../components/footer.php";
?>