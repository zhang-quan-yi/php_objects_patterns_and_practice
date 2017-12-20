<?php
// 从文件中读取log信息并将它转换为对象数据
// 让人混淆的代码
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

// 使用 示例代码；
$lines = readLinesFromFile('c10_test.txt');
$products = [];
foreach($lines as $line){
    $id = getIdFromLine($line);
    $name = getNameFromLine($line);
    $products[$id] = createProductObjectFromId($id,$name);
}
var_dump($products);

//array(2) {
//    [234]=>
//  object(Product)#1 (2) {
//  ["id"]=>
//    string(3) "234"
//    ["name"]=>
//    string(7) "quan yi"
//  }
//  [532]=>
//  object(Product)#2 (2) {
//  ["id"]=>
//    string(3) "532"
//["name"]=>
//    string(7) "hai lei"
//  }
//}

// 问题：
// 如果这些方法都是第三方方法，那么我们的代码会和子系统紧紧地耦合在一起。
// 当子系统变化时，或者我们决定将与子系统完全断开时，代码就会出问题。