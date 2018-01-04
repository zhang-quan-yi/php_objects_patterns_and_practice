<?php
abstract class Command{
    abstract function execute(CommandContext $context);
}

class LoginCommand extends Command{
    function execute(CommandContext $context){
        $user = $context->get('username');
        $pass = $context->get('pass');

        // login($user,$pass);
        $user_obj = [13,'qy','man'];
        $context->addParam("user",$user_obj);
        return true;
    }
}

class FeedbackCommand extends Command{
    function execute(CommandContext $context){
        $email = $context->get('email');
        $msg = $context->get('msg');
        $topic = $context->get('topic');
        $result = "send msg to database: $email|$msg|$topic";
        return $result;
    }
}

class CommandContext{
    private $params = [];
    private $error = "";

    function __construct(){
        $this->params = [];
    }

    function get($key){
        return $this->params[$key];
    }

    function setError($error){
        $this->error = $error;
    }

    function getError(){
        return $this->error;
    }

    function addParam($key,$value){
        $this->params[$key] = $value;
    }
}

class CommandFactory{
    private static $dir = 'commands';

    static function getCommand($action="Default"){
        if(preg_match('/\W/',$action)){
            throw new Exception('illegal characters in action');
        }

        $class = UCFirst(strtolower($action))."Command";
        $file = self::$dir . DIRECTORY_SEPARATOR . "{$class}.php";
        if(!file_exists($class)){
            throw new Exception("could not find $file");
        }
        require_once($file);
        if(!class_exists($class)){
            throw new Exception("no $class class located");
        }

        $cmd = new $class();
        return $cmd;
    }
}

class Controller{
    private $context;
    function __construct() {
        $this->context = new CommandContext();
    }

    function getContext(){
        return $this->context;
    }

    function process(){
        $cmd = CommandFactory::getCommand($this->context->get('action'));
        if(!$cmd->execute($this->context)){
            // 处理失败
        }else{
            // 处理成功
        }
    }
}

$controller = new Controller();

// 伪造用户请求。。。
$context = $controller->getContext();
$context->addParam('action','login');
$context->addParam('username','bob');
$context->addParam('pass','123456');

$controller->process();