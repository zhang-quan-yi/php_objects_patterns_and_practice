<?php
// 单例模式
class Preferences {
    private $props = [];

    private function __construct(){}

    public function setProperty($key,$value){
        $this->props[$key] = $value;
    }

    public function getProperty($key){
        return $this->props[$key];
    }

}