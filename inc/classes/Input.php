<?php
# Статический класс для работы с входящими данными
Class Input{
    # Возврат POST вх. данных
    public static function getPost($item){
       $res = null;
       # Проверка на существование $_POST[$item]
       if (isset($_POST[$item])){
            $res = $_POST[$item];
       }
       return $res;
    }
}
?>