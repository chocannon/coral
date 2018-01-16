<?php
// +----------------------------------------------------------------------
// | SwooleClient回调函数
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Interfs;

use Swoole;

interface ClientInterface
{
    public function onConnect(Client $client);
    public function onReceive(Client $client, string $data);
    public function onClose(Client $client);
    public function onError(Client $client);
}
