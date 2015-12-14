<?php
# Класс пользователь (регистрация, авторизация)
class User {
    private $email;
    private $password;
    public $status;
    public $error;

    //**************************************************//
    # Инициализация переменных класса
    public function __construct() {
        $this->email = Input::getPost('email');
        $this->password = Input::getPost('pass');
        $this->status = null;
        $this->error = null;
    }
    //**************************************************//

    //**************************************************//
    # Проверка логина на соответствие email
    private function ValidLogin(){
        # Регулярное выражение, описывающее email
        return (bool)preg_match("/^[a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-.]+$/",$this->email);
    }
    //**************************************************//

    //**************************************************//
    # Проверка пароля на количество знаков
    private function ValidPassword(){
        return strlen($this->password)>6;
    }
    //**************************************************//

    //**************************************************//
    # Регистрация пользователя
    public function Registration(){
        if($this->ValidLogin() && $this->ValidPassword()){
            // Доступ к БД
            $d = Db::GetInstance();
            // Поиск пользователя
            $s = $d->Qparam("select * from users where email=?",array($this->email));
            // Количество найденных записей
            $rc = $s->Count();
            if($rc == 0) {
                $sql = "INSERT INTO users (email,password) VALUES ('" . $this->email . "','" . password_hash($this->password, PASSWORD_DEFAULT) . "')";
                // Внесение нового пользователя
                $s = $d->ActionData($sql);
                $this->error = $s->Error() ? 1 : null;
            }
            else{$this->error = 1; $this->status="Такой пользователь уже существует!";}
            unset($d);
        }
        else{$this->error = 1;$this->status="Данные не корректны!";}
    }
    //**************************************************//

    //**************************************************//
    # Авторизация пользователя
    public function Login(){
        // Проверка имени и пароля
        if($this->ValidLogin() && $this->ValidPassword()){
            // Доступ к БД
            $d = Db::GetInstance();
            // Поиск пользователя
            $s = $d->Qparam("select * from users where email=?",array($this->email));
            // Количество найденных записей
            $rc = $s->Count();
            if($rc != 0){
                $res = $s->Result()[0];
                # Проверка хеша пароля
                $this->error = password_verify($this->password, $res->password) ? null: 1;
                $this->status = $this->error != 1 ? $res->id : null;
            }
            else{$this->error = 1;}
            unset($d);
        }
        else{
            $this->error = 1;
        }
    }
    //**************************************************//

}
?>