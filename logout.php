<?php
# Подключение инициализационного файла
require_once 'inc/core/init.php';
# Удаление сессионных переменных
Session::delete("user");Session::delete("email");
# переход к авторизации
header('Location: /login.php');
exit;
?>