<?php
# Подключение инициализационного файла
require_once 'inc/core/init.php';
# Если пользователь авторизован - переход к главной странице
if(Session::exists('user')){
    header('Location: /index.php');
}
else{
    # Проверка входных данных
    $em = Input::getPost('email');$pa = Input::getPost('pass');
    if (!empty($em) && (!empty($pa))){
        # Клас пользователь
        $us = new User();
        # Авторизация
        $us->Login();
        # Проверка наличия ошибок при регистрации
        if(empty($us->error)) {
            # Создание сессионных переменных
            Session::put('user',$us->status);Session::put('email',Input::getPost('email'));
            #переход к главной странице
            header('Location: /index.php');
            exit();
        }
        else {$er = "Некорректные данные!";}
    }
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/man.css">
    <title></title>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-4"></div>
        <div class="col-md-4 col-xs-12 col-sm-4">

            <div class="panel panel-default" id="login_panel" >
                <div class="panel-body">

                    <form method="POST" action="">
                        <input name="email" type="text" class="form-control" placeholder="Email" id="login">
                        <input name="pass" type="password" class="form-control" placeholder="Password more 6 chars" id="pass">
                        <input name="submit" type="submit" value="Войти" class="btn btn-primary" id="vxod">
                        <input type="button" value="Регистрация" class="btn btn-link" id="reg">

                    </form>
                </div>
            </div>
            <br>
            <span id="err"><?php echo $er; ?></span>

        </div>

        <div class="col-xs-4">
        </div>

    </div>
</div>


<script type="text/javascript">

    // Событие на нажатие кнопки - переход к регистрации
    document.getElementById('reg').addEventListener('click', function(){
        document.location.replace('/registration.php');
    }, false);

</script>
</body>
</html>
