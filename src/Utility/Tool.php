<?php
// +----------------------------------------------------------------------
// | 进程控制台辅助类
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Utility;

class Tool{
    /**
     * 获取网卡IP
     * @return string ip
     */
    public static function getHost()
    {
        $ipList = swoole_get_local_ip();
        if (isset($ipList['eth1']) && !empty($ipList['eth1'])) {
            return $ipList['eth1'];
        }
        if (isset($ipList['eth0']) && !empty($ipList['eth0'])) {
            return $ipList['eth0'];
        }
        return '0.0.0.0';
    }
}