<?php
# —татический класс дл€ работы с сесси€ми
Class Session
{
    # ѕроверка существовани€ сессионной переменной
    public static function exists($name){
        return (isset($_SESSION[$name])) ? true : false;
    }

    # ѕрисвоение сессионной переменной значени€
    public static function put($name, $value){
        return $_SESSION[$name]=$value;
    }

    # ¬озврат сессионной переменной
    public static function get($name){
        return $_SESSION[$name];
    }

    # ”даление сессионной переменной
    public static function delete($name){
        if(self::exists($name)){
            unset ($_SESSION[$name]);
        }
    }

}



?>