<?php
// +----------------------------------------------------------------------
// | Swoole Client 回调函数
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Traits;

use Coral\Utility\Console;

trait ClientCallBack
{
    public function onConnect(\Swoole\Client $client)
    {

    }


    public function onReceive(\Swoole\Client $client, string $data)
    {

    }


    public function onClose(\Swoole\Client $client)
    {

    }


    public function onError(\Swoole\Client $client)
    {

    }
}