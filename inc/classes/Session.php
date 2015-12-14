<?php
# Статический класс для работы с сессиями
Class Session
{
    # Проверка существования сессионной переменной
    public static function exists($name){
        return (isset($_SESSION[$name])) ? true : false;
    }

    # Присвоение сессионной переменной значения
    public static function put($name, $value){
        return $_SESSION[$name]=$value;
    }

    # Возврат сессионной переменной
    public static function get($name){
        return $_SESSION[$name];
    }

    # Удаление сессионной переменной
    public static function delete($name){
        if(self::exists($name)){
            unset ($_SESSION[$name]);
        }
    }

}



?>