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

// 9.3 工厂方法模式

abstract class ApptEncoder{
    abstract function encode();
}

class BlogsApptEncoder extends ApptEncoder{
    function encode(){
        return "Appointment data encoded in BloggsCal format\n";
    }
}

class MegaApptEncoder extends ApptEncoder{
    function encode(){
        return "Appointment data encoded in MegaCal format\n";
    }
}

// 负责生成ApptEncoder子类对象
class CommsManager{
    const BLOG = 1;
    const MEGA = 2;
    private $mode = 1;

    function __construct($mode){
        $this->mode = $mode;
    }

    function getApptEncoder(){
        switch ($this->mode){
            case(self::MEGA):
                return new MegaApptEncoder();
            default:
                return new BlogsApptEncoder();
        }
    }
}

// 使用示例
$common = new CommsManager(CommsManager::MEGA);
$appEncoder = $common->getApptEncoder();
echo $appEncoder->encode();
// 输出： Appointment data encoded in MegaCal format