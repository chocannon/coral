<?php
// +----------------------------------------------------------------------
// | 进程检测辅助类
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Utility;

class Posix{
    /**
     * 根据文件获取PID
     * @param  string $pidFile 进程号文件
     * @return int             进程号
     */
    public static function getPid(string $pidFile)
    {
        $pid = 0;
        if (file_exists($pidFile)) {
            $pid = file_get_contents($pidFile);
        }
        return !empty($pid) ? $pid : 0;
    }


    /**
     * 检测进程是否存活
     * @param  int    $pid [description]
     * @return [type]      [description]
     */
    public static function running(int $pid)
    {
        return posix_kill($pid, 0);
    }


    /**
     * 杀死进程
     * @return [type] [description]
     */
    public static function kill(int $pid)
    {
        return posix_kill($pid, 15);
    }


    /**
     * 重启进程
     * @param  int    $pid [description]
     * @return [type]      [description]
     */
    public static function reload(int $pid)
    {
        return posix_kill($pid, 10);
    }
}