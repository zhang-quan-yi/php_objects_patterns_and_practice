<?php
abstract class Tile{
    abstract function getWealthFactor();
}

class Plains extends Tile{
    private $wealthfactor = 2;
    function getWealthFactor(){
        return $this->wealthfactor;
    }
}

abstract class TileDecorator extends Tile{
    protected $tile;
    function __construct(Tile $tile) {
        $this->tile = $tile;
    }
}

class DiamondDecorator extends TileDecorator{
    function getWealthFactor() {
        return $this->tile->getWealthFactor() +2;
    }
}

class PollutionDecorator extends TileDecorator{
    function getWealthFactor() {
        return $this->tile->getWealthFactor() -4;
    }
}

// 使用示例
$tile = new Plains();
echo $tile->getWealthFactor(),PHP_EOL;// 2

$tile = new DiamondDecorator(new Plains);
echo $tile->getWealthFactor(),PHP_EOL;// 4

$tile = new PollutionDecorator(new DiamondDecorator(new Plains()));
echo $tile->getWealthFactor(),PHP_EOL;// 0

// 总结：
// 通过使用大量的装饰器，我们可以轻松、灵活地创建大量的新的组件

// 另外一个例子
class RequestHelper{}

abstract class ProcessRequest{
    abstract function process(RequestHelper $req);
}

class MainProcess extends ProcessRequest{
    function process(RequestHelper $req){
        print __CLASS__ . ": doing something useful with request\n";
    }
}

abstract class DecorateProcess extends ProcessRequest{
    protected $processRequest;
    function __construct(ProcessRequest $req) {
        $this->processRequest = $req;
    }
}

class LogRequest extends DecorateProcess{
    function process(RequestHelper $req) {
        print __CLASS__ . ": logging request\n";
        $this->processRequest->process($req);
    }
}

class AuthenticateRequest extends DecorateProcess{
    function process(RequestHelper $req) {
        print __CLASS__ . ": authenticating request\n";
        $this->processRequest->process($req);
    }
}

class StructureRequest extends DecorateProcess{
    function process(RequestHelper $req) {
        print __CLASS__ . ": structuring request data\n";
        $this->processRequest->process($req);
    }
}

$process = new AuthenticateRequest(new StructureRequest(
                                   new LogRequest(
                                   new MainProcess()
                                   )));
$process->process(new RequestHelper());

// AuthenticateRequest: authenticating request
// StructureRequest: structuring request data
// LogRequest: logging request
// MainProcess: doing something useful with request
