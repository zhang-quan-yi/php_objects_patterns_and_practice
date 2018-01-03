<?php
abstract class Unit{
    protected $depth = 0;

    function accept(ArmyVisitor $visitor){
        $method = "visit" . get_class($this);
        $visitor->$method($this);
    }

    protected function setDepth($depth){
        $this->depth = $depth;
    }

    function getDepth(){
        return $this->depth;
    }

    abstract function bombardStrength();
}

abstract class ContainerUnit extends Unit{
    protected $units = [];
    function addUnit(Unit $unit){
        foreach($this->units as $thisUnit){
            if($unit === $thisUnit){
                return ;
            }
        }
        $unit->setDepth($this->depth + 1);
        $this->units[] = $unit;
    }

    function accept(ArmyVisitor $visitor){
        $method = "visit" . get_class($this);
        $visitor->$method($this);
        foreach($this->units as $thisUnit){
            $thisUnit->accept($visitor);
        }
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

class Archer extends Unit{
    function bombardStrength(){
        return 2;
    }
}

class Cavalry extends Unit{
    function bombardStrength(){
        return 4;
    }
}

class LaserCannonUnit extends Unit{
    function bombardStrength(){
        return 6;
    }
}

class TroopCarrierUnit extends Unit{
    function bombardStrength(){
        return 18;
    }
}

abstract class ArmyVisitor{
    abstract function visit(Unit $node);

    function visitArmy(Army $node){
        $this->visit($node);
    }

    function visitArcher(Archer $node){
        $this->visit($node);
    }

    function visitCavalry(Cavalry $node){
        $this->visit($node);
    }

    function visitLaserCannonUnit(LaserCannonUnit $node){
        $this->visit($node);
    }

    function visitTroopCarrierUnit(TroopCarrierUnit $node){
        $this->visit($node);
    }
}

class TextDumpArmyVisitor extends ArmyVisitor{
    private $text = "";

    function visit(Unit $node){
        $ret = "";
        $pad = 4 * $node->getDepth();
        $ret .= sprintf("%{$pad}s","");
        $ret .= get_class($node).": ";
        $ret .= "bombard: " . $node->bombardStrength() . "\n";
        $this->text .=$ret;
    }

    function getText(){
        return $this->text;
    }
}

$main_army = new Army();
$main_army->addUnit(new Archer());
$main_army->addUnit(new Cavalry());
$main_army->addUnit(new LaserCannonUnit());
$main_army->addUnit(new TroopCarrierUnit());

$text_dump_visitor = new TextDumpArmyVisitor();
$main_army->accept($text_dump_visitor);
print $text_dump_visitor->getText();

class TaxCollectionVisitor extends ArmyVisitor{
    private $due = 0;
    private $report = "";

    function visit(Unit $node){
        $this->levy($node,1);
    }

    function visitArcher(Archer $node){
        $this->levy($node,2);
    }

    function visitCavalry(Cavalry $node){
        $this->levy($node,3);
    }

    function visitTroopCarrierUnit(TroopCarrierUnit $node){
        $this->levy($node,5);
    }

    private function levy(Unit $unit,$amount){
        $this->report .= "Tax levied for ".get_class($unit);
        $this->report .= ": $amount\n";
        $this->due += $amount;
    }

    function getReport(){
        return $this->report;
    }

    function getTax(){
        return $this->due;
    }
}
$texVisitor = new TaxCollectionVisitor();
$main_army->accept($texVisitor);
print $texVisitor->getReport();

// 将具体的业务分离到具体的访问者对象中，而基础的单元对象中则保存自己的基础属性信息；仅提供一个accept方法给相应的访问者提供必要的信息；