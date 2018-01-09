<?php
// +----------------------------------------------------------------------
// | Swoole Server 回调函数
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Traits;

use Coral\Utility\Process;

trait ServerCallBack{
    public function onStart(\Swoole\Server $serv)
    {
        file_put_contents($this->masterPidFile, $serv->master_pid);
        file_put_contents($this->managePidFile, $serv->manager_pid);
        Process::setName($this->processName . ': master');
    }


    public function onManagerStart(\Swoole\Server $serv)
    {
        Process::setName($this->processName . ': manager');
    }


    public function onWorkerStart(\Swoole\Server $server, int $workerId)
    {
        if ($workerId >= $this->config['worker_num']) {
            Process::setName($this->processName . ': tasker');
        } else {
            Process::setName($this->processName . ': worker');
        }
    }


    public function onConnect(\Swoole\Server $serv, int $fd, int $reactorId)
    {

    }


    public function onReceive(\Swoole\Server $serv, int $fd, int $reactorId, string $data)
    {

    }


    public function onShutdown(\Swoole\Server $serv)
    {

    }
    
    
    public function onManagerStop(\Swoole\Server $serv)
    {
        
    }


    public function onWorkerStop(\Swoole\Server $server, int $workerId)
    {

    }


    public function onClose(\Swoole\Server $serv, $fd, $reactorId)
    {

    }


    public function onTask(\Swoole\Server $serv, int $taskId, int $workId, string $data)
    {

    }

    public function onFinish(\Swoole\Server $serv, int $taskId, string $data)
    {

    }

    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        
    }

    public function onOpen(\Swoole\Websocket\Server $serv, \Swoole\Http\Request $request)
    {

    }

    public function onMessage(\Swoole\Websocket\Server $server, \Swoole\Websocket\Frame $frame)
    {

    }
}