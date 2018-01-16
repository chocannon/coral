<?php
// +----------------------------------------------------------------------
// | Swoole Server 回调函数
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Traits;

use Swoole;
use Coral\Utility\Process;

trait ServerCallBack
{
    public function onStart(Server $serv)
    {
        file_put_contents($this->masterPidFile, $serv->master_pid);
        file_put_contents($this->managePidFile, $serv->manager_pid);
        Process::setName($this->processName . ': master');
    }


    public function onManagerStart(Server $serv)
    {
        Process::setName($this->processName . ': manager');
    }


    public function onWorkerStart(Server $server, int $workerId)
    {
        if ($workerId >= $this->config['worker_num']) {
            Process::setName($this->processName . ': tasker');
        } else {
            Process::setName($this->processName . ': worker');
        }
    }


    public function onConnect(Server $serv, int $fd, int $reactorId)
    {
    }


    public function onReceive(Server $serv, int $fd, int $reactorId, string $data)
    {
    }


    public function onShutdown(Server $serv)
    {
    }
    
    
    public function onManagerStop(Server $serv)
    {   
    }


    public function onWorkerStop(Server $server, int $workerId)
    {
    }


    public function onClose(Server $serv, $fd, $reactorId)
    {
    }


    public function onTask(Server $serv, int $taskId, int $workId, string $data)
    {
    }


    public function onFinish(Server $serv, int $taskId, string $data)
    {
    }


    public function onRequest(Http\Request $request, Http\Response $response)
    {   
    }


    public function onOpen(Websocket\Server $serv, Http\Request $request)
    {
    }


    public function onMessage(Websocket\Server $server, Websocket\Frame $frame)
    {
    }
}