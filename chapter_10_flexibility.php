<?php
// 一个叫做"文明"的游戏
abstract class Unit{
    // 返回Unit的攻击强度
    abstract function bombardStrength();
}

// 射手
class Archer extends Unit{
    function bombardStrength(){
        return 4;
    }
}

// 激光炮
class LaserCannonUnit extends Unit{
    function bombardStrength(){
        return 44;
    }
}

// 定义一个类来组合战斗单元
class Army{
    private $units = [];

    function addUnit(Unit $unit){
        array_push($this->units,$unit);
    }

    function bombardStrength(){
        $strength = 0;
        foreach($this->units as $unit){
            $strength +=$unit->bombardStrength();
        }
        return $strength;
    }
}


