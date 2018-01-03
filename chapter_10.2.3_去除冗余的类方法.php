<?php
abstract class Unit{
    function getContainer(){
        return null;// 基本单元不可以作为容器
    }

    abstract function bombardStrength();
}

abstract class ContainerUnit extends Unit{
    protected $units = [];
    function getContainer(){
        return $this;
    }

    function addUnit(Unit $unit){
        if(!in_array($unit,$this->units)){
            array_push($this->units,$unit);
        }
    }

    function removeUnit(Unit $unit){
        $this->units = array_udiff($this->units,[$unit],function($a,$b){
            return ($a !== $b);
        });
    }
}

class Army extends ContainerUnit{
    function bombardStrength(){
        $strength = 0;

        foreach($this->units as $unit){
            $strength += $unit->bombardStrength();
        }
    }
}

class Archer extends Unit{
    function bombardStrength(){
        return 4;
    }
}

$unit = new Archer();
$army = new Army();

// 根据getContainer方法的返回值来判断该对象是否具有addUnit方法˚
if(!!$army->getContainer()){
    $army->addUnit($unit);
}