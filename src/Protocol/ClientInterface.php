<?php
// +----------------------------------------------------------------------
// | SwooleClient回调函数
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Protocol;

interface ClientInterface
{
    public function onConnect(\Swoole\Client $client);
    public function onReceive(\Swoole\Client $client, string $data);
    public function onClose(\Swoole\Client $client);
    public function onError(\Swoole\Client $client);
}
