<?php
abstract class Tile{
    abstract function getWealthFactor();
}

class Plains extends Tile{
    private $wealthfactor = 2;
    function getWealthFactor() {
        return $this->wealthfactor;
    }
}

class DiamondPlains extends Plains{
    function getWealthFactor(){
        return parent::getWealthFactor() + 2;
    }
}

class PollutedPlains extends Plains{
    function getWealthFactor(){
        return parent::getWealthFactor() - 4;
    }
}

// 获取财富系数
$tile = new PollutedPlains();
echo $tile->getWealthFactor();
// -2

// 问题：
// 可以获取 既含有钻石 又被污染的对象 吗？