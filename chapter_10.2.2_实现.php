<?php
abstract class Unit{
    abstract function addUnit(Unit $unit);
    abstract function removeUnit(Unit $unit);
    abstract function bombardStrength();
}

class Army extends Unit{
    private $units = [];

    function addUnit(Unit $unit){
        if(!in_array($this->units,$unit)){
            array_push($this->units,$unit);
        }
    }

    function removeUnit(Unit $unit){
        $this->units = array_udiff($this->units,[$unit],function($a,$b){
            return !($a===$b);
        });
    }

    function bombardStrength(){
        $strength = 0;
        foreach($this->units as $unit){
            $strength += $unit->bombardStrength();
        }
        return $strength;
    }
}

class Archer extends Unit{
    function addUnit(Unit $unit){
        throw new Exception("Archer（射手）不可以作为其他成员的容器");
    }

    function removeUnit(Unit $unit){
        throw new Exception("Archer（射手）不可以作为其他成员的容器");
    }

    function bombardStrength(){
        return 4;
    }
}

class LaserCannonUnit extends Unit{
    function addUnit(Unit $unit){
        throw new Exception("LaserCannonUnit（激光炮）不可以作为其他成员的容器");
    }

    function removeUnit(Unit $unit){
        throw new Exception("LaserCannonUnit（激光炮）不可以作为其他成员的容器");
    }

    function bombardStrength(){
        return 44;
    }
}

// 体会一下改写后的便利性；

// 创建一个Army对象
$main_army = new Army();

// 添加基础战斗单元
$main_army->addUnit( new Archer() );
$main_army->addUnit( new LaserCannonUnit() );

// 创建一个新的Army对象
$sub_army = new Army();
// 添加基础战斗单元
$sub_army->addUnit(new Archer());
$sub_army->addUnit(new Archer());
$sub_army->addUnit(new Archer());

// 合并军队
$main_army->addUnit($sub_army);

echo "the strength of the main army is: {$main_army->bombardStrength()}"; // 60

