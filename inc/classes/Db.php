<?php
# Класс для взаимодействия с БД
Class Db
{
    private $config;
    private static $_instance = null;
    private $_pdo, $_query, $_res, $_error, $_count;

    private function __construct()
    {
        try {
            $config = $GLOBALS['config'];
            $this->_pdo = new PDO('mysql:host=' . $config["host"] . ';dbname=' . $config["dbname"], $config["user"], $config["pass"]);
            $this->_pdo->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    //**************************************************
    public static function GetInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Db();
        }
        return self::$_instance;
    }

    # Запрос к БД
    //**************************************************
    public function ActionData($sql)
    {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            if (!$this->_query->execute()) {
                $this->_error = true;
            }
        }
        return $this;
    }

    # Запрос к БД с параметрами и возврат результата
    //**************************************************
    public function Qparam($sql, $params=array())
    {
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql))
        {
            $x= 1;
            foreach($params as $par)
            {
                $this->_query->bindParam($x, $par);
                $x++;
            }
        }

        if(@$this->_query->execute()){
            $this->_res = $this->_query->fetchAll(PDO::FETCH_OBJ);
            $this->_count = $this->_query->rowCount();
        }else{$this->_error=true;}

        return $this;
    }

    //**************************************************
    public function Result(){
        return $this->_res;
    }

    //**************************************************
    public function Count(){
        return $this->_count;
    }

    //**************************************************
    public function Error(){
        return $this->_error;
    }

}