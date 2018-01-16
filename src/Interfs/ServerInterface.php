<?php
// +----------------------------------------------------------------------
// | SwooleServer回调事件接口
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Interfs;

use Swoole;

interface ServerInterface
{
    public function onStart(Server $serv);
    public function onShutdown(Server $serv);
    public function onManagerStart(Server $serv);
    public function onManagerStop(Server $serv);
    public function onWorkerStart(Server $server, int $workerId);
    public function onWorkerStop(Server $server, int $workerId);
    public function onTask(Server $serv, int $taskId, int $workId, string $data);
    public function onFinish(Server $serv, int $taskId, string $data);
}