<?php
# Подключение инициализационного файла
require_once 'inc/core/init.php';

# Проверка входных данных
$em = Input::getPost('email');$pa = Input::getPost('pass');$pa1 = Input::getPost('pass1');
if (!empty($em) && (!empty($pa)) && (!empty($pa1))){
    # Пароль == Еще раз введенный пароль
    $respass = Input::getPost('pass') == Input::getPost('pass1') ? true : false;

    if ($respass){
        # Клас пользователь
        $us = new User();
        # Регистрация
        $us->Registration();
        # Проверка наличия ошибок при регистрации
        if(empty($us->error)) {
            $er = "Поздравляем! Вы успешно зарегистрировались!";
        }
        else{$er = $us->status;}
        unset($us);
    }
    else{$er = "Данные не корректны!";}
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
                        <input name="pass1" type="password" class="form-control" placeholder="Retype password" id="pass1"><br>
                        <input name="submit" type="submit" value="Зарегистрироваться" class="btn btn-primary" id="zareg">
                        <input type="button" value="Авторизация" class="btn btn-link" id="auto">

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

    // Событие на нажатие кнопки - переход к авторизации
    document.getElementById('auto').addEventListener('click', function(){
        document.location.replace('/login.php');
    }, false);

</script>



</body>
</html>
