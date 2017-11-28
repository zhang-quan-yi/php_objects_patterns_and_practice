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
abstract class CommsManager{
    abstract function getHeaderText();
    abstract function getApptEncoder();
    abstract function getFooterText();
}

class BlogsCommsManager extends CommsManager{
    function getHeaderText(){
        return "Blogs header\n";
    }
    function getApptEncoder(){
        return new BlogsApptEncoder();
    }
    function getFooterText(){
        return "Blogs footer\n";
    }
}

class MegaCommsManager extends CommsManager{
    function getHeaderText(){
        return "Mega header\n";
    }
    function getApptEncoder(){
        return new MegaApptEncoder();
    }
    function getFooterText(){
        return "Mega footer\n";
    }
}
// 使用示例
$blogComms = new BlogsCommsManager();
$appEncoder = $blogComms->getApptEncoder();
echo $appEncoder->encode();
// 输出： Appointment data encoded in MegaCal format