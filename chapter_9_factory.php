<?php
// 9.2 单例模式
// 添加静态方法和静态属性来间接实例化对象

class Preferences {
    private static $instance;
    private $props = [];

    private function __construct(){}

    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setProperty($key,$value){
        $this->props[$key] = $value;
    }

    public function getProperty($key){
        return $this->props[$key];
    }

}