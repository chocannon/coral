<?php
// +----------------------------------------------------------------------
// | 进程控制台辅助类
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Utility;

class Process{
    protected $logFile = '/tmp/swoole.log';

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
    
    
    /**
     * 设置进程名称
     * @param string $name 自定义进程名称
     */
    public static function setName(string $name)
    {
        if (function_exists('swoole_set_process_name')) {
            swoole_set_process_name($name);
        } elseif (function_exists('cli_set_process_title')) {
            cli_set_process_title($name);
        } else {
            throw new Exception("Can Not Rewrite Process Name!", 1);
        }
    }
}