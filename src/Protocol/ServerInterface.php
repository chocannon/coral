<?php
// +----------------------------------------------------------------------
// | SwooleServer回调事件接口
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Protocol;

interface ServerInterface
{
    public function onStart(\Swoole\Server $serv);
    public function onShutdown(\Swoole\Server $serv);
    public function onManagerStart(\Swoole\Server $serv);
    public function onManagerStop(\Swoole\Server $serv);
    public function onWorkerStart(\Swoole\Server $server, int $workerId);
    public function onWorkerStop(\Swoole\Server $server, int $workerId);
    public function onConnect(\Swoole\Server $serv, int $fd, int $reactorId);
    public function onClose(\Swoole\Server $serv, $fd, $reactorId);
    public function onReceive(\Swoole\Server $serv, int $fd, int $reactorId, string $data);
    public function onTask(\Swoole\Server $serv, int $taskId, int $workId, string $data);
    public function onFinish(\Swoole\Server $serv, int $taskId, string $data);
}