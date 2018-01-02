<?php
// 正交性：
// 程序的目标应该是创建在改动和转移时对其他组件影响最小对组件。

// 为 handleLogin函数添加新的功能：记录访问者ip、登入失败发送邮件等等。。。

class Login{
    const LOGIN_USER_UNKNOWN = 1;
    const LOGIN_WRONG_PASS = 2;
    const LOGIN_ACCESS = 3;

    private $status = array();

    function handleLogin($user,$pass,$ip){
        switch(rand(1,3)){
            case 1:
                $this->setStatus(self::LOGIN_ACCESS,$user,$ip);
                $ret = true;
                break;
            case 2:
                $this->setStatus(self::LOGIN_WRONG_PASS,$user,$ip);
                $ret = false;
                break;
            case 3:
                $this->setStatus(self::LOGIN_USER_UNKNOWN,$user,$ip);
                $ret = false;
                break;
        }
        // Logger::logIP($user,$ip,$this->getStatus());

        // if(!$ret){
        //  Notifier::mailWarning($user,$ip,$this->getStatus());
        //}

        // return $ret;
    }

    private function setStatus($status,$user,$ip){
        $this->status = array($status,$user,$ip);
    }

    function getStatus(){
        return $this->status;
    }
}

// 如果像这样直接在代码中加入功能来满足需求，会破坏设计，Login类很快会紧紧嵌入到这个特殊的系统中；

// 使用观察者模式
interface Observable{
    function attach(Observer $observer);
    function detach(Observer $observer);
    function notify();
}

class LoginObservable implements Observable{
    const LOGIN_USER_UNKNOWN = 1;
    const LOGIN_WRONG_PASS = 2;
    const LOGIN_ACCESS = 3;

    private $status = [];
    private $observers;

    function __construct() {
        $this->observers = array();
    }

    function attach(Observer $observer){
        $this->observers[] = $observer;
    }

    function detach(Observer $observer) {
        $newObservers = array();
        foreach($this->observers as $obs){
            if($obs !== $observer){
                $newObservers[] = $obs;
            }
        }
        $this->observers = $newObservers;
    }

    function notify(){
        foreach($this->observers as $obs){
            $obs->update($this);
        }
    }

    function setStatus($status,$user,$ip){
        $this->status = [$status,$user,$ip];
    }

    function getStatus(){
        return $this->status;
    }

    function handleLogin($user,$pass,$ip){
        switch(rand(1,3)){
            case 1:
                $this->setStatus(self::LOGIN_ACCESS,$user,$ip);
                $ret = true;
                break;
            case 2:
                $this->setStatus(self::LOGIN_WRONG_PASS,$user,$ip);
                $ret = false;
                break;
            case 3:
                $this->setStatus(self::LOGIN_USER_UNKNOWN,$user,$ip);
                $ret = false;
                break;
        }
        $this->notify();
        return $ret;
    }
}

interface Observer{
    function update(Observable $observable);
}

abstract class LoginObserver implements Observer{
    private $login;

    // LoginObservable类规范了 getStatus方法；
    function __construct(LoginObservable $login){
        $this->login = $login;
        $login->attach($this);
    }

    function update(Observable $observable){
        if($observable === $this->login){
            $this->doUpdate($observable);
        }
    }

    abstract function doUpdate(LoginObservable $login);
}

class SecurityMonitor extends LoginObserver{
    function doUpdate(LoginObservable $login) {
        $status = $login->getStatus();
        if($status[0] === LoginObservable::LOGIN_WRONG_PASS){
            print __CLASS__ . ":\t sending mail to sysadmin \n";
        }
    }
}

class GeneralLogger extends LoginObserver{
    function doUpdate(LoginObservable $login) {
        print __CLASS__ . ": \t add login data to log\n";
    }
}

class PartnershipTool extends LoginObserver{
    function doUpdate(LoginObservable $login){
        print __CLASS__ . ":\t set cookie if IP matches a list \n";
    }
}

$login = new LoginObservable();
new SecurityMonitor($login);
new GeneralLogger($login);
new PartnershipTool($login);

