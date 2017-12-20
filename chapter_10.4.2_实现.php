<?php
// 下面这个类为上面当过程式代码提供了一个接口：
class ProductFacade{
    private $products = array();
    private $file;

    function __construct($file) {
        $this->file = $file;
        $this->compile();
    }

    private function compile(){
        $lines = readLinesFromFile($this->file);
        foreach($lines as $line){
            $id = getIdFromLine($line);
            $name = getNameFromLine($line);
            $this->products[$id] = createProductObjectFromId($id,$name);
        }
    }

    public function getProducts(){
        return $this->products;
    }

    public function getProduct($id){
        return $this->products[$id];
    }
}

// 使用 示例
// 从客户端代码角度来看，现在从一个log文件访问product对象简单多了；
$facade = new ProductFacade('c10_test.txt');
var_dump($facade->getProducts());

//    array(2) {
//        [234]=>
//      object(Product)#2 (2) {
//      ["id"]=>
//        string(3) "234"
//        ["name"]=>
//        string(7) "quan yi"
//      }
//      [532]=>
//      object(Product)#3 (2) {
//      ["id"]=>
//        string(3) "532"
//    ["name"]=>
//        string(7) "hai lei"
//      }
//    }




function readLinesFromFile($file){
    return file($file);
}

function createProductObjectFromId($id,$productName){
    return new Product($id,$productName);
}

function getNameFromLine($line){
    if(preg_match("/.*-(.*)\s\d+/",$line,$array)){
        return str_replace('_',' ',$array[1]);
    }
    return 'name';
}

function getIdFromLine($line){
    if(preg_match("/^(\d{1,3})-/",$line,$array)){
        return $array[1];
    }
    return -1;
}

class Product{
    public $id;
    public $name;
    function __construct($id,$name) {
        $this->id = $id;
        $this->name = $name;
    }
}

