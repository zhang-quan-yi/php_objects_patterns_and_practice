<?php
// Unit单元里除了有bombardStrength方法，再添加存储节点的文本信息；
abstract class Unit{
    function getContainer(){
        return null;
    }

    abstract function bombardStrength();

    function textDump($num=0){
        $ret = "";
        $pad = 4*$num;
        $ret .=sprintf("%{$pad}s","");
        $ret .=get_class($this).": ";
        $ret .="bombard: ".$this->bombardStrength()."\n";
        return $ret;
    }
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
        $this->units = array_diff($this->units,[$unit],function($a,$b){
            return ($a !== $b);
        });
    }

    function textDump($num=0){
        $ret = parent::textDump($num);
        foreach($this->units as $unit){
            $ret .= $unit->textDump($num+1);
        }
        return $ret;
    }
}

class Army extends ContainerUnit{
    function bombardStrength(){
        $ret = 0;
        foreach($this->units as $unit){
            $ret += $unit->bombardStrength();
        }
        return $ret;
    }
}

class LaserCannonUnit extends Unit{
    function bombardStrength(){
        return 44;
    }
}

// 我们可能还需要继续创建统计树中单元个数的方法、保存组件到数据库的方法和计算军队的食物消耗的方法；
// 所有这些方法都将加入到容器类中，这样有助于在组合结构中较为轻松地访问相关节点；
// 但并非每个需要遍历对象树的操作都要在容器类中占据位置；