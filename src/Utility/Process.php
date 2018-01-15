<?php
// +----------------------------------------------------------------------
// | 进程控制台辅助类
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Utility;

class Process
{
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