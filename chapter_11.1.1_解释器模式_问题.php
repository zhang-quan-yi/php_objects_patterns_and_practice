<?php
abstract class Expression{
    private static $key_count = 0;
    private $key;

    abstract function interpret(InterpreterContext $context);

    function get_key(){
        if(empty($this->key)){
            self::$key_count++;
            $this->key = self::$key_count;
        }
        return $this->key;
    }
}

class InterpreterContext{
    private $express_store =  array();

    function replace(Expression $exp,$value){
        $this->express_store[$exp->get_key()] = $value;
    }

    function lookup(Expression $exp){
        return $this->express_store[$exp->get_key()];
    }
}

class LiteralExpression extends Expression{
    private $value;

    function __construct($value){
        $this->value = $value;
    }

    function interpret(InterpreterContext $context){
        $context->replace($this,$this->value);
    }
}

$context = new InterpreterContext();
$literal = new LiteralExpression(4);
$literal->interpret($context);

echo $context->lookup($literal),PHP_EOL; // 4

class VariableExpression extends Expression{
    private $name;
    private $val;

    function __construct($name,$val=null){
        $this->name = $name;
        $this->val = $val;
    }

    function interpret(InterpreterContext $context) {
        if(!is_null($this->val)){
            $context->replace($this,$this->val);
            $this->val = null;
        }

    }

    function set_value($value){
        $this->val = $value;
    }

    function get_key() {
        return $this->name;
    }
}


$my_var = new VariableExpression('$number',4);
$my_var->interpret($context);
echo $context->lookup($my_var),PHP_EOL; // 4

$new_var = new VariableExpression('$number');
$new_var->interpret($context);
echo $context->lookup($new_var),PHP_EOL;

abstract class OperatorExpression extends Expression{
    protected $l_op;
    protected $r_op;

    function __construct(Expression $l_op,Expression $r_op) {
        $this->l_op = $l_op;
        $this->r_op = $r_op;
    }

    function interpret(InterpreterContext $context){
        $this->l_op->interpret($context);
        $this->r_op->interpret($context);

        $result_l = $context->lookup($this->l_op);
        $result_r = $context->lookup($this->r_op);
        $this->do_interpret($context,$result_l,$result_r);
    }

    abstract protected function do_interpret(InterpreterContext $context,$result_l,$result_r);
}

class EqualExpression extends OperatorExpression{
    protected function do_interpret(InterpreterContext $context,$result_l,$result_r){
        $context->replace($this,$result_l == $result_r);
    }
}

class BooleanOrExpression extends OperatorExpression{
    protected function do_interpret(InterpreterContext $context, $result_l, $result_r) {
        $context->replace($this,$result_l || $result_r);
    }
}

class BooleanAndExpression extends OperatorExpression{
    protected function do_interpret(InterpreterContext $context, $result_l, $result_r) {
        $context->replace($this,$result_l && $result_r);
    }
}

$input = new VariableExpression('input');
$statement = new BooleanOrExpression(
    new EqualExpression($input,new LiteralExpression('four')),
    new EqualExpression($input,new LiteralExpression(4))
);
$context->lookup($statement);

echo '_________ test exp start __________',PHP_EOL;
foreach(['four','4','52'] as $item){
    $input->set_value($item);
    print "$item: \n";
    $statement->interpret($context);
    if($context->lookup($statement)){
        print "marks \n";
    }else{
        print "ooop.... \n";
    }
}

echo '________ test exp end __________',PHP_EOL;