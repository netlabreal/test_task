<?php
// Ќачало сессии
session_start();
// »нициализаци€ глобальных переменных
$GLOBALS['config'] = array('host' => 'localhost', 'user'=>'045623242_alex','pass'=>'1234','dbname'=>'litab_test','path'=>'../img/data/');
//$GLOBALS['config'] = array('host' => '192.168.1.100', 'user'=>'alexandr','pass'=>'rfqkfc','dbname'=>'test','path'=>'../img/data/');
//автозагрузка классов
spl_autoload_register(function($class){
    require_once $_SERVER['DOCUMENT_ROOT'].'/inc/classes/'.$class.'.php';
});
?>