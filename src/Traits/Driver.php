<?php
// +----------------------------------------------------------------------
// | 进程管理
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Traits;

use Coral\Utility\Posix;

trait Driver{
    /**
     * 接受命令行参数，执行动作
     * @return [type] [description]
     */
    public function run()
    {
        $argv1 = isset($_SERVER['argv'][1]) ? strtolower($_SERVER['argv'][1]) : '';
        switch ($argv1) {
            case '':
            case 'start':
                $this->start();
                break;
            case 'stop':
                $this->stop();
                break;
            case 'status':
                $this->status();
                break;   
            case 'reload':
                $this->reload();
                break;
            default:
                echo 'Usage : php file.php start | status | stop | reload' . PHP_EOL;
                break;
        }
    }


    /**
     * 启动服务
     * @return [type] [description]
     */
    public function start()
    {
        $pid = Posix::getPid($this->masterPidFile);
        if ($pid && Posix::running($pid)) {
            self::tip($this->processName . " : Server Is Running! Start \033[31m [FAIL] \033[0m");
            return false;
        }
        $this->initialization()->start();
        self::tip($this->processName . ": Start \033[34m [OK] \033[0m");
        return true;
    }


    /**
     * 查看服务状态
     * @return [type] [description]
     */
    public function status()
    {
        self::tip('Swoole Version : ' . SWOOLE_VERSION);
        $pid = Posix::getPid($this->masterPidFile);
        if (!$pid || !Posix::running($pid)) {
            self::tip($this->processName . " : Status \033[31m [DIE] \033[0m");
            return false;
        }
        self::tip($this->processName . ": Status \033[34m [LIVE] \033[0m");
        return true;
    }


    /**
     * 停止服务
     * @return [type] [description]
     */
    public function stop()
    {
        $pid = Posix::getPid($this->masterPidFile);
        if (!$pid) {
            self::tip($this->processName . " : PID File Not Found! Stop \033[31m [FAIL] \033[0m");
            return false;
        }
        if (!Posix::kill($pid)) {
            self::tip($this->processName . " : Send Signal Failed! Stop \033[31m [FAIL] \033[0m");
            return false;
        }
        unlink($this->masterPidFile);
        unlink($this->managePidFile);
        self::tip($this->processName . " : Stop \033[34m [SUCESS] \033[0m");
        return true;
    }


    /**
     * 重新加载服务
     * @return [type] [description]
     */
    public function reload()
    {
        $pid = Posix::getPid($this->managePidFile);
        if (!$pid) {
            self::tip($this->processName . " : PID File Not Found! Reload \033[31m [FAIL] \033[0m");
            return false;
        }
        if (!Posix::reload($pid)) {
            self::tip($this->processName . " : Send Signal Failed! Reload \033[31m [FAIL] \033[0m");
            return false;
        }
        self::tip($this->processName . " : Reload \033[34m [SUCESS] \033[0m");
        return true;
    }


    /**
     * 在控制台输出信息
     * @param  string $msg [description]
     * @return [type]      [description]
     */
    private static function tip(string $msg)
    {
        echo $msg . PHP_EOL;
    }
}