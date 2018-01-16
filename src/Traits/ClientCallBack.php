<?php
// +----------------------------------------------------------------------
// | Swoole Client 回调函数
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Traits;

use Swoole;
use Coral\Utility\Console;

trait ClientCallBack
{
    public function onConnect(Client $client)
    {
    }


    public function onReceive(Client $client, string $data)
    {
    }


    public function onClose(Client $client)
    {
    }


    public function onError(Client $client)
    {
    }
}